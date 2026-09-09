<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('details.product', 'user')->latest();

        // Filter Rentang Tanggal / Harian / Bulanan
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->get();
        $totalRevenue = $transactions->sum('total_amount');

        return view('reports.index', compact('transactions', 'totalRevenue'));
    }
}