<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
        /*
        |--------------------------------------------------------------------------
        | Company Security
        |--------------------------------------------------------------------------
        */

        if ($invoice->company_id !== auth()->user()->company_id) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Overpayment
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $alreadyPaid = (float) $invoice->payments()->sum('amount');

        $balance = (float) $invoice->total - $alreadyPaid;

        if ($amount > $balance) {

            return back()
                ->withErrors([
                    'amount' => 'Payment cannot be greater than the remaining invoice balance.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Payment
        |--------------------------------------------------------------------------
        */

        Payment::create([
            'company_id' => auth()->user()->company_id,
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Invoice Status
        |--------------------------------------------------------------------------
        */

        $invoice->syncPaymentStatus();

        return back()->with(
            'success',
            'Payment recorded successfully.'
        );
        
    }

    public function edit(Payment $payment)
    {
        if ($payment->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $payment->load('invoice');

        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        if ($payment->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $invoice = $payment->invoice;

        /*
        |--------------------------------------------------------------------------
        | Calculate balance excluding the payment being edited
        |--------------------------------------------------------------------------
        */

        $otherPayments = $invoice->payments()
        ->where('id', '!=', $payment->id)
        ->sum('amount');

        $balanceAvailable = (float) $invoice->total - (float) $otherPayments;

        $amount = (float) $validated['amount'];

        if ($amount > $balanceAvailable) {
            return back()
                ->withErrors([
                    'amount' => 'Payment cannot be greater than the remaining invoice balance.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Update payment
        |--------------------------------------------------------------------------
        */

        $payment->update([
            'amount' => $amount,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Recalculate invoice status
        |--------------------------------------------------------------------------
        */

        $invoice->syncPaymentStatus();

        return redirect()
        ->route('invoices.show', $invoice)
         ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->company_id !== auth()->user()->company_id) {
                abort(403);
            }

            $invoice = $payment->invoice;

                $payment->delete();

            /*
            |--------------------------------------------------------------------------
            | Recalculate invoice status
            |--------------------------------------------------------------------------
            */

            $invoice->syncPaymentStatus();

        return back()->with(
            'success',
            'Payment deleted successfully.'
        );
    }
}