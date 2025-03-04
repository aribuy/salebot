<?php
namespace App\Repositories\Client;
use App\Models\ClientStaff;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TeamRepository
{
    use ImageTrait;

    public function getClientStaff($client_id): LengthAwarePaginator
    {
        return ClientStaff::with('user')->where('client_id', $client_id)->paginate(setting('paginate'));
    }
    public function all($with = [])
    {
        return ClientStaff::with($with)->whereHas('user', function ($query) {
            $query->where('role_id', 2)->active()->notDeleted()->notBanned();
        })->paginate(setting('paginate'));
    }

    public function clientStaffs($client_id, $auth_id)
    {
        return User::where('client_id', $client_id)->where('id', '!=', $auth_id)->get();
    }

    public function get($id)
    {
        return ClientStaff::findOrfail($id)->with('user');
    }

    public function store($request)
    {
        $request['role_id']           = 3;

        if (isset($request['image'])) {
            $requestImage      = $request['image'];
            $response          = $this->saveImage($requestImage, '_team_');
            $request['images'] = $response['images'];
        }
        if (arrayCheck('password', $request)) {
            $request['password'] = bcrypt($request['password']);
        }
        $request['user_type']         = 'client-staff';
        $request['client_id']         = Auth::user()->client_id;
        $request['email_verified_at'] = now();
        $user                         = User::create($request);

        $request['user_id']           = $user->id;
        $request['client_id']         = Auth::user()->client_id;
        $request['slug']              = getSlug('clients', $user->name);

        return ClientStaff::create($request);
    }

    public function update($request, $id)
    {

        $user            = User::findOrFail($id);
        $staff           = $user->clientStaff;

        if (isset($request['image'])) {
            $requestImage      = $request['image'];
            $response          = $this->saveImage($requestImage, '_team_');
            $request['images'] = $response['images'];
        }
        if (arrayCheck('password', $request)) {
            $request['password'] = bcrypt($request['password']);
        }

        $user->update($request);
        $user            = User::find($id);
        $request['slug'] = getSlug('clients', $user->name, 'slug', $staff->id);

        return $staff->update($request);
    }
    public function statusChange($request)
    {
        $id = $request['id'];

        return User::find($id)->update($request);
    }
}
