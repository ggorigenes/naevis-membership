<?php

namespace App\Http\Controllers;

use App\Models\MembershipLevel;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = MembershipLevel::orderBy('fee')->get();
        return view('levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:membership_levels,name',
            'fee' => 'required|numeric|min:0',
            'validity_period' => 'required|integer|min:1',
            'benefits' => 'nullable|string',
        ]);

        MembershipLevel::create($validated);
        return redirect()->route('levels.index')->with('success', 'Membership level added successfully.');
    }

    public function edit(MembershipLevel $level)
    {
        return view('levels.edit', compact('level'));
    }

    public function update(Request $request, MembershipLevel $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:membership_levels,name,' . $level->id,
            'fee' => 'required|numeric|min:0',
            'validity_period' => 'required|integer|min:1',
            'benefits' => 'nullable|string',
        ]);

        $level->update($validated);
        return redirect()->route('levels.index')->with('success', 'Membership level updated successfully.');
    }

    public function destroy(MembershipLevel $level)
    {
        try {
            $level->delete();
            return redirect()->route('levels.index')->with('success', 'Membership level deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('levels.index')->with('error', 'Cannot delete level because members or transactions are linked to it.');
        }
    }
}