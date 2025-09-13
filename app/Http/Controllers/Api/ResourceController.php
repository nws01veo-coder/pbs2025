<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index(string $resource)
    {
        // Ubah nama resource menjadi format kelas yang benar
        $modelName = 'App\\Models\\' . Str::singular(Str::studly($resource));
        
        // Periksa apakah model ada
        if (!class_exists($modelName)) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        // Ambil semua data dari model
        $data = $modelName::all()->toArray();

        return response()->json([
            'data' => $data
        ]);
    }
}