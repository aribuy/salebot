<?php

namespace App\Repositories\Client;

use App\Models\Flow;
use App\Enums\TypeEnum;
use App\Models\Contact;
use App\Models\ContactFlow;
use App\Models\FlowBuilderFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class FlowBuilderRepository
{

    private $model;
    private $contact;
    public function __construct(
        Flow $model,
        Contact $contact,
    ) {
        $this->model = $model;
        $this->contact = $contact;
    }


    public function all($with = [])
    {
        return $this->model->latest()->paginate(setting('paginate'));
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function store($request)
    { 
    
        DB::beginTransaction();
        try {

            $client = auth()->user()->client;

            // Retrieve the client's active subscription plan
            $activeSubscription = $client->activeSubscription;
            $plan = $activeSubscription->plan;
    
            // Count the number of flow builders already created
            $existingFlowsCount = $this->model->where('client_id', $client->id)->count();
    
            // Check if the client has reached the maximum number of flow builders allowed
            if ($existingFlowsCount >= $plan->max_flow_builder) {
                return response()->json([
                    'success' => false,
                    'message'   => __('max_flow_builder_notice'),
                ]);
            }

            $flow = new $this->model();
            $flow->client_id = auth()->user()->client_id;
            $flow->name = $request->name;
              if($request->contact_list_id){
                $flow->contact_list_id = $request->contact_list_id;
            }
            if($request->segment_id){
                $flow->segment_id = $request->segment_id;
            }
            $flow->flow_for = $request->flow_for ?? 'whatsapp';
            $flow->flow_type = $request->flow_type ?? "generic";
            $flow->status = $request->status ?? 1;
            $flow->data = $request->flow_data;
            $flow->save();
            $this->parseRequestData($flow, $request->flow_data);
            DB::commit();
            return response()->json([
                'success'      => true,
                'message'      => __('flow_created_successfully'),
                'redirect_url' => route('client.flow-builders.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }

        return $flow;
        // return Flow::create($flow_data);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $client = auth()->user()->client;
            // Retrieve the client's active subscription plan
            $activeSubscription = $client->activeSubscription;
            $plan = $activeSubscription->plan;
            // Retrieve the existing flow
            $flow = $this->model->find($id);
            if (!$flow || $flow->client_id !== $client->id) {
                return response()->json([
                    'success' => false,
                    'error'   => __('max_flow_builder_edit_notice'),
                ]);
            }
                // Count the number of flow builders, excluding the one being updated
            $existingFlowsCount = $this->model
                ->where('client_id', $client->id)
                ->where('id', '!=', $id)
                ->count();
                // Check if updating the flow would exceed the maximum number of flow builders allowed
            if ($existingFlowsCount >= $plan->max_flow_builder) {
                return response()->json([
                    'success' => false,
                    'error'   => __('max_flow_builder_notice'),
                ]);
            }
    
            // Update the flow with new data
            $flow->name         = $request->name;
            if ($request->contact_list_id) {
                $flow->contact_list_id = $request->contact_list_id;
            }
            if ($request->segment_id) {
                $flow->segment_id = $request->segment_id;
            }
            $flow->flow_for     = $request->flow_for ?? 'whatsapp';
            $flow->flow_type    = $request->flow_type ?? 'generic';
            $flow->status       = $request->status ?? 1;
            $flow->data         = $request->flow_data;
            $flow->update();
    
            // Remove existing nodes and edges before parsing new data
            $flow->nodes()->delete();
            $flow->edges()->delete();
            $this->parseRequestData($flow, $request->flow_data);
    
            DB::commit();
    
            return response()->json([
                'success'      => true,
                'message'      => __('updated_successfully'),
                'redirect_url' => route('client.flow-builders.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    
        return $flow;
    }
    

    private function getFilteredContacts($request)
    {
        $contacts = $this->contact->select('contacts.*')
            ->active()
            ->where('contacts.is_blacklist', 0)
            ->where('type', TypeEnum::WHATSAPP)
            ->whereNotNull('phone')
            ->where('client_id', Auth::user()->client->id);

        if (!empty($request->contact_list_ids)) {
            $contacts = $contacts->join('contact_relation_lists', 'contact_relation_lists.contact_id', '=', 'contacts.id')
                ->whereIn('contact_relation_lists.contact_list_id', $request->contact_list_ids);
        }

        if (!empty($request->segment_ids)) {
            $contacts = $contacts->join('contact_relation_segments', 'contact_relation_segments.contact_id', '=', 'contacts.id')
                ->whereIn('contact_relation_segments.segment_id', $request->segment_ids);
        }

        return $contacts->distinct('contacts.id')->get();
    }
    private function insertContactFlows($contacts, $flowId)
    {
        foreach ($contacts as $contact) {
            $contactFlow = new ContactFlow();
            $contactFlow->contact_id = $contact->id;
            $contactFlow->flow_id = $flowId;
            $contactFlow->status = 1;
            $contactFlow->save();
        }
    }

    private function parseRequestData($flow, $flow_data): void
    {
        // dd($flow_data);
        $nodes    = $edges = [];
        $messages = collect($flow_data['messages']);
        foreach ($flow_data['elements']['nodes'] as $node_data) {
            $data    = [];
            $connections = [];
            $box     = $messages->where('id', $node_data['id'])->first();
            if ($node_data['type'] == 'starter-box') {
                $data['type']        = 'text';
                $data['keyword']     = $box['keyword'] ?? '';
                $data['matching_types']  = $box['matching_types'] ?? null;
                $data['duration'] = getArrayValue('text_duration', $box, 0);
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'text';
            } elseif ($node_data['type'] == 'box-with-title') {
                $data['type']     = 'text';
                $data['text']     = $box['text'];
                $data['duration'] = getArrayValue('text_duration', $box, 0);
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'text';
            } elseif ($node_data['type'] == 'node-image') {
                $data['type']     = 'image';
                $data['image']    = $box['image'];
                $data['duration'] = getArrayValue('image_duration', $box, 0);
                $data['original_file_name'] = null;
                $data['mime_type'] = null;
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'image';
            } elseif ($node_data['type'] == 'box-with-audio') {
                $data['type']     = 'audio';
                $data['audio']    = $box['audio'];
                $data['duration'] = getArrayValue('audio_duration', $box, 0);
                $data['original_file_name'] = null;
                $data['mime_type'] = null;
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'audio';
            } elseif ($node_data['type'] == 'box-with-video') {
                $data['type']     = 'video';
                $data['video']    = $box['video'];
                $data['duration'] = getArrayValue('video_duration', $box, 0);
                $data['original_file_name'] = null;
                $data['mime_type'] = null;
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'video';
            } elseif ($node_data['type'] == 'box-with-file') {
                $data['type']     = 'document';
                $data['file']     = $box['file'];
                $data['duration'] = getArrayValue('file_duration', $box, 0);
                $data['original_file_name'] = null;
                $data['mime_type'] = null;
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'file';
            } elseif ($node_data['type'] == 'box-with-location') {
                $data['type']     = 'location';
                $data['lat']      = $box['latitude'];
                $data['long']     = $box['longitude'];
                $data['duration'] = getArrayValue('location_duration', $box, 0);
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'location';
            } elseif ($node_data['type'] == 'box-with-condition') {
                $data['type'] = 'conditions';
                $data['conditions'] = getArrayValue('condition_fields', $box, []);
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'conditions';
            } elseif ($node_data['type'] == 'box-with-list') {

                $data['type'] = 'list';
                $data['header'] = [
                    'type' => 'text',
                    'text' => $box['header_text'] ?? ''
                ];
                $data['body'] = [
                    'text' => $box['text_message'] ?? ''
                ];
                $data['footer'] = [
                    'text' => $box['footer_text'] ?? ''
                ];
                $data['action'] = [
                    'button' => $box['button_text'] ?? '',
                    'sections' => []
                ];
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'list';
            } elseif ($node_data['type'] == 'box-with-button') {
                // Initialize the data array
                $data['type'] = 'button';
                $data['body'] = [
                    'text' => $box['button_message'] ?? ''
                ];
                $data['action'] = [
                    'buttons' => []
                ];
                foreach ($box['items'] as $button) {
                    $data['action']['buttons'][] = [
                        'type' => 'reply',
                        'reply' => [
                            'id' => $button['id'],
                            'title' => $button['text']
                        ]
                    ];
                } 
                if (isset($box['header_text'])) {
                    $data['header_text'] = $box['header_text'];
                    $data['header_type'] = 'text';
                } 
                if (isset($box['header_media_type'])) {
                    $data['header_media_type'] = $box['header_media_type'] ?? '';
                    $data['header_type'] = '';
                }
                if (isset($box['header_media'])) {
                    $data['header_media'] = $box['header_media'] ?? '';
                }
                if (isset($box['footer_text'])) {
                    $data['footer_text'] = $box['footer_text'] ?? '';
                }
                $connections['inputs'] = [];
                $connections['outputs'][0]['type'] = 'button';
            }

            $nodes[$node_data['id']] = [
                'node_id' => $node_data['id'],
                'type' => $node_data['type'],
                'position' => $node_data['position'],
                'data' => $data,
                'connections' => $connections,
            ];
        }

        foreach ($flow_data['elements']['edges'] as $edge_data) {
            $sourceNode = $edge_data['source'];
            $targetNode = $edge_data['target'];
            $edges[] = [
                'edge_id'      => $edge_data['id'],
                'source'       => $edge_data['source'],
                'target'       => $edge_data['target'],
                'data'         => $edge_data['data'],
                'sourceHandle' => $edge_data['sourceHandle'],
            ];
            // Update source node's outputs
            if (isset($nodes[$sourceNode]['connections']['outputs'])) {
                foreach ($nodes[$sourceNode]['connections']['outputs'] as &$output) {
                    if (empty($output['node_id'])) {
                        $output['node_id'] = $targetNode;
                        $output['data'] = $edge_data['data'];
                        break;
                    }
                }
            }
            // Update target node's inputs
            if (isset($nodes[$targetNode]['connections']['inputs']) && empty($nodes[$targetNode]['connections']['inputs']['node_id'])) {
                $nodes[$targetNode]['connections']['inputs'] = [
                    'node_id' => $sourceNode,
                    'type' => $edge_data['targetHandle'],
                    'data' => $edge_data['data'],
                ];
            }
        }
        // Save nodes and edges to the flow
        $flow->nodes()->createMany(array_values($nodes));
        $flow->edges()->createMany($edges);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return $this->find($id)->update(['status' => $request['data']['value']]);
    }

    public function destroy($id): bool
    {
        $flow     = $this->find($id);
        $nodes_id = $flow->nodes->pluck('node_id');
        $flies    = FlowBuilderFile::whereIn('flow_template_id', $nodes_id->toArray())->get();
        foreach ($flies as $file) {
            File::delete('public/' . $file->file);
            $file->delete();
        }
        $flow->nodes()->delete();
        $flow->edges()->delete();
        $flow->delete();
        return true;
    }



}
