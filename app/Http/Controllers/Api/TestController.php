<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return response()->json([
            'message' => 'API is working!',
            'time' => now()->toDateTimeString(),
            'status' => 'success'
        ]);
    }
}
