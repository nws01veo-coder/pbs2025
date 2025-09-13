<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        try {
            $galleries = Gallery::with('lokasi')->get();

            $formattedGalleries = $galleries->map(function ($item) {
                $imageUrl = null;
                if ($item->link) {
                    if (str_starts_with($item->link, 'http')) {
                        // External link like Google Drive
                        $imageUrl = $item->link;
                    } else {
                        // Local filename
                        $imageUrl = url('api/gallery-images/' . $item->link);
                    }
                }

                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'link' => $item->link,
                    'image_url' => $imageUrl,
                    'jenis' => $item->jenis,
                    'deskripsi' => $item->deskripsi,
                    'lokasi' => $item->lokasi ? $item->lokasi->name : null,
                ];
            });

            return response()->json([
                'data' => $formattedGalleries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch gallery data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'link' => 'nullable|string',
                'jenis' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'lokasi_id' => 'nullable|exists:locations,id',
            ]);

            $gallery = Gallery::create($request->all());

            return response()->json([
                'message' => 'Gallery created successfully.',
                'data' => $gallery,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create gallery.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $gallery = Gallery::with('lokasi')->findOrFail($id);

            $imageUrl = null;
            if ($gallery->link) {
                if (str_starts_with($gallery->link, 'http')) {
                    // External link like Google Drive
                    $imageUrl = $gallery->link;
                } else {
                    // Local filename
                    $imageUrl = url('api/gallery-images/' . $gallery->link);
                }
            }

            return response()->json([
                'data' => [
                    'id' => $gallery->id,
                    'nama' => $gallery->nama,
                    'link' => $gallery->link,
                    'image_url' => $imageUrl,
                    'jenis' => $gallery->jenis,
                    'deskripsi' => $gallery->deskripsi,
                    'lokasi' => $gallery->lokasi ? $gallery->lokasi->name : null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gallery not found.',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            $request->validate([
                'nama' => 'required|string|max:255',
                'link' => 'nullable|string',
                'jenis' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'lokasi_id' => 'nullable|exists:locations,id',
            ]);

            $gallery->update($request->all());

            return response()->json([
                'message' => 'Gallery updated successfully.',
                'data' => $gallery,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update gallery.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $gallery->delete();

            return response()->json([
                'message' => 'Gallery deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete gallery.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getImage($filename)
    {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            abort(400, 'Invalid filename');
        }

        $path = 'gallery-images/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Image not found.');
        }

        $file = Storage::disk('local')->get($path);

        $fullPath = Storage::disk('local')->path($path);
        $type = mime_content_type($fullPath);

        $type = $type ?: 'application/octet-stream';

        return response($file)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
