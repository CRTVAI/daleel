<?php

namespace App\Http\Controllers;

use App\Models\AiPrompt;
use Illuminate\Http\Request;

class AdminPromptController extends Controller
{
    public function index()
    {
        $prompts = AiPrompt::orderBy('title')->get();
        return view('admin.prompts.index', compact('prompts'));
    }

    public function update(Request $request, AiPrompt $prompt)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt->update([
            'prompt' => $request->prompt,
        ]);

        return redirect()->route('admin.prompts.index')->with('success', 'AI Prompt updated successfully.');
    }
}
