<?php

namespace App\Traits;

use App\Events\PusherNotification;
use App\Models\Notification;
use App\Models\User;

trait SendNotification
{
    public function sendNotification($users = [], $message = null, $message_type = 'success', $url = null, $details = null): bool
    {
        foreach ($users as $user) {
            $notification              = new Notification();
            $notification->user_id     = $user;
            $notification->title       = $message;
            $notification->description = $details;
            $notification->url         = $url;
            $notification->created_by  = auth()->id();
            $notification->save();
        }

        try {
            if (setting('is_pusher_notification_active')) {
                foreach ($users as $user) {
                    event(new PusherNotification($user, $message, $message_type, $url, $details));
                }
            }
        } catch (\Exception $e) {
            // dd($e);
        }

        return true;
    }

    public function pushNotification($data)
    {
        $headers = [
            'Authorization' => 'Basic '.setting('onesignal_rest_api_key'),
            'accept'        => 'application/json',
            'content-type'  => 'application/json',
        ];

        $body    = [
            'include_player_ids' => $data['ids'],
            'contents'           => [
                'en' => $data['message'],
            ],
            'headings'           => [
                'en' => $data['heading'],
            ],
            'app_id'             => setting('onesignal_app_id'),
            'url'                => $data['url'],
        ];

        return httpRequest('https://onesignal.com/api/v1/notifications', $body, $headers);
    }

    public function sendAdminNotifications($data)
    {
        $admin   = User::find(1);
        $message = $data['message'];
        try {
            $this->sendNotification([$admin->id], $message);
        } catch (\Exception $e) {
        }

        try {
            $this->pushNotification([
                'ids'     => $admin->onesignal_player_id,
                'message' => $message,
                'heading' => $data['heading'],
                'url'     => $data['url'],
            ]);
        } catch (\Exception $e) {
        }
    }
}
