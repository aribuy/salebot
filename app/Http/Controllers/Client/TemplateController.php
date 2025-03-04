<?php
namespace App\Http\Controllers\Client;
use App\Models\Language;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use App\Services\TemplateService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TemplateResource;
use App\DataTables\Client\TemplateDataTable;
use App\Repositories\Client\TemplateRepository;
use App\Http\Requests\Client\TemplateStoreRequest;
use App\DataTables\Client\TelegramTemplateDataTable;

class TemplateController extends Controller
{
    use RepoResponse;
    protected $repo;

    protected $whatsappService;

    public function __construct(TemplateRepository $repo, WhatsAppService $whatsappService)
    {
        $this->repo            = $repo;
        $this->whatsappService = $whatsappService;
    }

    public function index(TemplateDataTable $templateDataTable)
    {
        return $templateDataTable->render('backend.client.whatsapp.template.index');
    }

    public function getTelegramTemplate(TelegramTemplateDataTable $templateDataTable)
    {
        return $templateDataTable->render('backend.client.telegram.template.index');
    }

    public function loadTemplate(Request $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        $result = $this->repo->loadTemplate();
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }
        return back()->with($result->redirect_class, $result->message);
    }

    public function create()
    {
        if (empty(getClientWhatsAppID(Auth::user()->client))) {
            Toastr::error(__('for_template_create_app_id_must_required'));
            return redirect()->route('client.whatsapp.settings')->with('error',__('for_template_create_app_id_must_required'));
        }
        $data['locales'] =  Language::pluck('name','locale');
        return view('backend.client.whatsapp.template.create',$data);
    }

    public function store(TemplateStoreRequest $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        $result = $this->repo->store($request);
        if ($result->status) {
            return response()->json($result, 200);
        }
        return response()->json($result, 200);
    }
    

    public function edit($id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        if (empty(getClientWhatsAppID(Auth::user()->client))) {
            Toastr::error(__('for_template_create_app_id_must_required'));
            return redirect()->route('client.whatsapp.settings')->with('error',__('for_template_create_app_id_must_required.'));
        }
        $row =  $this->repo->find($id);
        $data = app(TemplateService::class)->execute($row);

        return view('backend.client.whatsapp.template.edit', $data);
    }



    public function syncTemplateByID($id)
    {
        $result = $this->repo->syncTemplateByID($id);
        if ($result->status) {
            return redirect()->route($result->redirect_to)->with($result->redirect_class, $result->message);
        }
        return back()->with($result->redirect_class, $result->message);
    }

    
    public function getTemplateByID($id)
    {
        return $this->repo->getTemplateByID($id);
    }

    public function update(TemplateStoreRequest $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        $result = $this->repo->update($request,$id);
        if ($result->status) {
            return response()->json($result, 200);
        }
        return response()->json($result, 200);
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

    public function whatsappTemplates(Request $request): JsonResponse
    {
        try {
            $templates = $request->flow_builder ? $this->repo->activeWhatsappTemplate() : $this->repo->whatsappTemplate();
            $data      = [
                'templates'     => TemplateResource::collection($templates),
                'next_page_url' => $request->flow_builder ? false : $templates->nextPageUrl(),
                'success'       => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];
            return response()->json($data);
        }
    }


}
