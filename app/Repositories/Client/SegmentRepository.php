<?php
namespace App\Repositories\Client;
use App\Models\Segment;

class SegmentRepository
{
    private $model;

    public function __construct(Segment $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return Segment::latest()->withPermission()->paginate(setting('pagination'));
    }

    public function combo()
    {
        return $this->model->active()->withPermission()->pluck('title', 'id');
    }

    public function activeSegments()
    {
        return Segment::where('status', 1)->withPermission()->get();
    }

    public function store($request)
    {
        $segment = Segment::create($request);
        return $segment;
    }

    public function find($id)
    {
        return Segment::find($id);
    }

    public function update($request, $id)
    {
        $segment = Segment::find($id);
        return $segment->update($request);
    }

    public function destroy($id)
    {
        return Segment::destroy($id);
    }

    public function statusChange($request)
    {
        $id = $request['id'];
        return Segment::find($id)->update($request);
    }

}
