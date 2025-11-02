<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class TextController extends Controller
{
    public function index()
    {
        Log::info('TextController index method was called.');
        logger('TextController accessed');
        return view('text');
    }

    public function foo()
    {
        Log::info('TextController index method was called.');
        logger('TextController accessed');
        return 'foo!';
    }
}
