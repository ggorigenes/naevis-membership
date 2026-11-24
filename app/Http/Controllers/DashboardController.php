<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipTransaction;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'Active')->count();
        $pendingTransactions = MembershipTransaction::where('status', 'Pending')->count();
        $collectedPayments = Payment::sum('amount_paid');

        $recentTransactions = MembershipTransaction::with(['member', 'level'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalMembers',
            'activeMembers',
            'pendingTransactions',
            'collectedPayments',
            'recentTransactions'
        ));
    }
}