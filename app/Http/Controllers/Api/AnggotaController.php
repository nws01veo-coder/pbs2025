<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AnggotaController extends Controller
{
    public function index()
    {
        try {
            $anggota = Anggota::with('jabatan', 'lokasi')->get();

            $formattedAnggota = $anggota->map(function ($item) {
                $imageUrl = null;
                if ($item->image) {
                    $filename = basename($item->image);
                    $imageUrl = url('api/anggota-images/' . $filename);
                }

                return [
                    'id' => $item->id,
                    'image_url' => $imageUrl,
                    'nama' => $item->name,
                    'alias' => $item->alias,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'status' => $item->status,
                    'jabatan' => $item->jabatan->name,
                    'lokasi' => $item->lokasi->name,
                    'no_telp' => $item->no_telp,
                    'alamat' => $item->alamat ?? '',
                    'aktif_arisan' => $item->aktif_arisan ?? true,
                ];
            });

            return response()->json([
                'data' => $formattedAnggota,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch anggota data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getImage($filename)
    {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            abort(400, 'Invalid filename');
        }

        $path = 'anggota-images/' . $filename;

        // Debug info
        Log::info('Fetching image', [
            'filename' => $filename,
            'path' => $path,
            'exists' => Storage::disk('local')->exists($path)
        ]);

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Image not found.');
        }

        $file = Storage::disk('local')->get($path);

        $fullPath = Storage::disk('local')->path($path);
        $type = mime_content_type($fullPath);

        $type = $type ?: 'application/octet-stream';

        return response($file)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function show($id)
    {
        try {
            $anggota = Anggota::with('jabatan', 'lokasi')->findOrFail($id);

            $imageUrl = null;
            if ($anggota->image) {
                $filename = basename($anggota->image);
                $imageUrl = url('api/anggota-images/' . $filename);
                // Log gambar yang dikirim
                Log::info('Sending anggota image', [
                    'id' => $id,
                    'image' => $anggota->image,
                    'filename' => $filename,
                    'url' => $imageUrl
                ]);
            }

            $formattedAnggota = [
                'id' => $anggota->id,
                'image_url' => $imageUrl,
                'nama' => $anggota->name,
                'alias' => $anggota->alias,
                'jenis_kelamin' => $anggota->jenis_kelamin,
                'status' => $anggota->status,
                'jabatan' => $anggota->jabatan->name,
                'lokasi' => $anggota->lokasi->name,
                'no_telp' => $anggota->no_telp,
                'alamat' => $anggota->alamat ?? '',
            ];

            return response()->json([
                'data' => $formattedAnggota,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch anggota data.',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function updateImage(Request $request, $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            
            // Validate request
            $request->validate([
                'image' => 'required|string',
                'image_name' => 'required|string'
            ]);
            
            $base64Image = $request->input('image');
            $imageName = $request->input('image_name');
            
            // Decode base64 image
            $imageData = base64_decode($base64Image);
            
            if ($imageData === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid base64 image data'
                ], 400);
            }
            
            // Create storage directory if not exists
            $storageDir = 'anggota-images';
            if (!Storage::disk('local')->exists($storageDir)) {
                Storage::disk('local')->makeDirectory($storageDir);
            }
            
            // Delete old image if exists
            if ($anggota->image) {
                $oldImagePath = 'anggota-images/' . basename($anggota->image);
                if (Storage::disk('local')->exists($oldImagePath)) {
                    Storage::disk('local')->delete($oldImagePath);
                    Log::info('Deleted old image: ' . $oldImagePath);
                }
            }
            
            // Save new image
            $imagePath = $storageDir . '/' . $imageName;
            Storage::disk('local')->put($imagePath, $imageData);
            
            // Update database
            $anggota->image = $imagePath;
            $anggota->save();
            
            // Generate new image URL
            $imageUrl = url('api/anggota-images/' . $imageName);
            
            Log::info('Updated anggota image', [
                'id' => $id,
                'image_path' => $imagePath,
                'image_url' => $imageUrl
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully',
                'data' => [
                    'image_url' => $imageUrl,
                    'image_path' => $imagePath
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to update anggota image', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $anggota->delete();

            return response()->json([
                'message' => 'Anggota deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete anggota.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
