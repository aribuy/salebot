<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Segment;
use App\Models\BotGroup;
use App\Models\ContactsList;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Resources\ContactResource;
use App\Models\ContactRelationSegments;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Client\ContactsDataTable;
use App\Http\Requests\Client\ContactsRequest;
use App\Repositories\Client\ContactRepository;
use App\Repositories\Client\SegmentRepository;
use App\Repositories\Client\TemplateRepository;
use App\Http\Requests\Client\ContactUpdateRequest;
use App\Repositories\Client\ContactListRepository;
use App\DataTables\Client\TelegramSubscriberDataTable;

class ContactController extends Controller
{
    use RepoResponse;
    protected $segmentsRepo;
    protected $repo;
    protected $contactsListRepo;
    protected $country;
    protected $template;

    public function __construct(
        ContactRepository $repo,
        ContactListRepository $contactsListRepo,
        SegmentRepository $segmentsRepo,
        CountryRepository $country,
        TemplateRepository $template
    ) {
        $this->contactsListRepo = $contactsListRepo;
        $this->segmentsRepo     = $segmentsRepo;
        $this->repo             = $repo;
        $this->country          = $country;
        $this->template          = $template;
    }

    public function index(ContactsDataTable $contactsDataTable)
    {
        $countries = $this->country->all();
        $segments  = $this->segmentsRepo->activeSegments();
        $lists     = $this->contactsListRepo->activeList();
        $data      = [
            'segments'  => $segments,
            'lists'     => $lists,
            'countries' => $countries,
            'templates' => $this->template->all(),
        ];
        return $contactsDataTable->render('backend.client.whatsapp.contacts.index', $data);
    }


    public function turkeyAndIndian()
    {
        $contacts = Contact::whereIn('country_id', ['225', '101'])->get();

        foreach ($contacts as $contact) {
            $segmentId = ($contact->country_id == '225') ? 5 : 3;
            $dd =  ContactRelationSegments::where('contact_id', $contact->id)
                ->where('segment_id', $segmentId)
                ->delete();
            // Insert the new record
            ContactRelationSegments::create([
                'contact_id' => $contact->id,
                'segment_id' => $segmentId,
            ]);
        }
    }

    public function countrySetup()
    {
        $contacts = Contact::whereNull('country_id')->get();
        foreach ($contacts as $contact) {
            $country_id = $this->getCountryByPhoneNumber($contact->phone);
            DB::table('contacts')->where('id', $contact->id)->update([
                'country_id' => $country_id
            ]);
        }
    }

    private function getCountryByPhoneNumber($phone)
    {
        if (strpos($phone, '+') !== 0) {
            $phone = '+' . $phone;
        }
        $prefixes = Country::pluck('id', 'phonecode');
        if (preg_match('/^\+(\d{1})/', $phone, $matches)) {
            $prefix = $matches[1];
            if (isset($prefixes[$prefix])) {
                return $prefixes[$prefix];
            } else if (preg_match('/^\+(\d{2})/', $phone, $matches)) {
                $prefix = $matches[1];
                if (isset($prefixes[$prefix])) {
                    return $prefixes[$prefix];
                } else if (preg_match('/^\+(\d{3})/', $phone, $matches)) {
                    $prefix = $matches[1];
                    if (isset($prefixes[$prefix])) {
                        return $prefixes[$prefix];
                    }
                }
            }
        }

        return null;
    }

    public function getTelegramContact(TelegramSubscriberDataTable $contactsDataTable)
    {
        $groups = BotGroup::active()->withPermission()->pluck('name', 'id');
        $data   = [
            'groups' => $groups,
        ];
        return $contactsDataTable->render('backend.client.telegram.contacts.index', $data);
    }

    public function create()
    {
        try {
            $segments = $this->segmentsRepo->activeSegments();
            $list     = $this->contactsListRepo->activeList();
            $data     = [
                'segments'  => $segments,
                'lists'     => $list,
                'countries' => $this->country->combo(),
            ];
            return view('backend.client.whatsapp.contacts.create', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }

    public function store(ContactsRequest $request)
    {
        if (isDemoMode()) {
            Toastr::info(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        $result = $this->repo->store($request);
        if ($request->ajax()) {
            return $result;
        }
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }
        return back()->with($result->redirect_class, $result->message);
    }

    public function edit($id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        try {
            $list     = $this->contactsListRepo->activeList();
            $segments = $this->segmentsRepo->activeSegments();
            $contacts = $this->repo->find($id);
            $data     = [
                'segments'  => $segments,
                'contact'   => $contacts,
                'lists'     => $list,
                'countries' => $this->country->combo(),
            ];
            return view('backend.client.whatsapp.contacts.edit', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');
            return back();
        }
    }

    public function view($id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        return $this->repo->view($id);
    }

    public function update(ContactUpdateRequest $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }

        $result = $this->repo->update($request, $id);
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }

        return back()->with($result->redirect_class, $result->message);
    }

    public function updateDetails(Request $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        return $this->repo->updateDetails($request, $id);
    }

    public function segments(): JsonResponse
    {
        try {
            $segments = $this->segmentsRepo->activeSegments();
            foreach ($segments as $item) {
                $options[] = [
                    'text' => $item->lang_title ?? $item->title,
                    'id'   => $item->id,
                ];
            }

            return response()->json($options);
        } catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function contactByClient(Request $request): JsonResponse
    {
        $contacts = $this->repo->activeContacts([
            'client_id'   => auth()->user()->client_id,
            'type'        => $request->type,
            'assignee_id' => $request->assignee_id,
            'q'           => $request->q,
            'tag_id'      => $request->tag_id,
        ]);

        try {
            $data = [
                'contacts'      => ContactResource::collection($contacts),
                'success'       => true,
                'next_page_url' => (bool) $contacts->nextPageUrl(),
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function addBlacklist(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->blacklist($request);
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function removeBlacklist(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->removeBlacklist($request);
            $data = [
                'status'  => 200,
                'message' => __('remove_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function addList(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->addContactList($request);
            $data = [
                'status'  => 200,
                'message' => __('add_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function addSegment(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->addSegment($request);
            $data = [
                'status'  => 200,
                'message' => __('add_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function removeList(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->removeContactList($request);
            $data = [
                'status'  => 200,
                'message' => __('remove_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }
    public function delete($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];
            return response()->json($data);
        }
        return $this->repo->destroy($id);
    }

    public function removeSegment(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->removeSegment($request);
            $data = [
                'status'  => 200,
                'message' => __('remove_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function statusChange(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $total_contacts = Contact::where('client_id', auth()->user()->client_id)->where('status', 1)->count();
            $contacts_limit = auth()->user()->activeSubscription->contact_limit;

            if ($total_contacts >= $contacts_limit) {
                $data = [
                    'status'  => 'danger',
                    'message' => __('insufficient_contacts_limit'),
                    'title'   => 'error',
                ];

                return response()->json($data);
            }
            $this->repo->statusChange($request->all());
            $data           = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function block($id): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $response = $this->repo->addBlock($id);

            $data     = [
                'status'  => 'success',
                'message' => __($response['message']),
                'title'   => __('success'),
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => $e->getMessage(),
                'title'   => __('error'),
            ];

            return response()->json($data);
        }
    }

    public function unblock($id): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $response = $this->repo->removeBlock($id);

            $data     = [
                'status'  => 'success',
                'message' => __($response['message']),
                'title'   => __('success'),
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => $e->getMessage(),
                'title'   => __('error'),
            ];

            return response()->json($data);
        }
    }

    public function createImport()
    {
        $segments = Segment::select('id', 'title as name')->withPermission()->get();
        $list     = ContactsList::select('id', 'name')->withPermission()->get();
        $data     = [
            'segments' => $segments,
            'lists'    => $list,
        ];

        return view('backend.client.whatsapp.import.create', $data);
    }

    public function parseCSV(Request $request)
    {
        $rules     = [
            'file' => 'required|file|mimes:xlsx|max:10240',
        ];
        $messages  = [
            'file.required' => 'Please upload a file.',
            'file.mimes'    => 'The uploaded file must be an Excelfile.',
            'file.max'      => 'The file size must be 10 MB or less.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->formatResponse(false, $validator->errors(), 'client.contacts.index', []);
        }

        return $this->repo->parseCSV($request);
    }

    public function confirmUpload(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->repo->confirmUpload($request);
    }

    // 


}
