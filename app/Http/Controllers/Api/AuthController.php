<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Login attempt', ['email' => $request->email]);
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::info('Login failed: invalid credentials');
            return response()->json([
                'message' => 'Email atau password salah.',
                'status' => 'error'
            ], 401);
        }
        
        // Check if user has permission to access the app
        if (!$user->hasPermissionTo('access_app')) {
            Log::info('Login failed: no app access permission', ['user_id' => $user->id]);
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke aplikasi ini.',
                'status' => 'error'
            ], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $userData = $user->toArray();
        unset($userData['password']);
        unset($userData['remember_token']);
        
        // Add user roles to response
        $userData['roles'] = $user->roles->pluck('name');

        Log::info('Login successful', ['user_id' => $user->id, 'roles' => $user->roles->pluck('name')]);
        
        return response()->json([
            'user' => $userData,
            'token' => $token,
            'status' => 'success'
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function index()
    {
        $users = User::all()->makeHidden(['password', 'remember_token']);
        return response()->json($users);
    }

    public function changePassword(Request $request)
    {
        Log::info('Change password attempt', ['user_id' => $request->user()->id]);
        
        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ]);
        
        $user = $request->user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            Log::info('Change password failed: invalid current password');
            return response()->json([
                'message' => 'Password saat ini salah.',
                'status' => 'error'
            ], 401);
        }
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        Log::info('Password changed successfully', ['user_id' => $user->id]);
        
        return response()->json([
            'message' => 'Password berhasil diubah.',
            'status' => 'success'
        ], 200);
    }

    public function checkPermissions(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'permissions' => [],
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }
        
        $permissions = [
            'kocok_arisan' => $user->hasPermissionTo('kocok_arisan'),
            'manage_kocok_arisan' => $user->hasPermissionTo('manage_kocok_arisan'),
            'access_app' => $user->hasPermissionTo('access_app'),
        ];
        
        $roles = $user->roles->pluck('name')->toArray();
        
        return response()->json([
            'permissions' => $permissions,
            'roles' => $roles,
            'status' => 'success'
        ], 200);
    }
}
