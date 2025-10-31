<?php

namespace App\Services;

use App\Models\ApiLog;
use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function chat($prompt)
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => $prompt],
        ];

        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-mini', // or 'gpt-4o', 'gpt-3.5-turbo'
            'messages' => $messages,
        ]);

        $responseMessage = $response->choices[0]->message->content;

        ApiLog::create(['request_body' => json_encode($messages), 'response_message' => $responseMessage]);

        return $responseMessage;
    }
}
