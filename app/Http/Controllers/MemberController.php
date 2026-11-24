<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['category', 'level'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('membership_category_id', $request->category_id);
        }

        if ($request->filled('level_id')) {
            $query->where('membership_level_id', $request->level_id);
        }

        $members = $query->paginate(10);
        $categories = MembershipCategory::all();
        $levels = MembershipLevel::all();

        return view('members.index', compact('members', 'categories', 'levels'));
    }

    public function create()
    {
        $categories = MembershipCategory::all();
        $levels = MembershipLevel::all();

        return view('members.create', compact('categories', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'membership_category_id' => 'required|exists:membership_categories,id',
            'membership_level_id' => 'required|exists:membership_levels,id',
            'payment_amount' => 'required|numeric',
        ]);

        $level = MembershipLevel::findOrFail($validated['membership_level_id']);

        if ($validated['payment_amount'] < $level->fee) {
            return back()
                ->withErrors(['payment_amount' => 'Payment amount must be at least the level fee of ₱' . number_format($level->fee, 2)])
                ->withInput();
        }

        // Generate unique membership number (includes soft-deleted records)
        $latestMember = Member::withTrashed()->latest('id')->first();
        $nextId = $latestMember ? $latestMember->id + 1 : 1;

        do {
            $membershipNumber = 'MEM-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $nextId++;
        } while (Member::withTrashed()->where('membership_number', $membershipNumber)->exists());

        $validated['membership_number'] = $membershipNumber;
        $validated['status'] = 'Active';

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member registered successfully. Initial payment accepted.');
    }

    public function show(Member $member)
    {
        $member->load(['category', 'level', 'transactions.level', 'transactions.payments']);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $categories = MembershipCategory::all();
        $levels = MembershipLevel::all();

        return view('members.edit', compact('member', 'categories', 'levels'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'membership_category_id' => 'required|exists:membership_categories,id',
            'membership_level_id' => 'required|exists:membership_levels,id',
            'status' => 'required|in:Active,Inactive,Suspended',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }
}