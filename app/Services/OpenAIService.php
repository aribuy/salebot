<?php

namespace App\Services;
use Orhanerday\OpenAi\OpenAi;

class OpenAIService
{
    protected $whatsappService;
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    private $data = [];

    public function execute($row)
    {
        // Extract parameters from the row array
        $open_ai_key = $row['open_ai_key'];
        $prompt = $row['prompt'] ?? '';
        $model = $row['model'] ?? 'gpt-3.5-turbo'; // Default model
    
        // Initialize OpenAI client
        $open_ai = new OpenAi($open_ai_key);
    
        try {
            // Make the API request
            $response = $open_ai->completion([
                'model'             => $model,
                'prompt'            => $prompt,
                'temperature'       => 0.7,
                'max_tokens'        => 150,
                'frequency_penalty' => 0.5,
                'presence_penalty'  => 0.5,
                'n'                 => 1,
            ]);
    
            // Decode the response
            $decodedResponse = json_decode($response);
    
            // Handle API errors
            if (isset($decodedResponse->error)) {
                \Log::error('OpenAI Error: ' . $decodedResponse->error->message);
                return ['error' => $decodedResponse->error->message];
            }
    
            // Process response choices
            if (!empty($decodedResponse->choices)) {
                $text = implode("\n", array_map(fn($choice) => $choice->text, $decodedResponse->choices));
                return [
                    'content' => nl2br(htmlspecialchars(trim($text))),
                    'success' => true,
                ];
            } else {
                Log::warning('No choices found in OpenAI response.');
                return ['error' => 'No choices found in the response.'];
            }
    
        } catch (\Exception $e) {
            // Handle unexpected errors
            \Log::error('Exception in OpenAI API call: ' . $e->getMessage());
            return ['error' => 'An unexpected error occurred.'];
        }
    }
    

}
