<?php

namespace App\Http\Controllers\Api\Client\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Telegram\ContactResource;
use App\Models\GroupSubscriber;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Client\ContactRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
            $contacts = GroupSubscriber::where('client_id', $user->client_id)
                                ->where('status', 1)
                                ->with('group',)
                                ->withPermission()
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

}
