<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;

class DbController extends Controller
{
    public function index()
    {
        $records = ApiLog::all();

        foreach ($records as $record) {
            echo $record->toJson()."\n\n";
        }

        return '';
    }

    public function index2()
    {
        $records = ApiLog::all();

        foreach ($records as $record) {
            echo $record->id."<br>";
            echo $record->request_body."<br>";
            echo $record->response_message."<br>";
            echo "<br><br>";
        }

        return '';
    }
}
