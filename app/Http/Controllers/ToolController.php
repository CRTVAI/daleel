<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $tools = Tool::orderBy('name')->paginate(12);
        return view('admin.tools.index', compact('tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tools,name',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $logoPath = '/images/tools/default.svg';

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tools', 'public');
            $logoPath = '/storage/' . $path;
        }

        Tool::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logoPath,
            'status' => 'active',
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Connected tool added successfully.');
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tools,name,' . $tool->id,
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo if it's not a default one
            if ($tool->logo && !str_starts_with($tool->logo, '/images/tools/')) {
                $oldPath = str_replace('/storage/', '', $tool->logo);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('logo')->store('tools', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $tool->update($data);

        return redirect()->route('admin.tools.index')->with('success', 'Connected tool updated successfully.');
    }

    public function destroy(Tool $tool)
    {
        // Delete logo if it's not a default one
        if ($tool->logo && !str_starts_with($tool->logo, '/images/tools/')) {
            $oldPath = str_replace('/storage/', '', $tool->logo);
            Storage::disk('public')->delete($oldPath);
        }

        $tool->delete();

        return redirect()->route('admin.tools.index')->with('success', 'Connected tool deleted successfully.');
    }
}
