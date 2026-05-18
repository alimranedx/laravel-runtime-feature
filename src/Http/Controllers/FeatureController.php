<?php

namespace Imran\RuntimeFeatureToggle\Http\Controllers;

use Imran\RuntimeFeatureToggle\Models\Feature;
use Imran\RuntimeFeatureToggle\Facades\Feature as FeatureFacade;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::with('rules')->get();
        return view('feature::index', compact('features'));
    }

    public function create()
    {
        return view('feature::form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:rtf_features,key',
            'enabled' => 'boolean',
            'value' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && is_null(json_decode($value))) {
                        $fail('The ' . $attribute . ' must be a valid JSON string.');
                    }
                },
            ],
        ]);

        $data = $request->only('key', 'enabled');
        $data['value'] = $request->filled('value') ? json_decode($request->value, true) : null;

        Feature::create($data);

        FeatureFacade::clearCache();

        return redirect()->route('features.index')->with('success', 'Feature created successfully.');
    }

    public function edit(Feature $feature)
    {
        return view('feature::form', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'key' => 'required|unique:rtf_features,key,' . $feature->id,
            'enabled' => 'boolean',
            'value' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && is_null(json_decode($value))) {
                        $fail('The ' . $attribute . ' must be a valid JSON string.');
                    }
                },
            ],
        ]);

        $data = $request->only('key', 'enabled');
        $data['value'] = $request->filled('value') ? json_decode($request->value, true) : null;

        $feature->update($data);

        FeatureFacade::clearCache($feature->key);

        return redirect()->route('features.index')->with('success', 'Feature updated successfully.');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();
        FeatureFacade::clearCache($feature->key);

        return redirect()->route('features.index')->with('success', 'Feature deleted successfully.');
    }

    public function toggle(Feature $feature)
    {
        $feature->update(['enabled' => !$feature->enabled]);
        FeatureFacade::clearCache($feature->key);

        return back()->with('success', 'Feature status toggled.');
    }
}
