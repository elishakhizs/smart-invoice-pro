<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = Client::where('company_id', $companyId)
        ->with([
            'invoices' => function ($query) {
                $query->latest();
            }
        ])
        ->withCount('invoices')
        ->withSum('invoices', 'total')
        ->withSum([
            'invoices as paid_invoices_total' => function ($query) {
                $query->where('status', 'paid');
            }
        ], 'total')
        ->withSum([
            'invoices as outstanding_invoices_total' => function ($query) {
                $query->where('status', 'sent');
            }
        ], 'total');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('company_name', 'like', '%' . $search . '%');

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Invoice Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('invoice_status')) {

            $status = $request->invoice_status;

                $query->whereHas('invoices', function ($invoiceQuery) use ($status) {

                    if ($status === 'overdue') {

                        $invoiceQuery
                        ->where('status', 'sent')
                        ->whereDate(
                            'due_date',
                            '<',
                            now()->toDateString()
                        );

                    } else {

                    $invoiceQuery->where(
                        'status',
                        $status
                    );
                }

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $clients = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view(
            'clients.index',
            compact('clients')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'nullable|email',

            'phone' => 'nullable|string',

            'company_name' => 'nullable|string',

            'tax_number' => 'nullable|string',

            'address' => 'nullable|string',

            'city' => 'nullable|string',

            'country' => 'nullable|string',

            'postal_code' => 'nullable|string',

        ]);

        $validated['company_id'] = Auth::user()->company_id;

        Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully');
    }

    /**
     * Display the specified resource.
     */
   public function show(Client $client)
    {
        if ($client->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $client->load([
            'invoices' => function ($query) {
                $query->with('payments')->latest();
            }
        ]);

        $payments = $client->invoices
        ->flatMap(function ($invoice) {
            return $invoice->payments->map(function ($payment) use ($invoice) {
                $payment->invoice = $invoice;

                return $payment;
            });
        })
        ->sortByDesc('payment_date')
        ->values();

        $totalInvoices = $client->invoices->count();

        $totalInvoiced = $client->invoices->sum(function ($invoice) {
            return (float) $invoice->total;
        });

        $totalPaid = $client->invoices->sum(function ($invoice) {
            return (float) $invoice->total_paid;
        });

        $totalOutstanding = $client->invoices->sum(function ($invoice) {
            return (float) $invoice->balance_due;
        });

        $overdueInvoices = $client->invoices->filter(function ($invoice) {
            return in_array($invoice->status, ['sent', 'partial'])
                && $invoice->due_date
                && $invoice->due_date->isPast()
                && $invoice->balance_due > 0;
        });

        $overdueCount = $overdueInvoices->count();

        $totalOverdue = $overdueInvoices->sum(function ($invoice) {
            return (float) $invoice->balance_due;
        });

        return view('clients.show', compact(
            'client',
            'totalInvoices',
            'totalInvoiced',
            'totalPaid',
            'totalOutstanding',
            'overdueCount',
            'totalOverdue',
            'payments'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
        // security: ensure tenant isolation
        if ($client->company_id !== Auth::user()->company_id){
            abort(403);
        }

        return view('clients.edit', compact('client'));
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // security: ensure tenant isolation
       if ($client->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company_name' => 'nullable|string',
            'tax_number' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // security: ensure tenant isolation
            if ($client->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully');
    }
}
