<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Store the user's onboarding preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'interests' => 'required|array',
            'goal' => 'required|string',
            'level' => 'required|string',
            'connections' => 'array'
        ]);

        $user = Auth::user();

        $user->update([
            'interests' => $request->interests,
            'learning_goal' => $request->goal,
            'experience_level' => $request->level,
            'connections' => $request->connections,
        ]);

        // Generate automatic roadmap immediately using the onboarding answers
        try {
            $roadmapController = new \App\Http\Controllers\RoadmapController();
            $roadmapController->generateAutoOnboardingRoadmap($user);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Auto Onboarding Roadmap Generation Failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.'
        ]);
    }
}
