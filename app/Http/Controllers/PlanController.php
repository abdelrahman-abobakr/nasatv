<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of plans.
     */
    public function index()
    {
        $plans = Plan::latest()->paginate(15);
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'channels' => ['nullable', 'integer', 'min:0'],
            'movies' => ['nullable', 'integer', 'min:0'],
            'series' => ['nullable', 'integer', 'min:0'],
            'extras' => ['nullable', 'array'],
            'extras.*' => ['nullable', 'string', 'max:255'],
            'max_resolution' => ['nullable', 'string', 'max:255'],
            'simultaneous_devices' => ['nullable', 'integer', 'min:1'],
            'match_recording' => ['nullable', 'boolean'],
            'multi_audio_subtitles' => ['nullable', 'boolean'],
            'support_24_7' => ['nullable', 'boolean'],
            'instant_activation' => ['nullable', 'boolean'],
        ]);

        // Handle boolean fields which are not sent if unchecked
        $booleanFields = ['match_recording', 'multi_audio_subtitles', 'support_24_7', 'instant_activation'];
        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field);
        }

        Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'channels' => ['nullable', 'integer', 'min:0'],
            'movies' => ['nullable', 'integer', 'min:0'],
            'series' => ['nullable', 'integer', 'min:0'],
            'extras' => ['nullable', 'array'],
            'extras.*' => ['nullable', 'string', 'max:255'],
            'max_resolution' => ['nullable', 'string', 'max:255'],
            'simultaneous_devices' => ['nullable', 'integer', 'min:1'],
            'match_recording' => ['nullable', 'boolean'],
            'multi_audio_subtitles' => ['nullable', 'boolean'],
            'support_24_7' => ['nullable', 'boolean'],
            'instant_activation' => ['nullable', 'boolean'],
        ]);

        // Handle boolean fields
        $booleanFields = ['match_recording', 'multi_audio_subtitles', 'support_24_7', 'instant_activation'];
        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field);
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
