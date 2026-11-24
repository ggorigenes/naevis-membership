<?php

namespace App\Http\Controllers;

use App\Models\MembershipCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = MembershipCategory::orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:membership_categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        MembershipCategory::create($validated);
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(MembershipCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, MembershipCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:membership_categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(MembershipCategory $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category because members are assigned to it.');
        }
    }
}