<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        try {
            // Ambil instance panel default Filament.
            $panel = Filament::getDefaultPanel();

            // Dapatkan semua grup navigasi dari panel.
            $navigation = collect($panel->getNavigation())
                ->map(function ($group) {
                return [
                    'group' => $group->getLabel(),
                    'items' => collect($group->getItems())
                        ->map(function ($item) {
                            return [
                                'label' => $item->getLabel(),
                                'icon' => $item->getIcon(),
                                'url' => $item->getUrl(), // Jika ada method getUrl()
                                'children' => method_exists($item, 'getItems')
                                    ? collect($item->getItems())->map(function ($child) {
                                        return [
                                            'label' => $child->getLabel(),
                                            'icon' => $child->getIcon(),
                                            'url' => $child->getUrl(),
                                        ];
                                    })->toArray()
                                    : [],
                            ];
                        })->toArray(),
                ];
            })->toArray();
            
            return response()->json([
                'data' => $navigation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load navigation',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}