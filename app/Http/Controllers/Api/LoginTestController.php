<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginTestController extends Controller
{
    public function login(Request $request)
    {
        // Log all request data for debugging
        Log::info('Login Test Request', [
            'all' => $request->all(),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);

        return response()->json([
            'message' => 'Login test endpoint works!',
            'received_data' => $request->all(),
            'status' => 'success'
        ], 200);
    }
}
