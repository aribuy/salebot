<?php

namespace App\Repositories;

use App\Models\Department;
use App\Models\OneSignalToken;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Traits\ImageTrait;
use App\Traits\SendMailTrait;
use App\Traits\SendNotification;

class TicketRepository
{
    use ImageTrait, SendMailTrait, SendNotification;

    protected $emailTemplate;

    public function __construct(EmailTemplateRepository $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    public function all($data = [], $with = [])
    {
        if (! arrayCheck('paginate', $data)) {
            $data['paginate'] = setting('pagination');
        }

        return Ticket::with($with)->when(arrayCheck('user_id', $data), function ($query) use ($data) {
            $query->where('user_id', $data['user_id']);
        })->latest()->paginate($data['paginate']);
    }

    public function store($request)
    {
        $response             = [];
        if (arrayCheck('images', $request)) {
            $requestImage = $request['images'];
            $response     = $this->saveImage($requestImage, '_client_');
        }
        if (! arrayCheck('status', $request)) {
            $request['status'] = 'pending';
        }

        if (auth()->user()->user_type == 'client-staff') {
            $request['client']       = auth()->user()->client_id;
            $request['client_staff'] = auth()->user()->id;
            $request['status']       = 'open';
        }

        $request['images']    = $response['images'] ?? null;
        $request['ticket_id'] = rand(1000, 50000);

        $user                 = User::where('id', $request['client_staff'])->first();
        $dept                 = Department::where('id', $request['department_id'])->first();

        if (auth()->user()->user_type == 'client-staff') {
            $userEmail = auth()->user()->email;
        } else {
            $userEmail = $user->first()->email;
        }

        $data                 = [
            'ticket' => $request,
            'dept'   => $dept,
        ];
        if(isMailSetupValid()){
            $this->sendmail($userEmail, 'emails.auth.ticket-email', $data);
        }
        $msg                  = __('new_ticket_opened', ['ticket_id' => $request['ticket_id']]);
        if (auth()->id() == 1) {
            $this->pushNotification([
                'ids'     => OneSignalToken::where('client_id', $request['client'])->pluck('subscription_id')->toArray(),
                'message' => $msg,
                'heading' => $request['ticket_id'],
                'url'     => route('client.tickets.index'),
            ]);
            $this->sendNotification([$request['client']], $msg, 'success', route('client.tickets.index'));
        } else {
            $this->sendAdminNotifications([
                'message' => $msg,
                'heading' => $request['ticket_id'],
                'url'     => route('tickets.index'),
            ]);
        }

        return Ticket::create($request);
    }

    public function find($id, $with = [])
    {
        return Ticket::find($id);
    }

    public function destroy($id)
    {
        return Ticket::destroy($id);
    }

    public function countByStatus($status)
    {
        if (auth()->user()->user_type == 'client-staff') {
            return Ticket::where('status', $status)->where('client_staff', auth()->user()->id)->count();
        } else {
            return Ticket::where('status', $status)->count();
        }
    }

    public function reply($request)
    {
        $response           = [];
        if (arrayCheck('images', $request)) {
            $requestImage = $request['images'];
            $response     = $this->saveImage($requestImage, '_client_');
        }

        $request['images']  = $response['images'] ?? null;
        $request['user_id'] = auth()->id();

        $reply              = TicketReply::create($request);

        $ticket             = $reply->ticket;
        $ticket_no          = $ticket->ticket_id;
        $client             = @$ticket->clientUser;
        $user               = @$client->user;
        $msg                = __('new_reply_on_ticket', ['ticket_id' => $ticket_no]);

        if (auth()->id() == 1) {
            $this->pushNotification([
                'ids'     => OneSignalToken::where('client_id', $client->id)->pluck('subscription_id')->toArray(),
                'message' => $msg,
                'heading' => $ticket_no,
                'url'     => route('client.tickets.show', $reply->ticket_id),
            ]);
            $this->sendNotification([@$user->id], $msg, 'success', route('client.tickets.show', $reply->ticket_id));
        } else {
            $this->sendAdminNotifications([
                'message' => $msg,
                'heading' => $ticket_no,
                'url'     => route('tickets.show', $reply->ticket_id),
            ]);
        }

        return $reply;
    }

    public function replyUpdate($request, $id)
    {
        $reply             = TicketReply::find($id);
        $response          = [];
        if (arrayCheck('images', $request)) {
            $requestImage = $request['images'];
            $response     = $this->saveImage($requestImage, '_client_');
        }

        $request['images'] = $response['images'] ?? null;
        $reply->update($request);

        return $reply;
    }

    public function replyFind($id)
    {
        return TicketReply::find($id);
    }

    public function replyDelete($id): int
    {
        return TicketReply::destroy($id);
    }
}
