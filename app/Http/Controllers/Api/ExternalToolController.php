<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;

class ExternalToolController extends Controller
{
    public function index(Request $request)
    {
        // Security Check
        $apiKey = env('OPENAI_API_KEY', 'default_secret_key_change_me');
        if ($request->header('X-API-Key') !== $apiKey) {
            return response()->json(['error' => 'Unauthorized request.'], 401);
        }

        $tools = Tool::where('status', 'active')->select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'tools' => $tools
        ]);
    }

    public function store(Request $request)
    {
        // Security Check
        $apiKey = env('OPENAI_API_KEY', 'default_secret_key_change_me');
        if ($request->header('X-API-Key') !== $apiKey) {
            return response()->json(['error' => 'Unauthorized request.'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
            'logo_base64' => 'nullable|string',
        ]);

        $data = [
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'active'
        ];

        // Handle Logo (Base64 to Storage)
        if ($request->filled('logo_base64')) {
            try {
                $imageData = $request->logo_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    $imageData = base64_decode($imageData);

                    if ($imageData !== false) {
                        $fileName = 'tool_logos/' . \Illuminate\Support\Str::slug($validated['name']) . '-' . time() . '.' . $type;
                        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
                        \Illuminate\Support\Facades\Storage::disk($disk)->put($fileName, $imageData, 'public');
                        $data['logo'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($fileName);
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('External Tool Logo Upload Failed: ' . $e->getMessage());
            }
        }

        $tool = Tool::updateOrCreate(
            ['name' => $validated['name']],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Tool synchronized successfully.',
            'tool_id' => $tool->id
        ], 201);
    }
}
