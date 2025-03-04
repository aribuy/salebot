<?php

namespace App\Http\Controllers\Api\Client\Whatsapp;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Whatsapp\TemplateResource;
use App\Models\Template;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Client\ContactListRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateController extends Controller
{
    use ApiReturnFormatTrait;

    public function allTemplate(): \Illuminate\Http\JsonResponse
    {
        try {
            $user      = jwtUser();
            $client_id = $user->client_id;
            $template  = Template::where('client_id', $client_id)->latest()->paginate(10);

            $data = [
                'template'              => TemplateResource::collection($template),
                'paginate' => [
                    'total'             => $template->total(),
                    'current_page'      => $template->currentPage(),
                    'per_page'          => $template->perPage(),
                    'last_page'         => $template->lastPage(),
                    'prev_page_url'     => $template->previousPageUrl(),
                    'next_page_url'     => $template->nextPageUrl(),
                    'path'              => $template->path(),
                ],
            ];

            return $this->responseWithSuccess('template_retrieved_successfully', $data);
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }


}
