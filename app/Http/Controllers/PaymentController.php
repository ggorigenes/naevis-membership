<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['transaction.member', 'transaction.level'])->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $pendingTransactions = MembershipTransaction::with(['member', 'level'])
            ->where('status', 'Pending')
            ->latest()
            ->get();

        $selectedTransactionId = $request->query('transaction_id');

        return view('payments.create', compact('pendingTransactions', 'selectedTransactionId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'membership_transaction_id' => 'required|exists:membership_transactions,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:Cash,GCash,Bank Transfer,Card',
        ]);

        $transaction = MembershipTransaction::findOrFail($validated['membership_transaction_id']);

        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'This transaction is no longer pending payment.');
        }

        if ($validated['amount_paid'] < $transaction->amount) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['amount_paid' => 'Amount paid must be at least ₱' . number_format($transaction->amount, 2)]);
        }

        DB::transaction(function () use ($validated, $transaction) {
            Payment::create([
                'membership_transaction_id' => $transaction->id,
                'payment_reference' => 'PAY-' . date('Y') . '-' . Str::upper(Str::random(6)),
                'amount_paid' => $validated['amount_paid'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => now(),
            ]);

            $transaction->update(['status' => 'Paid']);
        });

        return redirect()->route('transactions.index')
            ->with('success', "Payment recorded for {$transaction->reference_number}. Transaction is now Paid and ready for Approval.");
    }
}