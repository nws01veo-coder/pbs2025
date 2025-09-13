<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Test endpoint untuk cek anggota yang aktif arisan
Route::get('/test-anggota-aktif-arisan', function () {
    try {
        $allMembers = DB::table('anggotas')
            ->select('id', 'name', 'status', 'aktif_arisan')
            ->get();
            
        $activeArisanMembers = DB::table('anggotas')
            ->where('status', 'aktif')
            ->where('aktif_arisan', true)
            ->select('id', 'name', 'status', 'aktif_arisan')
            ->get();
            
        return response()->json([
            'success' => true,
            'all_members' => $allMembers,
            'active_arisan_members' => $activeArisanMembers,
            'count_all' => $allMembers->count(),
            'count_active_arisan' => $activeArisanMembers->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});
