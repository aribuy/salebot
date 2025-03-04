<?php

namespace App\Repositories\Client;

use App\Models\ContactsList;

class ContactListRepository
{
    private $model;

    public function __construct(ContactsList $model)
    {
        $this->model = $model;
    }

    public function combo()
    {
        return $this->model->active()->withPermission()->pluck('name', 'id');
    }

    public function all()
    {
        return ContactsList::latest()->paginate(setting('pagination'));
    }

    public function activeList()
    {
        return ContactsList::where('status', 1)->withPermission()->get();
    }

    public function store($request)
    {
        $request['client_id'] = auth()->user()->client_id;
        $segment              = ContactsList::create($request);

        return $segment;
    }

    public function find($id)
    {
        return ContactsList::find($id);
    }

    public function update($request, $id)
    {
        $contactsList             = ContactsList::find($id);
        $contactsList->name       = $request->name;
        $contactsList->updated_by = auth()->user()->id;
        $contactsList->status     = $request->status;

        return $contactsList->save();
    }

    public function destroy($id)
    {
        return ContactsList::destroy($id);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return ContactsList::find($id)->update($request);
    }
}
