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
}
