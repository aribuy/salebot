<?php
namespace App\Services;

use App\Models\Language;

class TemplateService
{

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    private $data = [];

    public function execute($row)
    {
        $header = null;
        $body = null;
        $footer = null;
        $buttons = null;
        $locales =  Language::pluck('name','locale');
        $variables = [];
            $components = $row->components;
            $variables = [
                'header'   => [],
                'body'     => [],
                'buttons'  => [],
            ];

            foreach ($components as $item) {
                switch ($item['type']) {
                    case 'HEADER':
                        switch ($item['format']) {
                            case 'TEXT':
                                $headerVariables       = $this->whatsappService->parseTextHeaderVariables($item);
                                $variables['header']   = array_merge($variables['header'], $headerVariables);
                                $header = $item;
                                break;
                            case 'DOCUMENT':
                                $variables['document'] = true;
                                $header = $item;
                                break;
                            case 'IMAGE':
                                $variables['image']    = true;
                                $data['HEADER'] = $item;
                                $header = $item;
                                break;
                            case 'VIDEO':
                                $variables['video']    = true;
                                $header = $item;
                                break;
                        }
                        break;
                    case 'BODY':
                        $bodyVariables        = $this->whatsappService->parseBodyVariables($item);
                        $variables['body']    = array_merge($variables['body'], $bodyVariables);
                        $body = $item;
                        break;
                    case 'BUTTONS':
                        $buttons = $item['buttons'];
                        break;
                    case 'FOOTER':
                        $footer = $item;
                        break;
                }
            }
            $data      = [
                'row'   => $row,
                'variables'  => $variables,
                "header" => $header,
                "body" => $body,
                "footer" => $footer,
                "buttons" => $buttons,
                "locales" => $locales,
            ];
        return $data;
    }

}
