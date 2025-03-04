<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

class WhatsAppService
{
    private $request;

    public function execute($request)
    {
    }

    public function parseTextHeaderVariables($item)
    {
        $variables = [];
        preg_match_all('/{{(\d+)}}/', $item['text'], $matches);
        if (! empty($matches[1])) {
            foreach ($matches[1] as $id) {
                $exampleValue = '';
                try {
                    $exampleValue = $item['example']['header_text'][$id - 1];
                } catch (\Throwable $e) {
                    // Handle the exception if necessary
                }
                $variables[]  = [
                    'id'           => $id,
                    'exampleValue' => $exampleValue,
                ];
            }
        }

        return $variables;
    }

    public function parseBodyVariables($item)
    {
        $variables = [];
        preg_match_all('/{{(\d+)}}/', $item['text'], $matches);
        if (! empty($matches[1])) {
            foreach ($matches[1] as $id) {
                $exampleValue = '';
                try {
                    $exampleValue = $item['example']['body_text'][0][$id - 1];
                } catch (\Throwable $e) {
                    // Handle the exception if necessary
                }
                $variables[]  = [
                    'id'           => $id,
                    'exampleValue' => $exampleValue,
                ];
            }
        }

        return $variables;
    }

    public function parseButtonVariables($item)
    {
        $variables = [];
        foreach ($item['buttons'] as $key => $button) {
            if ($button['type'] == 'URL') {
                preg_match_all('/{{(\d+)}}/', $button['url'], $matches);
                if (! empty($matches[1])) {
                    foreach ($matches[1] as $id) {
                        $exampleValue         = '';
                        try {
                            // Replace the placeholder with an empty string in the URL
                            $exampleValue = str_replace('{{'.$id.'}}', '', $button['url']);
                        } catch (\Throwable $e) {
                        }
                        $variables[$id - 1][] = [
                            'id'           => $id,
                            'exampleValue' => $exampleValue,
                            'type'         => $button['type'],
                            'text'         => $button['text'],
                        ];
                    }
                }
            } elseif ($button['type'] == 'COPY_CODE') {
                $exampleValue = $button['example'][0];
                $variables[]  = [
                    ['id'              => $key,
                        'exampleValue' => $exampleValue,
                        'type'         => $button['type'],
                        'text'         => $button['text']],
                ];
            }
        }

        return $variables;
    }

    public function extractCountryCode($phone)
    {
        $phoneNumber = $phone;
        if (strpos($phone, '+') !== 0) {
            $phoneNumber = '+' . $phone;
        }
        $prefixes = Country::pluck('id','phonecode');
        if (preg_match('/^\+(\d{1})/', $phoneNumber, $matches)) {
            $prefix = $matches[1];
            if (isset($prefixes[$prefix])) {
                return $prefixes[$prefix];
            }else if (preg_match('/^\+(\d{2})/', $phoneNumber, $matches)) {
                $prefix = $matches[1];
                if (isset($prefixes[$prefix])) {
                    return $prefixes[$prefix];
                }else if (preg_match('/^\+(\d{3})/', $phoneNumber, $matches)) {
                $prefix = $matches[1];
                if (isset($prefixes[$prefix])) {
                    return $prefixes[$prefix];
                }
            }
            }
        }

        return null;

    }
}
