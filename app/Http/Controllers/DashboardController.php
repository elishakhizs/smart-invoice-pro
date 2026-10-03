<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // Invoice statistics
        $totalInvoices = Invoice::where('company_id', $companyId)
            ->count();

        $paidInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->count();

        $outstandingInvoices = Invoice::where('company_id', $companyId)
            ->whereIn('status', ['sent', 'partial'])
            ->count();

        $overdueInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'sent')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();

        $draftInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'draft')
            ->count();

        // Financial statistics
        $totalRevenue = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->sum('total');

        $outstandingAmount = Invoice::where('company_id', $companyId)
            ->whereIn('status', ['sent', 'partial'])
            ->get()
            ->sum(function ($invoice) {
                return $invoice->balance_due;
            });

        $partialInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'partial')
            ->count();

        $overdueAmount = Invoice::where('company_id', $companyId)
            ->where('status', 'sent')
            ->whereDate('due_date', '<', now()->toDateString())
            ->sum('total');

        // Client statistics
        $totalClients = Client::where('company_id', $companyId)
            ->count();

        // Recent invoices
        $recentInvoices = Invoice::where('company_id', $companyId)
            ->with('client')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalInvoices',
            'paidInvoices',
            'outstandingInvoices',
            'overdueInvoices',
            'draftInvoices',
            'totalRevenue',
            'outstandingAmount',
            'overdueAmount',
            'totalClients',
            'recentInvoices',
            'partialInvoices',
        ));
    }
}