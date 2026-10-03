<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->toDateString()
        );


        /*
        |--------------------------------------------------------------------------
        | Base Invoice Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = Invoice::where('company_id', $companyId)
            ->whereBetween('issue_date', [
                $startDate,
                $endDate
            ]);


        /*
        |--------------------------------------------------------------------------
        | Invoice Statistics
        |--------------------------------------------------------------------------
        */

        $totalInvoices = (clone $baseQuery)->count();

        $draftInvoices = (clone $baseQuery)
            ->where('status', 'draft')
            ->count();

        $sentInvoices = (clone $baseQuery)
            ->where('status', 'sent')
            ->count();

        $partialInvoices = (clone $baseQuery)
            ->where('status', 'partial')
            ->count();

        $paidInvoices = (clone $baseQuery)
            ->where('status', 'paid')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Financial Statistics
        |--------------------------------------------------------------------------
        */

        $totalInvoiced = (clone $baseQuery)
            ->sum('total');

        /*
        | Total amount from fully paid invoices
        */

        $totalPaid = (clone $baseQuery)
            ->where('status', 'paid')
            ->sum('total');


        /*
        | Outstanding invoices
        |
        | Includes both:
        | - Sent invoices
        | - Partially paid invoices
        |
        | For partial invoices we calculate:
        |
        | Invoice Total - Payments Received
        */

        $outstandingInvoices = (clone $baseQuery)
            ->whereIn('status', ['sent', 'partial'])
            ->with('payments')
            ->get();

        $totalOutstanding = $outstandingInvoices->sum(function ($invoice) {
            return $invoice->balance_due;
        });


        /*
        |--------------------------------------------------------------------------
        | Overdue
        |--------------------------------------------------------------------------
        |
        | An invoice is overdue when:
        |
        | - It is sent OR partially paid
        | - Due date has passed
        | - It still has a balance
        |
        */

        $overdueInvoices = (clone $baseQuery)
            ->whereIn('status', ['sent', 'partial'])
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            )
            ->with('payments')
            ->get();

        $totalOverdue = $overdueInvoices->sum(function ($invoice) {
            return $invoice->balance_due;
        });


        /*
        |--------------------------------------------------------------------------
        | Client Statistics
        |--------------------------------------------------------------------------
        */

        $totalClients = Client::where(
            'company_id',
            $companyId
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Monthly Revenue
        |--------------------------------------------------------------------------
        |
        | SQLite-compatible month grouping.
        |
        */

        $monthlyRevenue = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'paid')
        ->whereBetween('issue_date', [
            $startDate,
            $endDate
        ])
        ->selectRaw(
            "strftime('%Y-%m', issue_date) as month,
             SUM(total) as total"
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Revenue Chart
        |--------------------------------------------------------------------------
        */

        $revenueChartLabels = $monthlyRevenue
            ->map(function ($item) {
                return \Carbon\Carbon::createFromFormat(
                    'Y-m',
                    $item->month
                )->format('M Y');
            })
            ->values();

        $revenueChartData = $monthlyRevenue
            ->pluck('total')
            ->map(fn ($total) => (float) $total)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Revenue By Client
        |--------------------------------------------------------------------------
        */

        $clientRevenue = Client::where(
            'company_id',
            $companyId
        )
        ->withSum([
            'invoices as paid_total' => function ($query) use (
                $startDate,
                $endDate
            ) {
                $query
                    ->where('status', 'paid')
                    ->whereBetween('issue_date', [
                        $startDate,
                        $endDate
                    ]);
            }
        ], 'total')
        ->orderByDesc('paid_total')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Reports View
        |--------------------------------------------------------------------------
        */

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalInvoices',
            'draftInvoices',
            'sentInvoices',
            'partialInvoices',
            'paidInvoices',
            'totalInvoiced',
            'totalPaid',
            'totalOutstanding',
            'totalOverdue',
            'totalClients',
            'monthlyRevenue',
            'clientRevenue',
            'revenueChartLabels',
            'revenueChartData'
        ));
    }
}