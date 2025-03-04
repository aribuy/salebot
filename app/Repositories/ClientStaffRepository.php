<?php

namespace App\Repositories;

use App\Models\ClientStaff;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;

class ClientStaffRepository
{
    use ImageTrait;

    public function getClientStaff($client_id)
    {
        return ClientStaff::where('client_id', $client_id)->with('user')->paginate(setting('paginate'));
    }

    public function all($with = [])
    {
        return ClientStaff::with($with)->whereHas('organization')->whereHas('user', function ($query) {
            $query->where('role_id', 2)->active()->notDeleted()->notBanned();
        })->paginate(setting('paginate'));
    }

    public function get($id)
    {
        return ClientStaff::findOrfail($id);
    }

    public function store($request)
    {
        $request['role_id']   = 3;

        if (isset($request['image'])) {
            $requestImage      = $request['image'];
            $response          = $this->getImageWithRecommendedSize(0, '417', '384', true, $requestImage);
            $request['images'] = $response['images'];
        }
        if (arrayCheck('password', $request)) {
            $request['password'] = bcrypt($request['password']);
        }
        $request['user_type'] = 'client-staff';
		$request['client_id'] = Auth::user()->client_id;
        $user                 = User::create($request);

        $request['user_id']   = $user->id;
        $request['client_id'] = Auth::user()->client_id;
        $request['slug']      = getSlug('clients', $user->name);

        return ClientStaff::create($request);
    }

    public function update($request, $id)
    {

        $user            = User::findOrFail($id);
        $staff           = $user->clientStaff;

        if (arrayCheck('image', $request)) {
            $requestImage      = $request['image'];
            $response          = $this->getImageWithRecommendedSize(0, '417', '384', true, $requestImage);
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
