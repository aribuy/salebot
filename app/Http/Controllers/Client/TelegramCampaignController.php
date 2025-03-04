<?php
namespace App\Http\Controllers\Client;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\BotGroup;
use App\Models\Timezone;
use Illuminate\Http\Request;
use App\Traits\TelegramTrait;
use App\Models\GroupSubscriber;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\DataTables\Client\GroupDataTable;
use App\Services\TelegramNewContactsService;
use App\Repositories\Client\ContactRepository;
use App\Repositories\Client\SegmentRepository;
use App\Services\TelegramTotalContactsService;
use App\Repositories\Client\CampaignRepository;
use App\Repositories\Client\TemplateRepository;
use App\Repositories\Client\ContactListRepository;
use App\Repositories\Client\TeleCampaignRepository;
use App\DataTables\Client\TelegramCampaignDataTable;
use App\Http\Requests\Client\TelegramCampaignRequest;

class TelegramCampaignController extends Controller
{
    use TelegramTrait;

    protected $repo;

    protected $templateRepo;

    protected $contactListsRepo;

    protected $ContactsRepo;

    protected $segmentsRepo;

    protected $campaignsRepo;

    public function __construct(
        TeleCampaignRepository $repo,
        TemplateRepository $templateRepo,
        ContactListRepository $contactListsRepo,
        ContactRepository $ContactsRepo,
        SegmentRepository $segmentsRepo,
        CampaignRepository $campaignsRepo,

    ) {
        $this->repo             = $repo;
        $this->templateRepo     = $templateRepo;
        $this->contactListsRepo = $contactListsRepo;
        $this->ContactsRepo     = $ContactsRepo;
        $this->segmentsRepo     = $segmentsRepo;
        $this->campaignsRepo    = $campaignsRepo;

    }

    public function index(Request $request, TelegramCampaignDataTable $dataTable)
    {

        $data = [
            'templates' => $this->templateRepo->combo(),
            'segments'  => $this->segmentsRepo->combo(),
            'lists'     => $this->contactListsRepo->combo(),
        ];

        return $dataTable->with(['campaign_type' => 'telegram'])->render('backend.client.telegram.campaigns.index', $data);
    }

    public function groups(GroupDataTable $groupDataTable)
    {
        $data = [];

        return $groupDataTable->render('backend.client.telegram.groups.index', $data);
    }

    public function create()
    { 
        $data = [
            'contacts' => Contact::where('type', TypeEnum::TELEGRAM)->withPermission()->active()->pluck('name', 'id'),
            'time_zones'    => Timezone::all(),

        ];

        return view('backend.client.telegram.campaigns.create', $data);
    }

    public function store(TelegramCampaignRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        $result = $this->repo->store($request);
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }

        return back()->with($result->redirect_class, $result->message);
    }

    public function overview(Request $request)
    {
        $modifiedRequest  = new Request(array_merge($request->all(), ['type' => TypeEnum::TELEGRAM->value]));
        $totalsubscribers = GroupSubscriber::withPermission()->where('type', TypeEnum::TELEGRAM->value)->count();
        $totalGroups      = BotGroup::withPermission()->active()->where('type', TypeEnum::TELEGRAM->value)->count();
        $activeSubscriber = GroupSubscriber::withPermission()->where('status', 1)->where('is_blacklist', 0)->where('type', TypeEnum::TELEGRAM->value)->count();

        if ($totalsubscribers > 0) {
            $activePercentage = ($activeSubscriber / $totalsubscribers) * 100;
        } else {
            $activePercentage = 0;
        }
        $data             = [
            'charts'           => [
                'total_contacts' => app(TelegramTotalContactsService::class)->execute($modifiedRequest),
                'new_contacts'   => app(TelegramNewContactsService::class)->execute($modifiedRequest),
            ],
            'blacklistCount'   => GroupSubscriber::where('is_blacklist', 1)->where('type', TypeEnum::TELEGRAM->value)->count(),
            'activePercentage' => $activePercentage,
            'totalGroups'      => $totalGroups,
            'totalsubscribers' => $totalsubscribers,
        ];

        return view('backend.client.telegram.overview.index', $data);
    }

    public function statusChange(Request $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $botgroup         = BotGroup::find($request['id']);
            $botgroup->status = $request['status'];
            $botgroup->save();
            $data             = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 400,
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        return $this->repo->statusUpdate($request, $id);
    }

    public function view($id)
    {
        try {
            $campaign = $this->repo->find($id);

            $data     = [
                'campaign' => $campaign,
            ];

            return view('backend.client.telegram.campaigns.view', $data);
        } catch (\Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }
}
