<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    protected $dataFile = 'data.json';

    protected function getData()
    {
        if (!Storage::exists($this->dataFile)) {
            return [];
        }
        return json_decode(Storage::get($this->dataFile), true);
    }

    protected function saveData($data)
    {
        Storage::put($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }
} 