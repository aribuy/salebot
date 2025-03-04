<?php
namespace App\Repositories\Client;
use App\Models\User;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Message;
use App\Models\Segment;
use App\Traits\ImageTrait;
use App\Traits\SimpleXLSX;
use App\Models\ClientStaff;
use App\Models\ContactsList;
use App\Traits\ContactTrait;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use App\Traits\TelegramTrait;
use App\Enums\MessageStatusEnum;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use App\Models\ContactRelationList;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactRelationSegments;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ContactRepository
{
    use ContactTrait, ImageTrait, RepoResponse,TelegramTrait;

    private $model;
    private $country;
    private $segment;
    private $contactsList;
    private $staff;

    public function __construct(
        Contact $model,
        Country $country,
        Segment $segment,
        ContactsList $contactsList,
        ClientStaff $staff
        )
    {
        $this->model = $model;
        $this->country = $country;
        $this->segment = $segment;
        $this->contactsList = $contactsList;
        $this->staff = $staff;
    }

    public function all($request)
    {

        return $this->model->latest()->withPermission()->where('type', $request->type)->paginate(setting('pagination'));
    }

    public function blockContacts($request)
    {
        return $this->model->latest()->withPermission()->where('type', $request->type)->where('is_blacklist', 1);
    } 

    public function getChatContactList($data = []): LengthAwarePaginator
    { 
        return $this->model->with(['lastMessage'])
        ->withPermission()
        ->when(arrayCheck('q', $data), function ($q) use ($data) {
            $q->where(function ($q) use ($data) {
                $q->where('name', 'like', '%'.$data['q'].'%')
                ->orWhere('phone', 'like', '%'.$data['q'].'%');
            });
        })->when(arrayCheck('type', $data), function ($query) use ($data) {
            $query->where('type', $data['type']);
        })->when(arrayCheck('assignee_id', $data), function ($query) use ($data) {
            $query->where('assignee_id', $data['assignee_id']);
        })->when(arrayCheck('tag_id', $data), function ($query) use ($data) {
            $query->whereHas('tags', function ($q) use ($data) {
                $q->where('id', $data['tag_id']);
            });
        })->where('has_conversation', 1)
        ->active()
        ->orderBy('last_conversation_at', 'DESC')
        ->where('is_blacklist', 0)
        ->paginate(12);
    }

    public function store(Request $request)
    { 
        DB::beginTransaction();
        try {
            $total_contacts      = Contact::where('client_id', auth()->user()->client_id)->where('status', 1)->count();
            $contacts_limit      = auth()->user()->activeSubscription->contact_limit;
            if ($total_contacts >= $contacts_limit) {
                return $this->formatResponse(false, __('insufficient_contacts_limit'), 'client.contacts.index', []);
            }
            $response['images']  = '';
            if (isset($request['images'])) {
                $requestImage = $request['images'];
                $response     = $this->saveImage($requestImage, '_contact_');
            }

            $contact             = new $this->model;
            $contact->name       = $request->name;
            $contact->phone      = str_replace(' ', '', $request->phone);
            $contact->country_id = $request->country_id;
            $contact->client_id  = Auth::user()->client->id;
            $contact->status     = $request->status ?? 1;
            $contact->images     = $response['images'];
            $contact->save();

            if (!empty($request->contact_list_id) && is_array($request->contact_list_id)) {
                foreach ($request->contact_list_id as $list_id) {
                    $contactRelationList                  = new ContactRelationList();
                    $contactRelationList->contact_id      = $contact->id;
                    $contactRelationList->contact_list_id = $list_id;
                    $contactRelationList->save();
                }
            } else {
                $contactList = $this->getOrCreateContactList(auth()->user()->client);
                $this->establishContactListRelations($contact, $contactList);
            }

            if (! empty($request->segment_id) && is_array($request->segment_id)) {
                foreach ($request->segment_id as $segment) {
                    $contactRelationSegment             = new ContactRelationSegments();
                    $contactRelationSegment->contact_id = $contact->id;
                    $contactRelationSegment->segment_id = $segment;
                    $contactRelationSegment->save();
                }
            } else {
                $defaultSegment = $this->getOrCreateDefaultSegment(auth()->user()->client);
                $this->establishContactSegmentRelations($contact, $defaultSegment);
            }
            DB::commit();
            return $this->formatResponse(true, __('created_successfully'), 'client.contacts.index', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.contacts.index', []);
        }
    }

    public function find($id)
    {
        return $this->model->withPermission()->with('contactList.list', 'segmentList.segment')->find($id);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if (isset($request['images'])) {
                $requestImage = $request['images'];
                $response     = $this->saveImage($requestImage, '_contact_');
            }
            $contact             = Contact::findOrFail($id);
            $contact->name       = $request->name;
            $contact->phone      = str_replace(' ', '', $request->phone);
            $contact->country_id = $request->country_id;
            $contact->client_id  = Auth::user()->client_id;
            $contact->status     = $request->status    ?? 1;
            $contact->images     = $response['images'] ?? $contact->images;
            $contact->save(); 

            ContactRelationList::whereIn('contact_id', [$id])->delete();

            if (!empty($request->contact_list_id)) {
                foreach ($request->contact_list_id as $list_id) {
                    $contactRelationList                  = new ContactRelationList();
                    $contactRelationList->contact_id      = $contact->id;
                    $contactRelationList->contact_list_id = $list_id;
                    $contactRelationList->save();
                }
            } else {
                // $contactList = $this->getOrCreateContactList(auth()->user()->client);
                // $this->establishContactListRelations($contact, $contactList);
            }

            ContactRelationSegments::whereIn('contact_id', [$id])->delete();

            if (! empty($request->segments)) {
                foreach ($request->segments as $segment) {
                    $contactRelationSegment             = new ContactRelationSegments();
                    $contactRelationSegment->contact_id = $contact->id;
                    $contactRelationSegment->segment_id = $segment;
                    $contactRelationSegment->save();
                }
            } else {
                // $defaultSegment = $this->getOrCreateDefaultSegment(auth()->user()->client);
                // $this->establishContactSegmentRelations($contact, $defaultSegment);
            }

            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'client.contacts.index', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }            
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.contacts.index', []);
        }
    }

    
    public function updateDetails($request, $id)
    {
        DB::beginTransaction();
        try {
            if (isset($request['images'])) {
                $requestImage = $request['images'];
                $response     = $this->saveImage($requestImage, '_contact_');
            }
            $contact             = Contact::findOrFail($id);
            $contact->country_id = $request->country_id;
            $contact->assignee_id= $request->assignee_id;
            if($request->email){
                $contact->email  = $request->email;
            }
            $contact->address    = $request->address;
            $contact->city       = $request->city;
            $contact->state      = $request->state;
            $contact->zipcode    = $request->zipcode;
            $contact->birthdate  = $request->birthdate;
            $contact->gender     = $request->gender;
            $contact->occupation = $request->occupation;
            $contact->company    = $request->company;
            $contact->bot_reply  = $request->bot_reply ? 1 : 0;
            $contact->is_blacklist = $request->is_blacklist ? 1 : 0;
            $contact->images     = $response['images'] ?? $contact->images;
            $contact->save(); 

            if (!empty($request->contact_list_id)) {
                ContactRelationList::whereIn('contact_id', [$id])->delete();
                foreach ($request->contact_list_id as $list_id) {
                    $contactRelationList                  = new ContactRelationList();
                    $contactRelationList->contact_id      = $contact->id;
                    $contactRelationList->contact_list_id = $list_id;
                    $contactRelationList->save();
                }
            } else {
                $contactList = $this->getOrCreateContactList(auth()->user()->client);
                $this->establishContactListRelations($contact, $contactList);
            }

            if (! empty($request->segment_id)) {
                ContactRelationSegments::whereIn('contact_id', [$id])->delete();
                foreach ($request->segment_id as $segment) {
                    $contactRelationSegment             = new ContactRelationSegments();
                    $contactRelationSegment->contact_id = $contact->id;
                    $contactRelationSegment->segment_id = $segment;
                    $contactRelationSegment->save();
                }
            } else {
                $defaultSegment = $this->getOrCreateDefaultSegment(auth()->user()->client);
                $this->establishContactSegmentRelations($contact, $defaultSegment);
            }

            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'client.contacts.index', []);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }            
           return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), 'client.contacts.index', []);
        }
    }

    public function blacklist($request)
    {
        $ids       = $request->ids;
        $blacklist = $request->is_blacklist;
        Contact::whereIn('id', $ids)->update(['is_blacklist' => $blacklist]);
    }

    public function removeBlacklist($request)
    {
        $ids       = $request->ids;
        $blacklist = $request->is_blacklist;

        Contact::whereIn('id', $ids)->update(['is_blacklist' => $blacklist]);
    }

    public function activeContacts($data)
    {
        return $this->model->where('status', 1)->when(arrayCheck('client_id', $data), function ($query) use ($data) {
            $query->where('client_id', $data['client_id']);
        })->when(arrayCheck('q', $data), function ($query) use ($data) {
            $query->where('name', 'LIKE', '%'.$data['q'].'%')->orWhere('phone', 'LIKE', '%'.$data['q'].'%');
        })->when(arrayCheck('type', $data), function ($query) use ($data) {
            $query->where('type', $data['type']);
        })->when(arrayCheck('assignee_id', $data), function ($query) use ($data) {
            $query->where('assignee_id', $data['assignee_id']);
        })->when(arrayCheck('tag_id', $data), function ($query) use ($data) {
            $query->whereHas('tags', function ($q) use ($data) {
                $q->where('id', $data['tag_id']);
            });
        })
        ->withPermission()
        ->where('contacts.is_blacklist',0)
        ->orderBy('name')->paginate(12);
    }

    public function addContactList(Request $request)
    {
        $ids = $request->ids;
        ContactRelationList::whereIn('contact_id', $ids)->delete();

        foreach ($ids as $id) {
            $contactRelationList                  = new ContactRelationList();
            $contactRelationList->contact_id      = $id;
            $contactRelationList->contact_list_id = $request->contact_list_id;
            $contactRelationList->save();
        }
    }

    public function addSegment(Request $request)
    {
        $ids = $request->ids;
        ContactRelationSegments::whereIn('contact_id', $ids)->delete();

        foreach ($ids as $id) {
            $contactSegments             = new ContactRelationSegments();
            $contactSegments->contact_id = $id;
            $contactSegments->segment_id = $request->segment_id;
            $contactSegments->save();
        }
    }

    public function removeContactList($request)
    {
        $ids = $request->ids;
        ContactRelationList::whereIn('contact_id', $ids)->update(['contact_list_id' => null]);
    }

    public function removeSegment($request)
    {
        $ids = $request->ids;
        ContactRelationSegments::whereIn('contact_id', $ids)->update(['segment_id' => null]);
    }

    public function readRatePercentage($request)
    {
        $delivered_message = Message::where('client_id', Auth::user()->client->id)->where('source', TypeEnum::WHATSAPP)->whereNotNull('campaign_id')->whereIn('status', [MessageStatusEnum::DELIVERED, MessageStatusEnum::READ])->count();
        $read_message      = Message::where('client_id', Auth::user()->client->id)->where('source', TypeEnum::WHATSAPP)->whereNotNull('campaign_id')->where('status', MessageStatusEnum::READ)->count();
        if ($delivered_message > 0) {
            $readRatePercentage = ($read_message / $delivered_message) * 100;
        } else {
            $readRatePercentage = 0;
        }

        return number_format($readRatePercentage, 0);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return Contact::find($id)->update($request);
    }

    public function addBlock($id)
    {
        $contact               = Contact::findOrfail($id);
        $contact->is_blacklist = 1;
        $contact->save();
        $data                  = [
            'status'  => true,
            'message' => __('successfully_blacklisted'),
        ];

        return $data;
    }

    public function removeBlock($id)
    {
        $contact               = Contact::findOrfail($id);
        $contact->is_blacklist = 0;
        $contact->save();
        $data                  = [
            'status'  => true,
            'message' => __('successful_remove_blacklist'),
        ];

        return $data;
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();  // Start transaction
        try {
            // Delete from related tables first
            DB::table('contact_relation_segments')->where('contact_id', $id)->delete();
            // DB::table('contact_notes')->where('contact_id', $id)->delete();
            DB::table('contact_relation_lists')->where('contact_id', $id)->delete();
            DB::table('messages')->where('contact_id', $id)->delete();
            // Now delete the main contact record
            $contact = $this->model->find($id);
            if ($contact) {
                $contact->delete();
                DB::commit();  // Commit transaction after successful deletions

                return $this->formatResponse(true, 'Deleted successfully', 'client.contacts.index', []);
            } else {
                return $this->formatResponse(false, 'Contact not found', 'client.contacts.index', []);
                throw new \Exception('Contact not found');
            }
        } catch (\Throwable $e) { 
            dd($e->getMessage());
            DB::rollBack();  // Rollback transaction in case of error

            return $this->formatResponse(false, $e->getMessage(), 'client.contacts.index', []);
        }
    }


    
    public function view(int $id)
    {
        try {
            $contact   = $this->model->with(['contact_flow','note','tags'])->withPermission()->find($id);
            $staffs    = User::where('client_id', Auth::user()->client->id)->get();
            $countries = $this->country->active()->pluck('name','id');
            $segments  = $this->segment->active()->withPermission()->pluck('title','id');
            $lists     = $this->contactsList->active()->withPermission()->pluck('name','id');
            $data      = [
                'staffs'  => $staffs,
                'contact'  => $contact,
                'segments'  => $segments,
                'lists'     => $lists,
                'countries' => $countries,
            ];
            $result =  view('backend.client.whatsapp.contacts.modal.__contact_body', $data)->render();
            return $this->formatResponse(true, __('data_found'), 'client.contacts.index', $result);
        } catch (\Throwable $e) { 
            dd($e->getMessage());
            return $this->formatResponse(false, $e->getMessage(), 'client.contacts.index', []);
        }
    }


    public function parseCSV($request)
    {
        try {
            $file     = $request->file('file');
            $xlsx     = SimpleXLSX::parse($file);
            $rows     = $xlsx->rows();
            unset($rows[0]);
            $all_rows = [];
            foreach ($rows as $row) {
                $all_rows[] = [
                    $row[0],
                    $row[1],
                ];
            }
            $data     = [
                'rows' => $all_rows,
            ];

            return $this->formatResponse(true, __('data_found'), 'client.contact.create', $data);
        } catch (\Exception $e) {
            return $this->formatResponse(false, $e->getMessage(), 'client.contact.create', []);
        }
    }

    public function confirmUpload($request)
    {
        $whatsappService = new WhatsAppService();
        try {
            $data   = json_decode($request->data);
            $array  = array_map('array_filter', $data);
            $data   = array_filter($array);
            if (! $data) {
                return response()->json([
                    'status'  => false,
                    'message' => __('no_row_found'),
                ]);
            }
            if (count($data) == 0) {
                return response()->json([
                    'status'  => false,
                    'message' => __('no_row_found'),
                ]);
            }
            if (count($data) > 0) {
                $rows           = [0, 1];
                //finding the empty rows
                $filled_rows    = [];
                $cells          = [];
                foreach ($data as $key => $datum) {
                    $cells[] = $key;
                    foreach ($datum as $row_key => $value) {
                        $filled_rows[$key][] = $row_key ?? null;
                    }
                }
                $validated_rows = [];

                foreach ($cells as $key => $cell) {
                    $diff_array = array_diff($rows, $filled_rows[$key]);
                    foreach ($diff_array as $item) {
                        if (! in_array($item, [0])) {
                            $validated_rows[] = [
                                'x' => $item,
                                'y' => $cell,
                            ];
                        }
                    }
                }
                if (count($validated_rows) > 0) {

                    return response()->json([
                        'status'  => false,
                        'rows'    => $validated_rows,
                        'message' => __('required_fields_are_missing'),
                        'errors'  => [],
                    ], 422);
                }
            }
            $errors = [];
            DB::beginTransaction();
            foreach ($data as $row) {
                if (isset($row[1])) {
                    $contact        = Contact::where('client_id', auth()->user()->client->id)->where('phone', $row[1])->orWhere('phone', '+'.$row[1])->first();
                    if (empty($contact)) {
                        $contact             = new Contact();
                        $contact->name       = $row['0'];
                        $contact->phone      = str_replace(' ', '', $row[1]);
                        $contact->country_id = $whatsappService->extractCountryCode($row[1]);
                        $contact->client_id  = auth()->user()->client->id;
                        $contact->save();
                    }
                    $contactListIds = array_filter(explode(';', $row[2] ?? ''), fn ($v) => ! empty($v));
                    // Check if there are specific contact list IDs to associate with the contact
                    if (! empty($contactListIds)) {
                        foreach ($contactListIds as $list_id) {
                            if (! empty($list_id)) {  // Ensure list_id is not empty
                                ContactRelationList::firstOrCreate([
                                    'contact_id'      => $contact->id,
                                    'contact_list_id' => $list_id,
                                ]);
                            }
                        }
                    } else {
                        // If no list IDs are provided, ensure the "Uncategorized" list exists for the client
                        $contactList = ContactsList::firstOrCreate([
                            'client_id' => auth()->user()->client->id,
                            'name'      => 'Uncategorized',
                        ]);
                        // Associate the contact with the "Uncategorized" list using firstOrCreate
                        ContactRelationList::firstOrCreate([
                            'contact_id'      => $contact->id,
                            'contact_list_id' => $contactList->id,
                        ]);
                    }
                    $segmentIds     = array_filter(explode(';', $row[3] ?? ''), fn ($v) => ! empty($v));
                    if (! empty($segmentIds)) {
                        foreach ($segmentIds as $segmentId) {
                            // Use firstOrCreate to find or create the relation
                            if (! empty($segmentId)) {
                                ContactRelationSegments::firstOrCreate([
                                    'contact_id' => $contact->id,
                                    'segment_id' => $segmentId,
                                ]);
                            }
                        }
                    } else {
                        // If no segments are provided, ensure the Default segment exists
                        $defaultSegment = Segment::firstOrCreate([
                            'client_id' => auth()->user()->client->id,
                            'title'     => 'Default',
                        ], [
                            'client_id' => auth()->user()->client->id,
                            'title'     => 'Default',
                        ]);
                        // Now, create the relation with the Default segment
                        ContactRelationSegments::firstOrCreate([
                            'contact_id' => $contact->id,
                            'segment_id' => $defaultSegment->id,
                        ]);
                    }
                }
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('created_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('Upload Contact', [$e->getMessage()]);
            return response()->json([
                'error'   => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


}
