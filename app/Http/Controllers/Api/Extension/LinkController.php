<?php

namespace App\Http\Controllers\Api\Extension;

use App\Http\Controllers\Controller;
use App\Models\ExtensionDevice;
use App\Models\ExtensionVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LinkController extends Controller
{
    /**
     * Link the extension using a verification code.
     */
    public function link(Request $request)
    {
        if (!$request->device_id) {
            return response()->json(['message' => 'Extension did not provide a Device ID. Please refresh extension.'], 422);
        }

        $request->validate([
            'verification_code' => 'required|string',
            'device_id' => 'required|string',
            'device_name' => 'nullable|string',
            'extension_name' => 'nullable|string',
            'extension_version' => 'nullable|string',
        ]);

        $user = null;

        // 1. Check if it's an Employee permanent code
        $employeeUser = User::where('connection_code', $request->verification_code)
            ->where('is_employee', true)
            ->first();

        if ($employeeUser) {
            $user = $employeeUser;
        } else {
            // 2. Check if it's a temporary verification code (Individual)
            $codeHash = hash('sha256', $request->verification_code);

            $verificationCode = ExtensionVerificationCode::where('code_hash', $codeHash)
                ->where('expires_at', '>', now())
                ->first();

            if (!$verificationCode) {
                return response()->json(['message' => 'Invalid or expired connection code.'], 422);
            }

            $user = $verificationCode->user;
        }

        // Create or update the device
        $device = ExtensionDevice::updateOrCreate(
            ['user_id' => $user->id, 'device_id' => $request->device_id],
            [
                'device_name' => $request->device_name,
                'extension_name' => $request->extension_name,
                'extension_version' => $request->extension_version,
                'last_active_at' => now(),
                'revoked_at' => null,
            ]
        );

        // Temporary individual verification codes are single-use.
        // Employee connection codes are also rotated after each successful link
        // so the same code can NEVER be reused to link a second unauthorised device.
        if (!$employeeUser) {
            $verificationCode->delete();
        } else {
            // Rotate the employee's connection code so it can't be reused
            $newCode = $this->generateEmployeeCode();
            $user->update([
                'connection_code'           => $newCode,
                'connection_code_issued_at' => now(),
            ]);
        }

        // Generate token
        $token = $user->createToken($request->device_name ?? 'Extension', [
            'extension:profile.read',
            'extension:activity.write',
            'extension:recommendations.read',
            'extension:feedback.write',
        ]);

        return response()->json([
            'data' => [
                'link_id' => $device->id,
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => null,
                'linked_at' => $device->created_at->toIso8601String(),
                'student' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]
        ]);
    }

    /**
     * Unlink the extension.
     */
    public function unlink(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'link_id' => 'required|string',
        ]);

        $user = $request->user();
        
        $device = ExtensionDevice::where('user_id', $user->id)
            ->where('id', $request->link_id)
            ->where('device_id', $request->device_id)
            ->first();

        if ($device) {
            $device->update(['revoked_at' => now()]);
            // Revoke current token
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'data' => [
                'unlinked' => true
            ]
        ]);
    }

    /**
     * Generate a cryptographically unique employee code.
     * Used both here (on post-link rotation) and by TeamController.
     */
    private function generateEmployeeCode(): string
    {
        do {
            $code = 'EMP-' . strtoupper(\Illuminate\Support\Str::random(4)) . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        } while (\App\Models\User::where('connection_code', $code)->exists());

        return $code;
    }
}
