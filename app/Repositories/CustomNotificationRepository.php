<?php

namespace App\Repositories;

use App\Models\CustomNotification;
use App\Models\OneSignalToken;
use App\Traits\ImageTrait;

class CustomNotificationRepository
{
    use ImageTrait;

    public function all()
    {
        return CustomNotification::orderByDesc('id')->paginate(setting('paginate'));
    }

    public function store($request)
    {
        $response['images']        = '';
        if (isset($request['images'])) {
            $requestImage = $request['images'];
            $response     = $this->saveImage($requestImage, '_notification_');
        }

        $notification              = new CustomNotification();
        $notification->title       = $request['title'];
        $notification->description = $request['description'];
        $notification->action_for  = $request['action_for'];
        $notification->images      = $response['images'];
        $notification->save();

        $one_signal_tokens         = OneSignalToken::pluck('subscription_id')->toArray();

        if (count($one_signal_tokens) > 0) {
            $headers = [
                'Authorization' => 'Basic '.setting('onesignal_rest_api_key'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ];

            $body    = [
                'include_player_ids' => array_unique($one_signal_tokens),
                'contents'           => [
                    'en' => $request['description'],
                ],
                'headings'           => [
                    'en' => $request['title'],
                ],
                'app_id'             => setting('onesignal_app_id'),
                'url'                => $request['action_for'],
            ];

            httpRequest('https://onesignal.com/api/v1/notifications', $body, $headers);
        }

        return $notification;
    }

    public function find($id)
    {
        return CustomNotification::find($id);
    }


    public function destroy($id)
    {
        return CustomNotification::destroy($id);
    }
}
