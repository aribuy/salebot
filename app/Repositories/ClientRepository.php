<?php

namespace App\Repositories;

use App\Models\BotGroup;
use App\Models\BotReply;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\ClientSetting;
use App\Models\ClientStaff;
use App\Models\Contact;
use App\Models\ContactsList;
use App\Models\Conversation;
use App\Models\GroupSubscriber;
use App\Models\Message;
use App\Models\Segment;
use App\Models\StripeSession;
use App\Models\Subscription;
use App\Models\SubscriptionTransactionLog;
use App\Models\Template;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientRepository
{
    use ImageTrait;

    public function all($data, $relation = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if (! arrayCheck('paginate', $data)) {
            $data['paginate'] = setting('paginate');
        }

        return Client::with($relation)->latest()->paginate($data['paginate']);
    }

    public function activeClient()
    {
        return Client::with('user')->latest()->where('status', 1)->get();
    }

    public function bestClient()
    {
        return Client::withCount('subscriptions')->withSum('subscriptions', 'price')->where('status', 1)->orderByDesc('subscriptions_count')->limit(5)->get();
    }

    public function clientStatus($status = null)
    {
        return Client::when($status || $status == '0', function ($query) use ($status) {
            return $query->where('status', $status);
        })->count();
    }

    public function find($id)
    {
        return Client::find($id);
    }

    public function store($request)
    {
        $response                        = [];
        if (arrayCheck('images', $request)) {
            $requestImage = $request['images'];
            $response     = $this->saveImage($requestImage, '_user_');
        }
        $response2                       = [];
        if (arrayCheck('logo', $request)) {
            $requestImage = $request['logo'];
            $response2    = $this->saveImage($requestImage, '_client_');
        }
        $request['slug']                 = getSlug('clients', $request['company_name']);
        $request['webhook_verify_token'] = Str::random(40);
        $request['api_key']              = Str::random(40);
        $request['logo']                 = $response2['images'] ?? null;
        $request['country_id']           = $request['country_id'] ?? null;
        $client                          = Client::create($request);

        //user
        $role                            = DB::table('roles')->where('slug', 'Client-staff')->select('id', 'permissions')->first();
        $permissions                     = json_decode($role->permissions, true);
        $request['permissions']          = $permissions;

        $request['first_name']           = $request['first_name'];
        $request['last_name']            = $request['last_name'];
        $request['role_id']              = $role->id;
        $request['email']                = $request['email'];
        $request['user_type']            = 'client-staff';
        $request['phone']                = $request['phone_number'];
        $request['client_id']            = $client->id;
        $request['is_primary']           = 1;
        $request['email_verified_at']    = now();
        if (arrayCheck('password', $request)) {
            $request['password'] = bcrypt($request['password']);
        }
        $request['images']               = $response['images']  ?? null;
        $user                            = User::create($request);
        //ClientStaff
        $request['user_id']              = $user->id;
        $request['client_id']            = $client->id;
        $request['slug']                 = getSlug('clients', $client->company_name);

        return ClientStaff::create($request);

    }

    public function update($request, $id)
    {

        $response2            = [];
        if (arrayCheck('logo', $request)) {
            $requestImage = $request['logo'];
            $response2    = $this->saveImage($requestImage, '_client_');
        }

        $client               = Client::findOrFail($id);
        $client->company_name = $request['company_name'];
        $client->slug         = getSlug('clients', $request['company_name']);
        $client->timezone     = $request['time_zone'] ?? null;
        $client->country_id   = $request['country_id'] ?? null;
        $client->logo         = $response2['images']  ?? $client->logo;
        $client->save();

        $clientStaff          = ClientStaff::where('client_id', $client->id)->first();
        $clientStaff->slug    = getSlug('clients', $client->company_name);

        return $clientStaff->save();
    }

    public function destroy($id): int
    {
        return Client::destroy($id);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return Client::find($id)->update($request);
    }

    public function delete($id)
    {
        $subscription_transaction_logs = SubscriptionTransactionLog::where('client_id', $id)->delete();
        $tickets                       = Ticket::where('client', $id)->delete();
        $stripe_sessions               = StripeSession::where('client_id', $id)->delete();
        $subscriptions                 = Subscription::where('client_id', $id)->delete();
        $bot_replies                   = BotReply::where('client_id', $id)->delete();
        $conversations                 = Conversation::where('client_id', $id)->delete();
        $templates                     = Template::where('client_id', $id)->delete();
        $messages                      = Message::where('client_id', $id)->delete();
        $campaigns                     = Campaign::where('client_id', $id)->delete();
        $group_subscribers             = GroupSubscriber::where('client_id', $id)->delete();
        $bot_groups                    = BotGroup::where('client_id', $id)->delete();
        $segments                      = Segment::where('client_id', $id)->delete();
        $contacts_lists                = ContactsList::where('client_id', $id)->delete();
        $contacts                      = Contact::where('client_id', $id)->delete();
        $client_staff                  = ClientStaff::where('client_id', $id)->delete();
        $client_settings               = ClientSetting::where('client_id', $id)->delete();
        $user                          = User::where('client_id', $id)->delete();
        $client                        = Client::destroy($id);
    }
}
