<?php

namespace App\Http\Controllers\Api\Client\Whatsapp;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Whatsapp\BotRepliesResource;
use App\Models\BotReply;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Client\BotReplyRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class BotRepliesController extends Controller
{
    use ApiReturnFormatTrait;

    protected $botReplyRepo;


    public function __construct(BotReplyRepository $botReplyRepo)
    {

        $this->botReplyRepo     = $botReplyRepo;

    }
    public function allBotReplies(): \Illuminate\Http\JsonResponse
    {
        try {
            $user      = jwtUser();
            $client_id = $user->client_id;
            $bot_reply   = BotReply::where('client_id', $client_id)->withPermission()->latest()->paginate(10);

            $data = [
                'bot_rplies'        => BotRepliesResource::collection($bot_reply),
                'paginate' => [
                    'total'         => $bot_reply->total(),
                    'current_page'  => $bot_reply->currentPage(),
                    'per_page'      => $bot_reply->perPage(),
                    'last_page'     => $bot_reply->lastPage(),
                    'prev_page_url' => $bot_reply->previousPageUrl(),
                    'next_page_url' => $bot_reply->nextPageUrl(),
                    'path'          => $bot_reply->path(),
                ],
            ];

            return $this->responseWithSuccess('segment_retrieved_successfully', $data);
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }




    public function submitBotReplies(Request $request, $id = null): \Illuminate\Http\JsonResponse
    {
        $baseRules = [
            'name'       => 'required',
            'reply_type' => 'required',
        ];


        if ($request->input('reply_type') == 'canned_response') {
            $baseRules['reply_text'] = 'required';
        }
        if (($request->input('reply_type') == 'exact_match' || $request->input('reply_type') == 'contains') && $request->input('reply_using_ai') == 0) {
            $baseRules['keywords']   = 'required';
            $baseRules['reply_text'] = 'required';
        }


        $validator = Validator::make($request->all(), $baseRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user                 = jwtUser();
            $request['client_id'] = $user->client_id;


            if ($id) {
                $bot_reply = BotReply::findOrFail($id);
                if (!$bot_reply) {
                    return $this->responseWithError('Contact not found.');
                }
                $this->botReplyRepo->update($request, $id);
            } else {
                $this->botReplyRepo->store($request);
            }

            return $this->responseWithSuccess('Submitted successfully');
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }

}
