<?php

namespace App\Http\Controllers\Api\Client\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Telegram\GroupResource;
use App\Models\Group;
use App\Models\BotGroup;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GroupController extends Controller
{
    use ApiReturnFormatTrait;

    public function allGroup(): \Illuminate\Http\JsonResponse
    {
        try {
            $user     = jwtUser();
            $group    = BotGroup::where('client_id', $user->client_id)
                                ->where('status', 1)
                                ->withPermission()
                                ->latest()
                                ->paginate(10);

            $data = [
                'group'                 => GroupResource::collection($group),
                'paginate' => [
                    'total'             => $group->total(),
                    'current_page'      => $group->currentPage(),
                    'per_page'          => $group->perPage(),
                    'last_page'         => $group->lastPage(),
                    'prev_page_url'     => $group->previousPageUrl(),
                    'next_page_url'     => $group->nextPageUrl(),
                    'path'              => $group->path(),
                ],
            ];

            return $this->responseWithSuccess('group_retrieved_successfully', $data);
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }

}
