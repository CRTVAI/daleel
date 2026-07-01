<?php

namespace App\Http\Controllers;

use App\Models\ProTrial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpgradeController extends Controller
{
    /**
     * Show the checkout/upgrade page.
     */
    public function show()
    {
        $user = Auth::user();

        // Already Pro — redirect back
        if ($user->account_type === 'Pro') {
            return redirect()->route('user.dashboard')->with('info', 'You are already on the Pro Plan!');
        }

        return view('upgrade', compact('user'));
    }

    /**
     * Activate the 3-month free Pro trial.
     */
    public function activate(Request $request)
    {
        $user = Auth::user();

        // Already Pro — bail
        if ($user->account_type === 'Pro') {
            return redirect()->route('user.dashboard')->with('info', 'You are already on the Pro Plan!');
        }

        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $expiresAt = now()->addMonths(3);

        // Upgrade the user
        $user->update([
            'account_type'   => 'Pro',
            'pro_expires_at' => $expiresAt,
            'phone'          => $request->phone,
            'can_access_team' => true,
        ]);

        // Record the lead for admin
        ProTrial::create([
            'user_id'      => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'phone'        => $request->phone,
            'activated_at' => now(),
            'expires_at'   => $expiresAt,
            'status'       => 'active',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', '🎉 Welcome to Pro! Your 3-month free trial is now active. Enjoy unlimited access!');
    }
}
