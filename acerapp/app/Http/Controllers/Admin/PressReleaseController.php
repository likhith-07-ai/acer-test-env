<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\PressRelease;

class PressReleaseController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('press-releases.view')) {
            abort(403, 'Unauthorized actions.');
        }

        $pressReleases = PressRelease::latest()->paginate(10);
        return view('admin.press-releases.index', compact('pressReleases'));
    }

    public function create()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('press-releases.create')) {
            abort(403, 'Unauthorized actions.');
        }

        return view('admin.press-releases.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('press-releases.create')) {
            abort(403, 'Unauthorized actions.');
        }

        $validated = $request->validate([
            'headline' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'date' => 'required|date',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
            'format' => 'required|in:pdf,raw',
        ]);

        $data = $this->decodeJsonFields($request->all());

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $request->file('pdf_file')->store('press-releases/pdfs', 'public');
        }
        $pressRelease = PressRelease::create($data);

        return redirect()->route('admin.press-releases.index')->with('success', 'Press Release created successfully.');
    }

    public function edit(PressRelease $pressRelease)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('press-releases.edit')) {
            abort(403, 'Unauthorized actions.');
        }

        return view('admin.press-releases.edit', compact('pressRelease'));
    }

    public function update(Request $request, PressRelease $pressRelease)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('press-releases.edit')) {
            abort(403, 'Unauthorized actions.');
        }

        $validated = $request->validate([
            'headline' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'date' => 'required|date',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
            'format' => 'required|in:pdf,raw',
        ]);

        $data = $this->decodeJsonFields($request->all());

        if ($request->hasFile('pdf_file')) {
            // Delete old file if it exists
            if ($pressRelease->pdf_file) {
                Storage::disk('public')->delete($pressRelease->pdf_file);
            }
            $data['pdf_file'] = $request->file('pdf_file')->store('press-releases/pdfs', 'public');
        }
        $pressRelease->update($data);

        return redirect()->route('admin.press-releases.index')->with('success', 'Press Release updated successfully.');
    }

    public function destroy(PressRelease $pressRelease)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('press-releases.delete')) {
            abort(403, 'Unauthorized actions.');
        }

        if ($pressRelease->pdf_file) {
            Storage::disk('public')->delete($pressRelease->pdf_file);
        }
        $pressRelease->delete();
        return redirect()->route('admin.press-releases.index')->with('success', 'Press Release deleted successfully.');
    }

    private function decodeJsonFields(array $data)
    {
        $jsonFields = [
            'rating_action_table',
            'strengths',
            'weaknesses',
            'positive_sensitivities',
            'negative_sensitivities',
            'company_segments_table',
            'fy_columns',
            'annexure_1_rating_history',
            'annexure_1_1_complexity',
            'annexure_2_instruments',
            'annexure_3_lenders',
            'analytical_contacts',
            'ann6_entities_consolidated',
            'applicable_criteria'
        ];

        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $decoded = json_decode($data[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$field] = $decoded;
                }
            }
        }

        return $data;
    }
}