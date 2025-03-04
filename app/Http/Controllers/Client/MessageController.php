<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use App\Models\ContactTag;
use App\Helpers\TextHelper;
use App\Models\ContactNote;
use App\Traits\CommonTrait;
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use App\Traits\SendMailTrait;
use App\Enums\MessageStatusEnum;
use App\Traits\SendNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TagResource;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\StaffResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Resources\ChatroomResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SharedFileResource;
use App\Http\Resources\SharedMediaResource;
use App\Repositories\Client\TeamRepository;
use App\Repositories\Client\ContactRepository;
use App\Repositories\Client\MessageRepository;
use Illuminate\Contracts\Foundation\Application;

class MessageController extends Controller
{
    use CommonTrait, RepoResponse, SendNotification, SendMailTrait;

    protected $repo;

    protected $contactRepository;

    protected $contact;

    protected $message;

    protected $messageModel;

    public function __construct(
        MessageRepository $repo,
        ContactRepository $contactRepository,
        ContactRepository $contact,
        Message $messageModel,
    ) {
        $this->repo = $repo;
        $this->contactRepository = $contactRepository;
        $this->contact           = $contact;
        $this->messageModel           = $messageModel;
    }

    public function index(Request $request): View|Factory|RedirectResponse|Application
    { 
        try {
            $contact = $this->contact->find($request->contact);

            $data    = [
                'contact' => $contact ? [
                    'id'                   => $contact->id,
                    'receiver_id'          => $contact->id,
                    'name'                 => $contact->name,
                    'phone'                => $contact->phone,
                    'image'                => $contact->profile_pic,
                    'last_conversation_at' => $contact->last_conversation_at,
                    'assignee_id'          => nullCheck($contact->assignee_id),
                ] : false,
            ];

            return view('backend.client.chat.index', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function chatRooms(Request $request): JsonResponse
    {
        $rooms = $this->contactRepository->getChatContactList([
            'type'        => $request->type,
            'assignee_id' => $request->assignee_id,
            'q'           => $request->q,
            'tag_id'      => $request->tag_id,
        ]);
        try {
            $data = [
                'chat_rooms'    => ChatroomResource::collection($rooms),
                'next_page_url' => (bool) $rooms->nextPageUrl(),
                'success'       => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function chatroomMessages($id): JsonResponse
    {
        try {

            $contact          = $this->contactRepository->find($id);
            // \Log::error('contact',[$contact]);         
            $messages         = $this->repo->chatRoomMessages($id, null, $contact->source);

            $grouped_messages = $messages->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('d/m/Y');
            });
            Message::where('contact_id', $contact->id)->where('status', MessageStatusEnum::DELIVERED)->where('is_contact_msg', 1)->update([
                'status' => MessageStatusEnum::READ,
            ]);

            $data             = [
                'messages'      => $this->parseMessages($grouped_messages),
                'user'          => [
                    'id'                   => $contact->id,
                    'receiver_id'          => $contact->id,
                    'name'                 => $contact->name,
                    'phone'                => isDemoMode() ? '+*************' : @$contact->phone,
                    'image'                => $contact->profile_pic,
                    'group_chat_id'        => $contact->group_chat_id,
                    'source'               => $contact->type,
                    'last_conversation_at' => $contact->last_conversation_at,
                    'assignee_id'          => nullCheck($contact->assignee_id),
                ],
                'success'       => true,
                'next_page_url' => (bool) $messages->nextPageUrl(),
                'can_not_reply' => Carbon::now()->diffInHours($contact->last_conversation_at) > 24,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function parseMessages($messages): array
    {
        $data = [];
        $i    = 0;
        foreach ($messages as $key => $message) {
            if ($key == Carbon::now()->format('d/m/Y')) {
                $key = 'Today';
            } elseif ($key == Carbon::now()->subDay()->format('d/m/Y')) {
                $key = 'Yesterday';
            }
            $data[$i]['date'] = $key;
            foreach ($message->reverse() as $message_item) {
                // dd($message_item);

                $receiver_image         = null;
                $contact_name           = null;
                $contacts               = $message_item->contacts ? json_decode($message_item->contacts) : [];
                if ($message_item->contact->type == 'telegram') {
                    $receiver_image = isset($message_item->group_subscriber) && isset($message_item->group_subscriber->avatar) ? $message_item->group_subscriber->avatar : static_asset('images/default/user.jpg');
                    $contact_name   = isset($message_item->group_subscriber) && isset($message_item->group_subscriber->name) ? $message_item->group_subscriber->name : null;
                } else {
                    $receiver_image = isset($message_item->contact->profile_pic) && !empty($message_item->contact->profile_pic) ? $message_item->contact->profile_pic : static_asset('images/default/user.jpg');
                    $contact_name   = isset($message_item->contact->name) ? $message_item->contact->name : null;
                }
                // dd($message_item->group_subscriber);
                $buttons = $this->renderButtons($message_item->buttons);
                $class                  = 'single-sp-chat-area mt--8 ';
                if ($message_item->is_campaign_msg) {
                    $class .= 'single-sp-card-box';
                } elseif ($message_item->header_image) {
                    $class .= 'single-sp-img-box';
                } elseif ($message_item->header_video) {
                    $class .= 'single-sp-card-box plyr';
                } elseif ($message_item->header_audio) {
                    $class .= 'single-sp-audio-box plyr';
                } elseif ($message_item->header_document) {
                    $class .= 'single-sp-card-box';
                } elseif ($message_item->contacts) {
                    $class .= 'single-sp-card-box d-flex mt--23';
                } else {
                    $class .= 'single-sp-text-area';
                }
                $context = [];
                if ($message_item->context_id) {
                    $contextMessage = $this->messageModel->where('message_id', $message_item->context_id)->first();
                    if($contextMessage){  
                        $contextType = $contextMessage->message_type;
                        $contextValue = match($contextType) {
                            'text' => Str::limit($contextMessage->value,100),
                            'image' => $contextMessage->header_image,
                            'location' => $contextMessage->header_location,
                            'audio' => $contextMessage->header_audio,
                            'video' => $contextMessage->header_video,
                            'document' => $contextMessage->header_document,
                            'contacts' => $contextMessage->contacts,
                            'reply_button', 'interactive_button' => $contextMessage->header_text ? $contextMessage->header_text : $contextMessage->value,
                            'interactive', 'button' => $contextMessage->buttons ? json_decode($contextMessage->buttons, true)[0]['text'] : $contextMessage->value,
                            'condition' => $contextMessage->component_header,
                            'interactive_list', 'template', 'carousel' => $contextMessage->components,
                            default => null,
                        };
                        $context = [
                            'id' => $contextMessage->id,
                            'type' => $contextType,
                            'message' => $contextValue,
                        ];
                    }
                }
                $class .= $message_item->is_contact_msg ? '' :  ' text-end';
                // $class .= $message_item->is_contact_msg ? '' : (($message_item->header_video || $message_item->header_audio) && !$message_item->is_campaign_msg ? '' : ' text-end');
                   // Correctly fetch and set the message type
                $messageType = $message_item->message_type;
                $data[$i]['messages'][] = [
                    'id'              => $message_item->id,
                    'class'           => $class,
                    'context'           => $context,
                    'is_campaign_msg' => (bool) $message_item->is_campaign_msg,
                    'header_video'    => $message_item->header_video,
                    'header_image'    => $message_item->header_image,
                    'header_audio'    => $message_item->header_audio,
                    'message_type'    => $messageType,
                    'header_document' => $message_item->header_document,
                    'header_location' => $message_item->header_location,
                    'file_info'       => $message_item->file_info ? : [],
                    'header_text'     => $message_item->header_text,
                    'value'           => TextHelper::transformText($message_item->value),
                    'footer_text'     => $message_item->footer_text,
                    'contacts'        => $contacts,
                    'error'           => $message_item->error,
                    'is_seen'         => $message_item->status == MessageStatusEnum::READ,
                    'is_sent'         => $message_item->status == MessageStatusEnum::SENT,
                    'is_delivered'    => $message_item->status == MessageStatusEnum::DELIVERED,
                    'user_image'      => @$message_item->client->profile_pic,
                    'receiver_image'  => $receiver_image,
                    'contact_name'    => $contact_name,
                    'source'          => $message_item->contact->type,
                    'sent_at'         => Carbon::parse($message_item->created_at)->format('H:i A'),
                    'is_contact_msg'  => (bool) $message_item->is_contact_msg,
                    'buttons'         => $buttons,
                ];
            }
            $i++;
        }
        return $data;
    }


    private function renderButtons($buttonsJson): array
    {
        $renderedButtons = [];
        $buttons = json_decode($buttonsJson, true);
        if (!is_array($buttons)) {
            return $renderedButtons;
        }
        foreach ($buttons as $button) {
            if (!empty($button['parameters'])) {
                $params = $button['parameters'][0];
                $type = $params['type'] ?? null;

                $renderedButtons[] = [
                    'type' => $type == 'URL' ? 'a' : 'button',
                    'text' => getArrayValue($type, $params),
                    'value' => getArrayValue($type, $button),
                ];
            } else {
                $type = $button['type'] ?? 'URL';

                $renderedButtons[] = [
                    'type' => $type == 'URL' ? 'a' : 'button',
                    'text' => getArrayValue('text', $button),
                    'value' => getArrayValue('url', $button),
                ];
            }
        }

        return $renderedButtons;
    }



    public function sendMessage(Request $request): JsonResponse
    {
        $validator    = Validator::make($request->all(), [
            'message'     => 'required_without_all:image,document',
            'receiver_id' => 'required',
            'image'       => 'required_without_all:message,document',
            'document'    => 'required_without_all:message,image',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        $conversation_remaining = auth()->user()->activeSubscription->conversation_remaining;
        if ($conversation_remaining <= 0) {
            return response()->json([
                'error' => __('insufficient_conversation_limit'),
            ]);
        }
        try {
            $conversation_id        = $this->conversationUpdate(auth()->user()->client_id, $request->receiver_id);
            $contact                = $this->contactRepository->find($request->receiver_id);
            if ($request->file('document') !== null) {
                $this->repo->sendDocumentMessage($request, $request->receiver_id, $contact->type, $conversation_id);
            } elseif ($request->file('image') !== null) {
                $this->repo->sendImageMessage($request, $request->receiver_id, $contact->type, $conversation_id);
            } elseif (!empty($request->message)) {
                $this->repo->sendTextMessage($request, $request->receiver_id, $contact->type, $conversation_id);
            } else {
                return response()->json([
                    'error' => __('oops...Something Went Wrong'),
                ]);
            }
            $conversation_remaining = $conversation_remaining - 1;
            Subscription::where('client_id', auth()->user()->client_id)->where('status', 1)->update(['conversation_remaining' => $conversation_remaining]);
            DB::commit();
            return response()->json([
                'success' => __('message_sent_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function sendForwardMessage(Request $request)
    {
        $validator              = Validator::make($request->all(), [
            'messageIds' => 'required',
            'contactId' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        return $this->repo->sendForwardMessage($request);
    }

    public function staffsByClient(): JsonResponse
    {
        $repo   = new TeamRepository();
        $staffs = $repo->clientStaffs(auth()->user()->client_id, auth()->id());

        try {
            $data = [
                'staffs'  => StaffResource::collection($staffs),
                'success' => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function assignStaff(Request $request): JsonResponse
    {
        try {

            $contact              = $this->contactRepository->find($request->contact_id);
            $contact->assignee_id = $request->staff_id;
            $contact->save();
            $user = User::find($request->staff_id);

            $msg = __('conatct_assign_for_chat', ['assignedByName' => Auth::user()->first_name . ' ' . Auth::user()->last_name]);

            $this->pushNotification([
                'ids'     => $user->onesignal_player_id,
                'message' => $msg,
                'heading' => __('chat_contact_assignment'),
                'url'     => route('client.chat.index'),
            ]);

            $data           = [
                'user'      => $user,
                'chat_link' => route('client.chat.index', ['contact' => $contact->id]),
                'subject'   => __('chat_contact_assignment'),
                'body'      => $msg,
                'email_templates'   => $msg,

            ];

            if (isMailSetupValid()) {
                // Mail::to($user->email)->send(new SendSmtpMail($attribute));
                $this->sendmail($user->email, 'emails.conatct_assign', $data);
            }
            return response()->json([
                'success' => __('staff_assigned_successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function contactDetails($id): JsonResponse
    {
        try {
            $contact = $this->contactRepository->find($id);
            $notes   = ContactNote::where('contact_id', $id)->latest()->get();
            $tags    = ContactTag::where('contact_id', $id)->latest()->get();
            $data    = [
                'contact' => [
                    'id'                   => $contact->id,
                    'receiver_id'          => $contact->id,
                    'name'                 => $contact->name,
                    'phone'                => (isDemoMode()) ? '*********' : $contact->phone,
                    'image'                => $contact->profile_pic,
                    'last_conversation_at' => Carbon::parse($contact->last_conversation_at)->format('d/m/Y'),
                    'assignee_id'          => nullCheck($contact->assignee_id),
                    'conversation_id'      => nullCheck(@$contact->lastConversation->unique_id),
                    'source'               => $contact->type,
                    'created_at'           => Carbon::parse($contact->created_at)->format('d/m/Y'),
                ],
                'notes'   => NoteResource::collection($notes),
                'tags'    => TagResource::collection($tags),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function sharedFiles($id, Request $request): JsonResponse
    {
        try {
            $medias = $this->repo->chatRoomMessages($id, $request->type);
            if ($request->type == 'media') {
                $files = SharedMediaResource::collection($medias);
            } elseif ($request->type == 'files') {
                $files = SharedFileResource::collection($medias);
            } else {
                $files = [];
                foreach ($medias as $media) {
                    $pattern       = '/https?:\/\/\S+/';

                    preg_match_all($pattern, $media->value, $matches);

                    $extractedUrls = $matches[0];

                    foreach ($extractedUrls as $url) {
                        $files[] = $url;
                    }
                }
            }
            $data   = [
                'files'         => $files,
                'success'       => true,
                'next_page_url' => $medias->nextPageUrl() ?: false,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function deleteFile($id): JsonResponse
    {
        try {
            $message = $this->repo->find($id);
            if ($message->header_image) {
                File::delete($message->header_image);
                $message->header_image = null;
            } elseif ($message->header_document) {
                File::delete($message->header_document);
                $message->header_document = null;
            }
            $message->save();

            return response()->json([
                'success' => __('file_deleted_successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function clearChat($id)
    {
        return $this->repo->clearChat($id);
    }

    public function deleteMessage($id)
    {
        return $this->repo->deleteMessage($id);
    }

    public function generateAIReply(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|integer',
            'reply_type' => 'required|string',
            'context'    => 'required|array',
        ]);
        $reply = $this->repo->generateAIReply(
            $request->contact_id,
            $request->reply_type,
            $request->context
        );
        return response()->json($reply);
    }

    public function generateAIRewriteReply(Request $request)
    {
        $reply = $this->repo->generateAIRewriteReply(
            $request->contact_id,
            $request->reply_type,
            $request->context
        );
        return response()->json($reply);
    }

    public function getContactMessages($contactId, Request $request)
    {
        $messages = Message::where('contact_id', $contactId)
            ->orderBy('created_at', 'desc')
            ->where('is_contact_msg', 1)
            ->limit($request->limit ?? 1)
            // ->latest()
            // ->limit(3)
            ->pluck('value');
        return response()->json([
            'messages' => $messages
        ]);
    }
}
