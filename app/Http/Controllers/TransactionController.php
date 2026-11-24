<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use App\Notifications\MembershipTransactionSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = MembershipTransaction::with(['member', 'level', 'payments'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $members = Member::orderBy('last_name')->get();
        $levels = MembershipLevel::orderBy('fee')->get();
        return view('transactions.create', compact('members', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'membership_level_id' => 'required|exists:membership_levels,id',
            'transaction_type' => 'required|in:Application,Renewal',
        ]);

        $level = MembershipLevel::findOrFail($validated['membership_level_id']);

        $transaction = DB::transaction(function () use ($validated, $level) {
            $transaction = MembershipTransaction::create([
                'reference_number' => 'TXN-' . date('Y') . '-' . Str::upper(Str::random(6)),
                'member_id' => $validated['member_id'],
                'membership_level_id' => $level->id,
                'transaction_type' => $validated['transaction_type'],
                'amount' => $level->fee,
                'status' => 'Pending',
            ]);

            Notification::route('mail', $transaction->member->email)
                ->notify(new MembershipTransactionSubmitted($transaction));

            $transaction->update(['verification_sent_at' => now()]);

            return $transaction;
        });

        return redirect()->route('transactions.index')
            ->with('success', "Transaction {$transaction->reference_number} created. Verification email queued.");
    }

    public function updateStatus(Request $request, MembershipTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:Approved,Expired,Cancelled',
        ]);

        $newStatus = $request->status;

        // Functional Requirement: Only paid transactions may be marked as approved
        if ($newStatus === 'Approved' && $transaction->status !== 'Paid') {
            return redirect()->back()->with('error', 'Only PAID transactions can be marked as Approved.');
        }

        if ($newStatus === 'Approved') {
            $startDate = now();
            $endDate = now()->addMonths($transaction->level->validity_period);

            $transaction->update([
                'status' => 'Approved',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // Sync member's active level and status
            $transaction->member->update([
                'membership_level_id' => $transaction->membership_level_id,
                'status' => 'Active',
            ]);
        } else {
            $transaction->update(['status' => $newStatus]);
        }

        return redirect()->back()->with('success', "Transaction {$transaction->reference_number} marked as {$newStatus}.");
    }

    public function receipt(MembershipTransaction $transaction)
    {
        $transaction->load(['member.category', 'level', 'payments']);
        return view('transactions.receipt', compact('transaction'));
    }
}
