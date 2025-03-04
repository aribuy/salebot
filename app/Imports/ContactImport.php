<?php
namespace App\Imports;
use App\Models\Contact;
use App\Models\Segment;
use App\Models\ContactsList;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\ContactRelationList;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactRelationSegments;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ContactImport implements SkipsEmptyRows, SkipsOnError, ToCollection, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable, SkipsErrors;

    protected $request;

    public function __construct(Request $request,
    ) {
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {


            $contactData               = [
                'phone'     => $row['phone'],
                'client_id' => auth()->user()->client->id,
            ];
            if (isset($row['name'])) {
                $contactData['name'] = $row['name'];
            }
            $whatsappService           = new WhatsAppService();
            $contactData['country_id'] = $whatsappService->extractCountryCode($row['phone']);
            $contact                   = Contact::where('phone', $row['phone'])->orWhere('phone', '+'.$row['phone'])->first();
            if (empty($contact)) {
                $contact             = new Contact();
                $contact->name       = $row['name'];
                $contact->phone      = str_replace(' ', '', $row['phone']);
                $contact->country_id = $whatsappService->extractCountryCode($row['phone']);
                $contact->client_id  = Auth::user()->client_id;
                $contact->save();
                // $contact = Contact::create($contactData);
            }
            $contactListIds            = $this->request->input('contact_list_id');
            if (! is_null($contactListIds)) {
                foreach ($contactListIds as $list_id) {
                    $list = ContactRelationList::where('contact_id', $contact->id)->where('contact_list_id', $list_id)->first();
                    if (empty($list)) {
                        $contactRelationList                  = new ContactRelationList();
                        $contactRelationList->contact_id      = $contact->id;
                        $contactRelationList->contact_list_id = $list_id;
                        $contactRelationList->save();
                    }
                }
            } else {
                $contactList = ContactsList::where('client_id', auth()->user()->client->id)->where('name', 'Uncategorized')->first();

                if (is_null($contactList)) {
                    $contactList            = new ContactsList();
                    $contactList->name      = 'Uncategorized';
                    $contactList->client_id = auth()->user()->client->id;
                    $contactList->save();
                }
                DB::table('contact_relation_lists')->insert([
                    'contact_id'      => $contact->id,
                    'contact_list_id' => $contactList->id,
                ]);

            }
            $segmentIds                = $this->request->input('segment_id');
            if (! is_null($segmentIds)) {
                foreach ($segmentIds as $segment) {
                    $list = ContactRelationSegments::where('contact_id', $contact->id)->where('segment_id', $segment)->first();
                    if (empty($list)) {
                        $contactRelationSegment             = new ContactRelationSegments();
                        $contactRelationSegment->contact_id = $contact->id;
                        $contactRelationSegment->segment_id = $segment;
                        $contactRelationSegment->save();
                    }
                }
            } else {
                //Default Segment
                $segment = Segment::where('client_id', auth()->user()->client->id)->where('title', 'Default')->first();
                if (is_null($segment)) {
                    $segment            = new Segment();
                    $segment->title     = 'Default';
                    $segment->client_id = auth()->user()->client->id;
                    $segment->save();
                }
                DB::table('contact_relation_segments')->insert([
                    'contact_id' => $contact->id,
                    'segment_id' => $segment->id,
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.phone' => 'required',
        ];
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
