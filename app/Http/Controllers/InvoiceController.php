<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceSequence;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = Invoice::where('company_id', $companyId)
            ->with('client');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'invoice_number',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas('client', function ($clientQuery) use ($search) {

                    $clientQuery
                        ->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'company_name',
                            'like',
                            '%' . $search . '%'
                        );

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            if ($request->status === 'overdue') {

                $query
                    ->whereIn('status', ['sent', 'partial'])
                    ->whereDate(
                        'due_date',
                        '<',
                        now()->toDateString()
                    );

            } else {

                $query->where(
                    'status',
                    $request->status
                );

            }
        }

        /*
        |--------------------------------------------------------------------------
        | Invoice Listing
        |--------------------------------------------------------------------------
        */

        $invoices = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Invoice Counters
        |--------------------------------------------------------------------------
        */

        $totalInvoices = Invoice::where(
            'company_id',
            $companyId
        )->count();

        $draftInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'draft')
        ->count();

        $sentInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'sent')
        ->count();

        $partialInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'partial')
        ->count();

        $paidInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'paid')
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Financial Totals
        |--------------------------------------------------------------------------
        */

        $totalInvoiced = Invoice::where(
            'company_id',
            $companyId
        )->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Total Paid
        |--------------------------------------------------------------------------
        */

        $paidInvoiceRecords = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'paid')
        ->with('payments')
        ->get();

        $totalPaid = $paidInvoiceRecords->sum(function ($invoice) {
            return $invoice->total_paid;
        });

        /*
        |--------------------------------------------------------------------------
        | Total Outstanding
        |--------------------------------------------------------------------------
        */

        $outstandingInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->whereIn('status', ['sent', 'partial'])
        ->with('payments')
        ->get();

        $totalOutstanding = $outstandingInvoices->sum(function ($invoice) {
            return $invoice->balance_due;
        });

        /*
        |--------------------------------------------------------------------------
        | Total Overdue
        |--------------------------------------------------------------------------
        */

        $overdueInvoices = Invoice::where(
            'company_id',
            $companyId
        )
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
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'invoices.index',
            compact(
                'invoices',
                'totalInvoices',
                'draftInvoices',
                'sentInvoices',
                'partialInvoices',
                'paidInvoices',
                'totalInvoiced',
                'totalPaid',
                'totalOutstanding',
                'totalOverdue'
            )
        );
    }


    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $clients = Client::where(
            'company_id',
            auth()->user()->company_id
        )
        ->orderBy('name')
        ->get();

        return view(
            'invoices.create',
            compact('clients')
        );
    }


    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'client_id' => [
                'required',
                'integer',
                'exists:clients,id'
            ],

            'issue_date' => [
                'required',
                'date'
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:issue_date'
            ],

            'status' => ['required','in:draft,sent'],

            'notes' => [
                'nullable',
                'string'
            ],

            'tax_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.description' => [
                'required',
                'string',
                'max:255'
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0'
            ],

        ]);

        $companyId = auth()->user()->company_id;

        /*
        |--------------------------------------------------------------------------
        | Make sure client belongs to company
        |--------------------------------------------------------------------------
        */

        $client = Client::where(
            'id',
            $validated['client_id']
        )
        ->where(
            'company_id',
            $companyId
        )
        ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Create Invoice
        |--------------------------------------------------------------------------
        */

        $invoice = DB::transaction(function () use (
            $validated,
            $companyId,
            $client
        ) {

            $invoiceNumber = $this->generateInvoiceNumber(
                $companyId
            );

            $invoice = Invoice::create([

                'company_id' => $companyId,

                'client_id' => $client->id,

                'invoice_number' => $invoiceNumber,

                'issue_date' => $validated['issue_date'],

                'due_date' => $validated['due_date'],

                'status' => $validated['status'],

                'notes' => $validated['notes'] ?? null,

                'subtotal' => 0,

                'tax' => 0,

                'total' => 0,

            ]);

            $subtotal = 0;

            foreach ($validated['items'] as $item) {

                $itemTotal =
                    $item['quantity'] *
                    $item['unit_price'];

                $invoice->items()->create([

                    'description' => $item['description'],

                    'quantity' => $item['quantity'],

                    'unit_price' => $item['unit_price'],

                    'total' => $itemTotal,

                ]);

                $subtotal += $itemTotal;
            }

            $tax =
                $subtotal *
                ($validated['tax_rate'] / 100);

            $total =
                $subtotal +
                $tax;

            $invoice->update([

                'subtotal' => $subtotal,

                'tax' => $tax,

                'total' => $total,

            ]);

            return $invoice;
        });

        return redirect()
            ->route(
                'invoices.show',
                $invoice
            )
            ->with(
                'success',
                'Invoice created successfully.'
            );
    }


    /**
     * Display a specific invoice.
     */
    public function show(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $invoice->load([
            'client',
            'items',
            'company',
            'payments'
        ]);

        return view(
            'invoices.show',
            compact('invoice')
        );
    }


    /**
     * Show invoice edit form.
     */
    public function edit(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $clients = Client::where(
            'company_id',
            auth()->user()->company_id
        )
        ->orderBy('name')
        ->get();

        $invoice->load('items');

        return view(
            'invoices.edit',
            compact(
                'invoice',
                'clients'
            )
        );
    }


    /**
     * Update invoice.
     */
    public function update(Request $request,Invoice $invoice) {
        $this->authorizeInvoice($invoice);

        $validated = $request->validate([

            'client_id' => [
                'required',
                'integer',
                'exists:clients,id'
            ],

            'issue_date' => [
                'required',
                'date'
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:issue_date'
            ],

            'status' => ['required','in:draft,sent'],

            'notes' => [
                'nullable',
                'string'
            ],

        ]);

        $client = Client::where(
            'id',
            $validated['client_id']
        )
        ->where(
            'company_id',
            auth()->user()->company_id
        )
        ->firstOrFail();

        $invoice->update([

            'client_id' => $client->id,

            'issue_date' => $validated['issue_date'],

            'due_date' => $validated['due_date'],

            'status' => $validated['status'],

            'notes' => $validated['notes'] ?? null,

        ]);

        return redirect()
            ->route(
                'invoices.show',
                $invoice
            )
            ->with(
                'success',
                'Invoice updated successfully.'
            );
    }


    /**
     * Delete invoice.
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice deleted successfully.'
            );
    }


    /**
     * Generate invoice number.
     */
    private function generateInvoiceNumber(
        int $companyId
    ): string {

        $year = now()->year;

        $sequence = InvoiceSequence::where(
            'company_id',
            $companyId
        )
        ->where(
            'year',
            $year
        )
        ->lockForUpdate()
        ->first();

        if (!$sequence) {

            $sequence = InvoiceSequence::create([

                'company_id' => $companyId,

                'year' => $year,

                'last_number' => 0,

            ]);
        }

        $sequence->increment(
            'last_number'
        );

        return sprintf(
            'INV-%d-%06d',
            $year,
            $sequence->last_number
        );
    }


    /**
     * Download invoice PDF.
     */
    public function pdf(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $invoice->load([
            'client',
            'items',
            'company',
            'payments'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'invoices.pdf',
            compact('invoice')
        );

        return $pdf->download(
            $invoice->invoice_number . '.pdf'
        );
    }


    /**
     * Invoice dashboard.
     */
    public function dashboard()
    {
        
        $companyId = auth()->user()->company_id;

        /*
        |--------------------------------------------------------------------------
        | Invoice Counts
        |--------------------------------------------------------------------------
        */

        $totalInvoices = Invoice::where(
            'company_id',
            $companyId
        )->count();

        $draftInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'draft')
        ->count();

        $sentInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'sent')
        ->count();

        $partialInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'partial')
        ->count();

        $paidInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'paid')
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Invoiced
        |--------------------------------------------------------------------------
        */

        $totalInvoiced = Invoice::where(
            'company_id',
            $companyId
        )->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Total Paid
        |--------------------------------------------------------------------------
        */

        $allInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->with('payments')
        ->get();

        $totalPaid = $allInvoices->sum(function ($invoice) {
            return $invoice->total_paid;
        });

        /*
        |--------------------------------------------------------------------------
        | Outstanding Balance
        |--------------------------------------------------------------------------
        */

        $outstandingInvoices = $allInvoices->filter(function ($invoice) {
            return in_array(
                $invoice->status,
                ['sent', 'partial']
            );
        });

        $totalOutstanding = $outstandingInvoices->sum(
            function ($invoice) {
                return $invoice->balance_due;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Overdue Invoices
        |--------------------------------------------------------------------------
        */

        $overdueInvoicesCollection = $allInvoices->filter(
            function ($invoice) {

                return in_array(
                    $invoice->status,
                    ['sent', 'partial']
                )
                && $invoice->due_date
                && $invoice->due_date->isPast()
                && $invoice->balance_due > 0;
            }
        );

        $overdueInvoices = $overdueInvoicesCollection->count();

        $overdueAmount = $overdueInvoicesCollection->sum(
            function ($invoice) {
                return $invoice->balance_due;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Recent Invoices
        |--------------------------------------------------------------------------
        */

        $recentInvoices = Invoice::where(
            'company_id',
            $companyId
        )
        ->with('client')
        ->latest()
        ->take(5)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Payments
        |--------------------------------------------------------------------------
        */

        $recentPayments = \App\Models\Payment::where(
            'company_id',
            $companyId
        )
        ->with([
            'invoice',
            'invoice.client'
        ])
        ->latest('payment_date')
        ->latest('id')
        ->take(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | upcomming payment
        |--------------------------------------------------------------------------
        */
        
        $upcomingInvoices = Invoice::where('company_id', $companyId)
        ->whereIn('status', ['sent', 'partial'])
        ->whereDate('due_date', '>=', now()->toDateString())
        ->whereDate('due_date', '<=', now()->addDays(30)->toDateString())
        ->with([
            'client',
            'payments'
        ])
        ->orderBy('due_date')
        ->take(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Monthly Revenue
        |--------------------------------------------------------------------------
        */

        $monthlyRevenue = Invoice::where(
            'company_id',
            $companyId
        )
        ->where('status', 'paid')
        ->whereYear(
            'issue_date',
            now()->year
        )
        ->selectRaw(
            'strftime("%m", issue_date) as month,
            SUM(total) as revenue'
        )
        ->groupBy('month')
        ->orderBy('month')
        ->pluck(
            'revenue',
            'month'
        );

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'invoices.dashboard',
            compact(
                'totalInvoices',
                'draftInvoices',
                'sentInvoices',
                'partialInvoices',
                'paidInvoices',
                'totalInvoiced',
                'totalPaid',
                'totalOutstanding',
                'overdueInvoices',
                'overdueAmount',
                'recentInvoices',
                'upcomingInvoices',
                'recentPayments',
                'monthlyRevenue'
            )
        );
    }
    


    /**
     * Mark invoice as paid.
     */
    public function markPaid(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $totalPaid = (float) $invoice->payments()->sum('amount');
        $invoiceTotal = (float) $invoice->total;

        if ($totalPaid < $invoiceTotal) {
            return back()->withErrors([
                'payment' =>'This invoice cannot be marked as paid while a balance remains outstanding.'
            ]);
        }

        $invoice->syncPaymentStatus();

        return back()->with(
            'success',
            'Invoice payment status synchronized successfully.'
        );
    }


    /**
     * Authorize invoice access.
     */
    private function authorizeInvoice(
        Invoice $invoice
    ) {
        if (
            $invoice->company_id !==
            auth()->user()->company_id
        ) {
            abort(403);
        }
    }


    /**
     * Send invoice by email.
     */
    public function sendInvoice(
        Invoice $invoice
    ) {
        $this->authorizeInvoice($invoice);

        $invoice->load([
            'company',
            'client',
            'items',
            'payments',
        ]);

        Mail::to(
            $invoice->client->email
        )->send(
            new \App\Mail\InvoiceMail($invoice)
        );

        return back()->with(
            'success',
            'Invoice email sent successfully.'
        );
    }
}