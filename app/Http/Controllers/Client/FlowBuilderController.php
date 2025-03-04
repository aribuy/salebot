<?php
namespace App\Http\Controllers\Client;
use Exception;
use App\Models\Flow;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Message;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Traits\BotReplyTrait;
use App\Models\FlowBuilderFile;
use App\Models\ContactFlowState;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\Factory;
use App\DataTables\Client\FlowDataTable;
use Illuminate\Contracts\Foundation\Application;
use App\Repositories\Client\FlowBuilderRepository;

class FlowBuilderController extends Controller
{
    use BotReplyTrait, ImageTrait;

    protected $repo;

    public function __construct(FlowBuilderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(FlowDataTable $dataTable)
    {
        try {
            return $dataTable->render('backend.client.flow.index');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return back();
        }
    }

    public function create(): Factory|View|array|Application
    {
        $data = [
            'contact' => false,
        ];

        return view('backend.client.flow.create', $data);
    }

    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'name'              => 'required|string',
            'contact_list_id' => 'nullable|integer|exists:contacts_lists,id',
            'segment_id' => 'nullable|integer|exists:segments,id',
        ]);

        return $this->repo->store($request);
    }

    public function show($id): Factory|View|array|Application
    {
        $data = [
            'contact' => false,
        ];

        return view('backend.client.flow.create', $data);
    }

    public function edit($id): JsonResponse
    {
        try {
            $data = [
                'flow'    => $this->repo->find($id),
                'success' => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name'              => 'required|string',
            'contact_list_id' => 'nullable|integer|exists:contacts_lists,id',
            'segment_id' => 'nullable|integer|exists:segments,id',
        ]);
        return   $this->repo->update($request, $id);
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
            $this->repo->statusChange($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 400,
                'message' => $e->getMessage(),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        DB::beginTransaction();
        try {
            $this->repo->destroy($id);

            $data = [
                'status'  => 'success',
                'message' => __('delete_successful'),
                'title'   => __('success'),
            ];
            DB::commit();

            return response()->json($data);
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                'status'  => 400,
                'message' => $e->getMessage(),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function uploadFile(Request $request): JsonResponse
    {
        $file_name = time() . '.' . $request->file('file')->getClientOriginalExtension();
        $request->file('file')->move(public_path('client/flow_builder'), $file_name);
        $flow      = FlowBuilderFile::create([
            'file'               => 'client/flow_builder/' . $file_name,
            'flow_template_id'   => $request->id,
            'flow_template_type' => $request->type,
        ]);

        return response()->json([
            'success'     => __('file_uploaded_successfully'),
            'file_object' => [
                'id'   => $flow->flow_template_id,
                'file' => static_asset($flow->file),
                'ext'  => pathinfo($flow->file, PATHINFO_EXTENSION),
            ],
        ]);
    }

    public function step(Request $request): JsonResponse
    {
        $flow      = Flow::with('nodes', 'edges')->find($request->flow_id);
        if ($request->node_id) {
            $node = $flow->nodes->firstWhere('node_id', $request->node_id);
        } else {
            $node = $flow->nodes->firstWhere('type', 'starter-box');
        }
        $next_node = $this->determineNextNode($flow, $node, $request->user_response);

        if ($next_node) {
            $data         = [
                'message' => __('next_node_found'),
                'success' => true,
            ];
            $data['node'] = $this->prepareNodeResponse($next_node);
        } else {
            $data = [
                'message' => __('successfully_completed_the_flow'),
                'success' => true,
            ];
        }

        return response()->json($data);
    }

    private function determineNextNode($flow, $current_node, $user_response)
    {
        $edge = $flow->edges->where('source', $current_node->node_id);

        if ($current_node->type == 'box-with-condition' && $user_response == 0) {

            $edge = $edge->where('sourceHandle', 'false')->first();
        } else {
            $edge = $edge->first();
        }

        if ($edge) {
            return $flow->nodes->firstWhere('node_id', $edge->target);
        }

        return null; // Or handle this case as needed
    }

    private function prepareNodeResponse($node): array
    {
        return [
            'node_id' => $node->node_id,
            'type'    => $node->type,
            'data'    => $node->data,
        ];
    }

    public function sendQuickReply(Request $request)
    {
        $message = Message::find($request->message_id);

        return $this->QuickReply($message);
        // $this->sendFlowReply($request);
    }

    public function sendFlowReply($request)
    {
        $contact          = Contact::find(1);
        $client           = Client::find(1);
        $message          = [];
        $currentFlowState = ContactFlowState::where('contact_id', $contact->id)->first();
        return $this->flowReply($currentFlowState->flow_id, $contact, $client, $message);
    }
}
