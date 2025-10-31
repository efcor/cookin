<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class ChatController extends Controller
{
    public function ask(Request $request, OpenAIService $openai)
    {
        return '';
        $prompt = $request->input('prompt', 'Hello, I am testing talking to you from a Laravel app via your API.');
        $answer = $openai->chat($prompt);

        return response()->json([
            'prompt' => $prompt,
            'response' => $answer
        ]);
    }
}
