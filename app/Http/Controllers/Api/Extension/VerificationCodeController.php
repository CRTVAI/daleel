<?php

namespace App\Http\Controllers\Api\Extension;

use App\Http\Controllers\Controller;
use App\Models\ExtensionVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class VerificationCodeController extends Controller
{
    /**
     * Create a new verification code for the logged-in user.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Enforce Free Plan 1 device limit
        if ($user->account_type === 'Free Plan') {
            $activeDevicesCount = \App\Models\ExtensionDevice::where('user_id', $user->id)
                ->whereNull('revoked_at')
                ->count();
            if ($activeDevicesCount >= 1) {
                return response()->json([
                    'message' => 'Free Plan limit reached! You can only connect 1 device. Please revoke your existing device connection before generating a new key.'
                ], 422);
            }
        }

        // Invalidate older unused codes for the same user
        ExtensionVerificationCode::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->delete();

        // Generate a 6-digit random code
        $plainCode = 'DALEEL-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $code = ExtensionVerificationCode::create([
            'user_id' => $user->id,
            'code_hash' => hash('sha256', $plainCode),
            'expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'data' => [
                'verification_code' => $plainCode,
                'expires_at' => $code->expires_at->toIso8601String(),
                'expires_in_seconds' => 600,
            ]
        ]);
    }
}
