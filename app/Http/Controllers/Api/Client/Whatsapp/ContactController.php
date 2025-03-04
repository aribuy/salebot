<?php

namespace App\Http\Controllers\Api\Client\Whatsapp;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Whatsapp\ContactResource;
use App\Models\Contact;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Client\ContactRepository;
use Exception;

class ContactController extends Controller
{
    use ApiReturnFormatTrait;

    protected $contactsRepo;


    public function __construct( ContactRepository $contactsRepo)
    {

        $this->contactsRepo     = $contactsRepo;

    }
    public function allContact(): \Illuminate\Http\JsonResponse
    {
        try {
            $user     = jwtUser();
            $contacts = Contact::where('client_id', $user->client_id)
                                ->where('status', 1)
                                ->with('list','country', 'created_by')
                                ->latest()
                                ->paginate(10);

            $data = [
                'contacts'              => ContactResource::collection($contacts),
                'paginate' => [
                    'total'             => $contacts->total(),
                    'current_page'      => $contacts->currentPage(),
                    'per_page'          => $contacts->perPage(),
                    'last_page'         => $contacts->lastPage(),
                    'prev_page_url'     => $contacts->previousPageUrl(),
                    'next_page_url'     => $contacts->nextPageUrl(),
                    'path'              => $contacts->path(),
                ],
            ];

            return $this->responseWithSuccess('contact_retrieved_successfully', $data);
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }

    public function submitContact(Request $request, $id = null): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone'      => 'required|numeric|min:11|unique:contacts,phone,' . $id,
            'country_id' => 'required|exists:countries,id',
        ]);


        if ($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $user = jwtUser();
            if ($id) {
                $contact = Contact::findOrFail($id);
                if (!$contact) {
                    return $this->responseWithError('Contact not found.');
                }
                $this->contactsRepo->update($request, $id);
            } else {
                $this->contactsRepo->store($request);
            }

            return $this->responseWithSuccess('Submitted successfully');
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }
}
