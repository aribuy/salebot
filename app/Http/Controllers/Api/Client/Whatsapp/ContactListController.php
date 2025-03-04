<?php

namespace App\Http\Controllers\Api\Client\Whatsapp;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Whatsapp\ContactListResource;
use App\Models\ContactsList;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Client\ContactListRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ContactListController extends Controller
{
    use ApiReturnFormatTrait;

    protected $contactListRepo;


    public function __construct( ContactListRepository $contactListRepo)
    {

        $this->contactListRepo     = $contactListRepo;

    }
    public function allContactList(Request $request)
        {
            try {
                $user = jwtUser();
                $client_id = $user->client_id;

                $contacts = ContactsList::where('client_id', $client_id)
                    ->where('status', 1)
                    ->with('contactList')
                    ->latest()
                    ->paginate(10);

                $data = [];
                foreach ($contacts as $contact) {
                    $client = $user->client;
                    $activeContactsCount = $client->whatsAppContacts()->count();
                    $totalContactsCount = $contact->contactList->count() ?? 0;
                    $total_percent = $activeContactsCount > 0 ? ($totalContactsCount / $activeContactsCount) * 100 : 0;

                    $messageCounts = DB::table('messages')
                        ->where('contacts.client_id', $client->id)
                        ->leftJoin('contacts', 'contacts.id', '=', 'messages.contact_id')
                        ->leftJoin('contact_relation_lists', 'contacts.id', '=', 'contact_relation_lists.contact_id')
                        ->where('contact_relation_lists.contact_list_id', $contact->id)
                        ->where('is_contact_msg', 0)
                        ->groupBy('contact_relation_lists.contact_id')
                        ->selectRaw('COUNT(*) AS total_messages, SUM(CASE WHEN messages.status = "read" THEN 1 ELSE 0 END) AS read_messages')
                        ->first();

                    $total_message = $messageCounts->total_messages ?? 0;
                    $total_message_read = $messageCounts->read_messages ?? 0;
                    $readPercentage = ($total_message > 0) ? ($total_message_read / $total_message) * 100 : 0;

                    $data[] = [
                        'contact' => new ContactListResource($contact),
                        'totalContactsCount' => $totalContactsCount,
                        'total_percent' => $total_percent,
                        'total_message' => $total_message,
                        'total_message_read' => $total_message_read,
                        'readPercentage' => $readPercentage
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Contact list retrieved successfully',
                    'data' => [
                        'contacts' => $data,
                        'paginate' => [
                            'total' => $contacts->total(),
                            'current_page'  => $contacts->currentPage(),
                            'per_page'      => $contacts->perPage(),
                            'last_page'     => $contacts->lastPage(),
                            'prev_page_url' => $contacts->previousPageUrl(),
                            'next_page_url' => $contacts->nextPageUrl(),
                            'path'          => $contacts->path(),
                        ]
                    ]
                ]);
            } catch (\Exception $e) {
                return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
            }
        }



    public function submitContactList(Request $request, $id = null): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user                 = jwtUser();
            $request['client_id'] = $user->client_id;


            if ($id) {
                $contact = ContactsList::findOrFail($id);
                if (!$contact) {
                    return $this->responseWithError('Contact not found.');
                }
                $this->contactListRepo->update($request->all(), $id);
            } else {
                $this->contactListRepo->store($request->all());
            }

            return $this->responseWithSuccess('Submitted successfully');
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }

}
