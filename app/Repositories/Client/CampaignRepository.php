<?php
namespace App\Repositories\Client;
use App\Models\Campaign;
use App\Traits\RepoResponse;

class CampaignRepository
{
    use RepoResponse;
    private $model;
    public function __construct(Campaign $model)
    {
        $this->model = $model;
    }
    public function all()
    {
        return Campaign::latest()->paginate(setting('pagination'));
    }

    public function activeSegments()
    {
        return Campaign::where('status', 1)->get();
    }

    public function find($id)
    {
        return Campaign::find($id);
    }
}
