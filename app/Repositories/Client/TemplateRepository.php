<?php
namespace App\Repositories\Client;
use App\Enums\TypeEnum;
use App\Models\Template;
use App\Traits\ImageTrait;
use App\Traits\CommonTrait;
use Illuminate\Support\Str;
use App\Traits\RepoResponse;
use App\Traits\TelegramTrait;
use App\Traits\TemplateTrait;
use App\Traits\WhatsAppTrait;
use App\Services\TemplateService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TemplateRepository
{
    use CommonTrait, ImageTrait, RepoResponse, TelegramTrait, WhatsAppTrait,TemplateTrait;
    const GRAPH_API_BASE_URL = 'https://graph.facebook.com/v19.0/';
    private $model;

    protected $whatsappService;

    public function __construct(Template $model, WhatsAppService $whatsappService)
    {
        $this->model           = $model;
        $this->whatsappService = $whatsappService;
    }

    public function combo()
    {
        return $this->model->withPermission()->active()->pluck('name', 'id');
    }

    public function all()
    {
        return $this->model->withPermission()->latest()->paginate(setting('pagination'));
    }

    public function activeSegments()
    {
        return $this->model->withPermission()->where('status', 1)->get();
    }

    public function find($id)
    {
        return $this->model->withPermission()->find($id);
    }

    public function get_size($file_path)
    {
        return Storage::size($file_path);
    }

    private function uploadMediaToFacebookStep1($fileUrl, $fileSize, $mimeType)
    {
        $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
        // Upload the image to Facebook
        $appId = getClientWhatsAppID(Auth::user()->client);
        $apiUrl = self::GRAPH_API_BASE_URL . "{$appId}/uploads?file_length={$fileSize}&file_type={$mimeType}&access_token={$accessToken}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => file_get_contents($fileUrl),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: ' . $mimeType,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // Parse the response to extract the session ID
        $responseArray = json_decode($response, true);
        if (isset($responseArray['id'])) {
            return $responseArray['id'];
        } else {
            return null; // Handle error condition
        }
    }

    private function uploadMediaToFacebookStep2($fileUrl, $session_id)
    {
        $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
        // Upload the image to Facebook
        $apiUrl = self::GRAPH_API_BASE_URL . "{$session_id}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => file_get_contents($fileUrl), // Assuming $fileUrl contains the file contents
            CURLOPT_HTTPHEADER => array(
                'Authorization: OAuth ' . $accessToken,
                'file_offset: 0',
                'Content-Type: text/plain'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // Parse the response to extract the session ID
        $responseArray = json_decode($response, true);
        if (isset($responseArray['h'])) {
            return $responseArray['h'];
        } else {
            return null; // Handle error condition
        }
    }

    private function uploadMediaToFacebook($file)
    { 
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        $session_id = $this->uploadMediaToFacebookStep1($file, $fileSize, $mimeType);
        if (empty($session_id)) {  
            return null;
        }
          // Handle different MIME types
          if (str_contains($mimeType, 'image')) {
            $response  = $this->saveImage($file);
            $media_url = $response['images'];
            $media_url = getFileLink('original_image', $media_url);
        } elseif (str_contains($mimeType, 'video')) {
            $response  = $this->saveFile($file, 'mp4', false);
            $media_url = asset('public/'.$response);
        } elseif (str_contains($mimeType, 'audio')) {
            $response  = $this->saveFile($file, 'mp3', false);
            $media_url = asset('public/'.$response);
            // $media_url = asset($this->saveFile($file, 'mp3', false));
        } elseif (str_contains($mimeType, 'pdf')) {
            $response  = $this->saveFile($file, 'pdf', false);
            $media_url = asset('public/'.$response);
            // $media_url = asset($this->saveFile($file, 'pdf', false));
        }
        $media = $this->uploadMediaToFacebookStep2($media_url, $session_id);
        if (empty($media)) {
            return null;
        }
        return $media;
    }


    public function store($request)
    {
       
        try {
            $clientSetting = Auth::user()->client->whatsappSetting;
            $media = null;
            $template_name = Str::lower($request->template_name);
            $locale = $request->locale;
            $template_category = $request->template_category;
            $message_body = $request->message_body;
            $footer_text = $request->footer_text;
            $header_text = $request->header_text;
            $header_type = $request->header_type;
            // Prepare the message template data
            $messageTemplate = [
                'name' => $template_name,
                'language' => $locale,
                'category' => $template_category,
                'components' => []
            ];

            // Add header component if header text is not null
            if (!empty($header_type)) {
                if ($header_type == "TEXT") {

                    $header_example = !empty($request->header_variable) ? ['header_text' => $request->header_variable] : null;
                    $header_component = [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => $header_text,
                    ];
                    if ($header_example) {
                        $header_component['example'] = $header_example;
                    }
                    $messageTemplate['components'][] = $header_component;
                } elseif ($header_type == "IMAGE") {
                    if ($request->hasFile('header_image')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_image'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }

                    if (!empty($media)) {
                        $header_example = !empty($request->header_image) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'IMAGE',
                        ];

                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }

                        $messageTemplate['components'][] = $header_component;
                    }
                } elseif ($header_type == "VIDEO") {
                    // $media_url = asset($this->saveFile($request->header_video, 'mp4', false));
                    if ($request->hasFile('header_video')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_video'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }
                    if (!empty($media)) {
                        $header_example = !empty($request->header_video) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'VIDEO',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }
                } elseif ($header_type == "DOCUMENT") {
                    if ($request->hasFile('header_document')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_document'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }
                    if (!empty($media)) {
                        // $media_url = asset($this->saveFile($request->header_document, 'pdf', false));
                        $header_example = !empty($request->header_document) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'DOCUMENT',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }
                } elseif ($header_type == "AUDIO") {
                    // Save the audio file and get its asset URL
                    // $media_url = asset($this->saveFile($request->header_audio, 'mp3', false));
                    if ($request->hasFile('header_audio')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_audio'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }
                    if (!empty($media)) {
                        $header_example = !empty($request->header_audio) ? ["header_handle" => [$media]] : null;
                        // Define the message component for the audio header type
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'AUDIO',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }
                } elseif ($header_type == "LOCATION") {
                    $messageTemplate['components'][] = [
                        'type' => 'HEADER',
                        'format' => 'LOCATION',
                    ];
                }
            }
            
            // Add body component
            $body_example = !empty($request->body_variable) ? ['body_text' => [$request->body_variable]] : null;
            // Build the BODY component
            $body_component = [
                'type' => 'BODY',
            ];
            
            if ($template_category == "AUTHENTICATION") {
                $body_component['add_security_recommendation'] = true;
            } else {
                $body_component['text'] = $message_body;
                // If there's a valid example, add it to the component
                if ($body_example) {
                    $body_component['example'] = $body_example;
                }
            }
            
            // Add the BODY component to the message template
            $messageTemplate['components'][] = $body_component;

            // Add footer component if footer text is not null
            if (!empty($footer_text)) {
                $messageTemplate['components'][] = [
                    'type' => 'FOOTER',
                    'text' => $footer_text
                ];
            }
            if ($request->button_type !== "NONE") {
                // Add buttons component
                if ($request->button_type == "QUICK_REPLY") {
                    // Create an empty array to store the buttons
                    $buttons = [];
                    // Iterate over the provided button texts to add buttons to the array
                    foreach ($request->button_text as $text) {
                        $buttons[] = [
                            "type" => "QUICK_REPLY",
                            "text" => $text
                        ];
                    }
                    // Append the buttons array to the components array in the message template
                    $messageTemplate['components'][] = [
                        'type' => 'BUTTONS',
                        'buttons' => $buttons
                    ];
                } else if ($request->button_type == "CTA") {

                    $buttons = [];
                    $button_text = $request->button_text;
                    $button_value = $request->button_value;
                    foreach ($request->type_of_action as $key => $action) {
                        if ($action == "URL") {
                            $buttons[] = [
                                "type" => "URL",
                                "text" => $button_text[$key],
                                "url" => $button_value[$key],
                            ];
                        } elseif ($action == "PHONE_NUMBER") {
                            $buttons[] = [
                                "type" => "PHONE_NUMBER",
                                "text" => $button_text[$key],
                                "phone_number" => $button_value[$key],
                            ];
                        } elseif ($action == "COPY_CODE") {
                            $buttons[] = [
                                "type" => "OTP",
                                "otp_type" => "COPY_CODE",
                                "text" => $button_value[$key],
                            ];
                        }
                    }
                    // Append the buttons array to the components array in the message template
                    $messageTemplate['components'][] = [
                        'type' => 'BUTTONS',
                        'buttons' => $buttons
                    ];
                }
            }
            $components =  json_encode($messageTemplate);
            $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
            $whatsappBusinessAccountId = getClientWhatsAppBusinessAcID(Auth::user()->client);
            $apiUrl = self::GRAPH_API_BASE_URL . "{$whatsappBusinessAccountId}/message_templates";
            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $components);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $this->formatResponse(false, $err, 'client.templates.index', []);
            }
            $_response = json_decode($response);
            if (!empty($_response->error)) {
                $error_message = !empty($_response->error->error_user_msg) ?
                    $_response->error->error_user_msg :
                    $_response->error->message;
                return $this->formatResponse(
                    false,
                    $error_message,
                    'client.templates.create',
                    []
                );
            } else {
                $template = new $this->model();
                $template->name = $template_name;
                $template->client_setting_id = $clientSetting->id;
                $template->components = $messageTemplate;
                $template->category = $template_category;
                $template->language = $locale;
                $template->client_id = Auth::user()->client->id;
                $template->type = TypeEnum::WHATSAPP;
                $template->status = $_response->status;
                $template->template_id = $_response->id;
                $template->header_media = $media;
                $template->save();
                $this->syncTemplateByID($template->id);
            }
            return $this->formatResponse(true, __('message_template_created_successfully'), route('client.templates.index'), []);
        } catch (\Throwable $e) {
            \Log::error($e);
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, $e->getMessage(), route('client.templates.index'), []);
        }
    }

    public function update($request, $id)
    {
        $template = $this->model->withPermission()->find($id);
        $clientSetting = Auth::user()->client->whatsappSetting;

        try {
            $media = null;
            $template_name = Str::lower($request->template_name);
            $locale = $request->locale;
            $template_category = $request->template_category;
            $message_body = $request->message_body;
            $footer_text = $request->footer_text;
            $header_text = $request->header_text;
            $header_type = $request->header_type;
            $messageTemplate = [
                'name' => $template_name,
                'language' => $locale,
                'category' => $template_category,
                'components' => []
            ];
            if (!empty($header_type)) {
                if ($header_type == "TEXT") {
                    $header_example = !empty($request->header_variable) ? ['header_text' => $request->header_variable] : null;
                    $header_component = [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => $header_text,
                    ];
                    if ($header_example) {
                        $header_component['example'] = $header_example;
                    }
                    $messageTemplate['components'][] = $header_component;
                } elseif ($header_type == "IMAGE") {
                    if ($request->hasFile('header_image')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_image'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }

                    if (!empty($media)) {
                        $header_example = !empty($request->header_image) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'IMAGE',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }

                } elseif ($header_type == "VIDEO") {
                    if ($request->hasFile('header_video')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_video'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }
                    if (!empty($media)) {
                        $header_example = !empty($request->header_video) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'VIDEO',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }

                } elseif ($header_type == "DOCUMENT") {
                    if ($request->hasFile('header_document')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_document'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }
                    if (!empty($media)) {
                        $header_example = !empty($request->header_document) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'DOCUMENT',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }

                } elseif ($header_type == "AUDIO") {
                    if ($request->hasFile('header_audio')) {
                        $media = $this->uploadMediaToFacebook($request->file('header_audio'));
                        if (empty($media)) {
                            return $this->formatResponse(false, __('unable_to_upload_media'), 'client.templates.index', []);
                        }
                    }
                    if (!empty($media)) {
                        $header_example = !empty($request->header_audio) ? ["header_handle" => [$media]] : null;
                        $header_component = [
                            'type' => 'HEADER',
                            'format' => 'AUDIO',
                        ];
                        if ($header_example) {
                            $header_component['example'] = $header_example;
                        }
                        $messageTemplate['components'][] = $header_component;
                    }

                } elseif ($header_type == "LOCATION") {
                    $messageTemplate['components'][] = [
                        'type' => 'HEADER',
                        'format' => 'LOCATION',
                    ];
                }
            }
            $body_example = !empty($request->body_variable) ? ['body_text' => [$request->body_variable]] : null;
            // $body_component = [
            //     'type' => 'BODY',
            //     'text' => $message_body,
            // ];
            // if ($body_example) {
            //     $body_component['example'] = $body_example;
            // }
            $body_component = [
                'type' => 'BODY',
            ];
            if ($template_category == "AUTHENTICATION") {
                $body_component['add_security_recommendation'] = true;
            } else {
                $body_component['text'] = $message_body;
                // If there's a valid example, add it to the component
                if ($body_example) {
                    $body_component['example'] = $body_example;
                }
            }
            $messageTemplate['components'][] = $body_component;
            if (!empty($footer_text)) {
                $messageTemplate['components'][] = [
                    'type' => 'FOOTER',
                    'text' => $footer_text
                ];
            }
            if ($request->button_type !== "NONE") {
                // Add buttons component
                if ($request->button_type == "QUICK_REPLY") {
                    // Create an empty array to store the buttons
                    $buttons = [];
                    // Iterate over the provided button texts to add buttons to the array
                    foreach ($request->button_text as $text) {
                        $buttons[] = [
                            "type" => "QUICK_REPLY",
                            "text" => $text
                        ];
                    }
                    // Append the buttons array to the components array in the message template
                    $messageTemplate['components'][] = [
                        'type' => 'BUTTONS',
                        'buttons' => $buttons
                    ];
                } else if ($request->button_type == "CTA") {

                    $buttons = [];
                    $button_text = $request->button_text;
                    $button_value = $request->button_value;
                    foreach ($request->type_of_action as $key => $action) {
                        if ($action == "URL") {
                            $buttons[] = [
                                "type" => "URL",
                                "text" => $button_text[$key],
                                "url" => $button_value[$key],
                            ];
                        } elseif ($action == "PHONE_NUMBER") {
                            $buttons[] = [
                                "type" => "PHONE_NUMBER",
                                "text" => $button_text[$key],
                                "phone_number" => $button_value[$key],
                            ];
                        } elseif ($action == "COPY_CODE") {
                            $buttons[] = [
                                "type" => "OTP",
                                "otp_type" => "COPY_CODE",
                                "text" => $button_value[$key],
                            ];
                        }
                    }
                    // Append the buttons array to the components array in the message template
                    $messageTemplate['components'][] = [
                        'type' => 'BUTTONS',
                        'buttons' => $buttons
                    ];
                }
            }
            $components =  json_encode($messageTemplate);
            $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
            $apiUrl = self::GRAPH_API_BASE_URL . "{$template->template_id}";
            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $components);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $this->formatResponse(false, $err, 'client.templates.index', []);
            }
            $_response = json_decode($response);
            if (!empty($_response->error)) {
                $error_message = !empty($_response->error->error_user_msg) ?
                    $_response->error->error_user_msg :
                    $_response->error->message;
                return $this->formatResponse(
                    false,
                    $error_message,
                    'client.templates.create',
                    []
                );
            } else {
                $template->header_media = $media;
                $template->client_setting_id = $clientSetting->id;
                $template->update();
                $this->syncTemplateByID($template->id);
            }
            return $this->formatResponse(true, __('updated_successfully'), route('client.templates.index'), []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            \Log::info('Template Update Error', [$e->getMessage()]);

            return $this->formatResponse(false, $e->getMessage(), route('client.templates.index'), []);
        }
    }

    public function destroy($id)
    {
        try {
            $template = $this->model->withPermission()->find($id);
            $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
            $whatsappBusinessAccountId = getClientWhatsAppBusinessAcID(Auth::user()->client);
            $hsmId = $template->template_id;
            $templateName = $template->name;
            $apiUrl = self::GRAPH_API_BASE_URL . "{$whatsappBusinessAccountId}/message_templates?hsm_id={$hsmId}&name={$templateName}";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json'
                ),
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $_response = json_decode($response);
            if (isset($_response->error)) {
                $error_message = !empty($_response->error->error_user_msg) ?
                    $_response->error->error_user_msg :
                    $_response->error->message;
                if ($_response->error->code == "100") {
                    $this->model->withPermission()->where('id', $id)->delete();
                }
                return $this->formatResponse(
                    false,
                    $error_message,
                    'client.templates.create',
                    []
                );
            } else {
                $this->model->withPermission()->where('id', $id)->delete();
            }
            return $this->formatResponse(true, __('deleted_successfully'), 'client.templates.index', []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());            
            }

            return $this->formatResponse(false, $e->getMessage(), 'client.templates.index', []);
        }
    }

    public function syncTemplateByID($id)
    {
        try {
            $clientSetting = Auth::user()->client->whatsappSetting;
            $template = $this->model->withPermission()->find($id);
            $accessToken = getClientWhatsAppAccessToken(Auth::user()->client);
            $apiUrl = self::GRAPH_API_BASE_URL . "/{$template->template_id}";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $templateObject = json_decode($response);
            if (isset($templateObject) && isset($templateObject->error)) {
                $error_message = isset($templateObject->error->error_user_msg) ?
                    $templateObject->error->error_user_msg :
                    $templateObject->error->message;
                return $this->formatResponse(
                    false,
                    $error_message,
                    'client.templates.index',
                    []
                );
            }
            $template = $this->model->withPermission()->firstOrNew(['template_id' => $templateObject->id]);
            $template->fill([
                'name'          => $templateObject->name,
                'client_setting_id' => $clientSetting->id,
                'components'    => $templateObject->components ?? [],
                'category'      => $templateObject->category,
                'language'      => $templateObject->language,
                'client_id'     => Auth::user()->client->id,
                'status'        => $templateObject->status,
                'type'          => TypeEnum::WHATSAPP,
            ]);
            $template->save();
            return $this->formatResponse(
                true,
                __('template_sync_successfully'),
                'client.templates.index',
                []
            );
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());            
            }           
            \Log::info('Sync Template', [$e->getMessage()]);
 
            return $this->formatResponse(
                false,
                $e->getMessage(),
                'client.templates.index',
                []
            );
        }
    }

    public function getTemplateByID($id)
    {
        $row  = $this->find($id);
        $data = app(TemplateService::class)->execute($row);
        return view('backend.client.whatsapp.campaigns.partials.__template', $data)->render();
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return $this->model->find($id)->update($request);
    }

    public function loadTemplate()
    {
        
        try {
            $clientSetting                 = Auth::user()->client->whatsappSetting;
            $accessToken                   = getClientWhatsAppAccessToken(Auth::user()->client);
            $whatsapp_business_account_id  = getClientWhatsAppBusinessAcID(Auth::user()->client);
            $url                           = self::GRAPH_API_BASE_URL . "{$whatsapp_business_account_id}/message_templates";
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

                $template = $this->model->withPermission()->firstOrNew(['template_id' => $templateObject['id']]);
                $template->fill([
                    'name'          => $templateObject['name'],
                    'client_setting_id' => $clientSetting->id,
                    'components'    => $templateObject['components'] ?? [],
                    'category'      => $templateObject['category'],
                    'language'      => $templateObject['language'],
                    'client_id'     => Auth::user()->client->id,
                    'status'        => $templateObject['status'],
                    'type'          => TypeEnum::WHATSAPP,
                ]);

                $template->save();
            }
            $this->model->whereNotIn('template_id', collect($allData)->pluck('id'))->withPermission()->delete();
            return $this->formatResponse(true, __('updated_successfully'), 'client.templates.index', []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            \Log::info('Load Template', [$e->getMessage()]);
            return $this->formatResponse(false, $e->getMessage(), 'client.templates.index', []);
        }
    }

    public function whatsappTemplate()
    {
        return $this->model->withPermission()->active()->where('type', TypeEnum::WHATSAPP)->latest()->paginate();
    }
    public function activeWhatsappTemplate()
    {
        return $this->model->withPermission()->active()->where('type', TypeEnum::WHATSAPP)->latest()->get();
    }
}
