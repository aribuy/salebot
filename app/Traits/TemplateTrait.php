<?php
namespace App\Traits;
use App\Enums\TypeEnum;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

trait TemplateTrait
{ 

    public $graphApiBaseUrl = 'https://graph.facebook.com/v19.0/';
   
    public function loadTemplate($clientSetting)
    {
        try {
            $accessToken                   = getClientWhatsAppAccessToken(Auth::user()->client);
            $whatsapp_business_account_id  = getClientWhatsAppBusinessAcID(Auth::user()->client);
            $url = $this->graphApiBaseUrl . "{$whatsapp_business_account_id}/message_templates"; 
            $allData                       = [];
            $nextPageUrl                   = $url;

            do {

                $response = Http::withToken($accessToken)->get($nextPageUrl);
                if (!$response->successful()) {
                    return $this->formatResponse(false, $response['error']['message'] ?? 'Unknown error occurred.', 'client.templates.index', []);
                }
                $data = $response->json();
                $templateIds = collect($data['data'])->pluck('id')->toArray();
                $allData     = array_merge($allData, $data['data']);
                $nextPageUrl = $data['paging']['next'] ?? null;

            } while ($nextPageUrl);


            foreach ($allData as $templateObject) {

                $template = Template::withPermission()->firstOrNew(['template_id' => $templateObject['id']]);
                $template->fill([
                    'client_setting_id'=> $clientSetting->id,
                    'name'          => $templateObject['name'],
                    'components'    => $templateObject['components'] ?? [],
                    'category'      => $templateObject['category'],
                    'language'      => $templateObject['language'],
                    'client_id'     => Auth::user()->client->id,
                    'status'        => $templateObject['status'],
                    'type'          => TypeEnum::WHATSAPP,
                ]);

                $template->save();
            }
            Template::whereNotIn('template_id', collect($allData)->pluck('id'))->withPermission()->delete();

        

            return $this->formatResponse(true, __('updated_successfully'), 'client.templates.index', []);

        } catch (\Throwable $e) {

            if (config('app.debug')) {
                dd($e->getMessage());            
            }

            \Log::info('Load Template', [$e->getMessage()]);

            return $this->formatResponse(false, $e->getMessage(), 'client.templates.index', []);
        }
    }

    // Helper method to build the header component
    private function buildHeaderComponent($requestData)
    {
        // Determine header type and build component accordingly
        switch ($requestData['header_type']) {
            case "TEXT":
                $headerText = $requestData['header_text'];
                $headerExample = !empty($requestData['header_variable']) ? ['header_text' => $requestData['header_variable']] : null;
                return [
                    'type' => 'HEADER',
                    'format' => 'TEXT',
                    'text' => $headerText,
                    'example' => $headerExample
                ];
                // Handle other header types similarly
            default:
                return null;
        }
    }

    // Helper method to build the buttons component
    private function buildButtonsComponent($requestData)
    {
        // Determine button type and build component accordingly
        switch ($requestData['button_type']) {
            case "QUICK_REPLY":
                // Build quick reply buttons
                $buttons = [];
                foreach ($requestData['button_text'] as $text) {
                    $buttons[] = [
                        "type" => "QUICK_REPLY",
                        "text" => $text
                    ];
                }
                return [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons
                ];
                // Handle other button types similarly
            default:
                return null;
        }
    }

    // Helper method to send request to WhatsApp API
    private function sendRequestToWhatsAppAPI($messageTemplate)
    {
        $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
        $whatsappBusinessAccountId = getClientWhatsAppBusinessAcID(Auth::user()->client);
        $apiUrl = $this->graphApiBaseUrl . "{$whatsappBusinessAccountId}/message_templates";
    
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messageTemplate));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
    
        return json_decode($response);
    }
    




}
