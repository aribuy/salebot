<?php
namespace App\Http\Controllers\Client;
use App\Models\BotGroup;
use Illuminate\Http\Request;
use App\Traits\TelegramTrait;
use App\Http\Controllers\Controller;
use App\Repositories\Client\ContactRepository;
use App\Repositories\Client\CampaignRepository;
use App\Repositories\Client\TemplateRepository;
use App\DataTables\Client\TelegramSubscriberDataTable;
use App\Repositories\Client\TelegramSubscriberRepository;

class TelegramSubscriberController extends Controller
{
    use TelegramTrait;

    protected $repo;

    protected $templateRepo;

    protected $ContactsRepo;

    protected $campaignsRepo;

    public function __construct(
        TelegramSubscriberRepository $repo,
        TemplateRepository $templateRepo,
        ContactRepository $ContactsRepo,
        CampaignRepository $campaignsRepo,

    ) {
        $this->repo             = $repo;
        $this->templateRepo     = $templateRepo;
        $this->ContactsRepo     = $ContactsRepo;
        $this->campaignsRepo    = $campaignsRepo;

    }

    public function index(TelegramSubscriberDataTable $contactsDataTable)
    {
       $groups = BotGroup::active()->withPermission()->pluck('name', 'id');
        $data   = [
            'groups' => $groups,
        ];
        return $contactsDataTable->render('backend.client.telegram.contacts.index', $data);
    }

    public function telegramSubscribersync($id)
    {
        return $this->repo->telegramSubscribersync($id);
    }
    public function addBlacklist($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        return $this->repo->addBlock($id);
      }

    public function removeBlacklist($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
   
        return $this->repo->removeBlacklist($id);
    }

    public function delete($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];
            return response()->json($data);
        }
        return $this->repo->destroy($id);

    }



    
}
