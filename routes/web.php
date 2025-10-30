<?php

use App\Models\ApiLog;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ApiLog::create(['request_body' => 'the request body', 'response_status' => 200, 'response_payload' => 'Here is the answer']);
    // ApiLog::create(['request_body' => 'the request body2', 'response_status' => 200, 'response_payload' => 'Here is the answer2']);
    // ApiLog::create(['request_body' => 'the request body3', 'response_status' => 200, 'response_payload' => 'Here is the answer3']);

    return ApiLog::all();
});
