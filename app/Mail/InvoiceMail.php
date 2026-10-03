<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;

        $this->invoice->load([
            'company',
            'client',
            'items',
            'payments',
        ]);
    }

    public function build()
    {
        $pdf = Pdf::loadView(
            'invoices.pdf',
            [
                'invoice' => $this->invoice,
            ]
        )->output();

        return $this
            ->subject(
                'Invoice ' .
                $this->invoice->invoice_number .
                ' from ' .
                $this->invoice->company->name
            )
            ->view('emails.invoice')
            ->attachData(
                $pdf,
                $this->invoice->invoice_number . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}