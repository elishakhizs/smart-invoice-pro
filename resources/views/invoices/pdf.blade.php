<div style="width: 100%; margin-bottom: 30px;">

```
<table style="width: 100%; border-collapse: collapse;">
    <tr>

        {{-- COMPANY INFORMATION --}}
        <td style="width: 60%; vertical-align: top;">

            <h1 style="margin: 0 0 8px 0;">
                {{ $invoice->company->name }}
            </h1>

            @if($invoice->company->address)
                <p style="margin: 3px 0;">
                    {{ $invoice->company->address }}
                </p>
            @endif

            @if($invoice->company->city || $invoice->company->country)
                <p style="margin: 3px 0;">
                    {{ $invoice->company->city }}

                    @if($invoice->company->city && $invoice->company->country)
                        ,
                    @endif

                    {{ $invoice->company->country }}
                </p>
            @endif

            @if($invoice->company->phone)
                <p style="margin: 3px 0;">
                    Phone: {{ $invoice->company->phone }}
                </p>
            @endif

            @if($invoice->company->email)
                <p style="margin: 3px 0;">
                    Email: {{ $invoice->company->email }}
                </p>
            @endif

            @if($invoice->company->tax_number)
                <p style="margin: 3px 0;">
                    Tax/VAT: {{ $invoice->company->tax_number }}
                </p>
            @endif

        </td>


        {{-- INVOICE INFORMATION --}}
        <td style="width: 40%; text-align: right; vertical-align: top;">

            <h2 style="margin: 0 0 10px 0;">
                INVOICE
            </h2>

            <p style="margin: 5px 0;">
                <strong>
                    {{ $invoice->invoice_number }}
                </strong>
            </p>

            <p style="margin: 5px 0;">
                <strong>Issue Date:</strong>
                {{ $invoice->issue_date->format('d M Y') }}
            </p>

            <p style="margin: 5px 0;">
                <strong>Due Date:</strong>
                {{ $invoice->due_date->format('d M Y') }}
            </p>

            <p style="margin: 8px 0 0 0;">
                <strong>Status:</strong>

                @if($invoice->status === 'paid')
                    Paid
                @elseif($invoice->status === 'partial')
                    Partially Paid
                @elseif($invoice->status === 'sent')
                    Outstanding
                @else
                    Draft
                @endif
            </p>

        </td>

    </tr>
</table>
```

</div>

<div style="width: 100%; margin-bottom: 25px;">

    <table style="width: 100%; border-collapse: collapse;">
        <tr>

            <td style="width: 50%; vertical-align: top;">
                <h3 style="margin: 0 0 8px 0;">
                    BILL TO
                </h3>

                <p style="margin: 3px 0;">
                    <strong>{{ $invoice->client->name }}</strong>
                </p>

                @if($invoice->client->company_name)
                    <p style="margin: 3px 0;">
                        {{ $invoice->client->company_name }}
                    </p>
                @endif

                @if($invoice->client->email)
                    <p style="margin: 3px 0;">
                        {{ $invoice->client->email }}
                    </p>
                @endif

                @if($invoice->client->phone)
                    <p style="margin: 3px 0;">
                        {{ $invoice->client->phone }}
                    </p>
                @endif

                @if($invoice->client->address)
                    <p style="margin: 3px 0;">
                        {{ $invoice->client->address }}
                    </p>
                @endif

                @if($invoice->client->city || $invoice->client->country)
                    <p style="margin: 3px 0;">
                        {{ $invoice->client->city }}

                        @if($invoice->client->city && $invoice->client->country)
                            ,
                        @endif

                        {{ $invoice->client->country }}
                    </p>
                @endif

                @if($invoice->client->tax_number)
                    <p style="margin: 3px 0;">
                        Tax/VAT: {{ $invoice->client->tax_number }}
                    </p>
                @endif

            </td>

        </tr>
    </table>

</div>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">

    <thead>
        <tr style="background-color: #f3f4f6;">

            <th style="padding: 10px; text-align: left;">
                Description
            </th>

            <th style="padding: 10px; text-align: center;">
                Qty
            </th>

            <th style="padding: 10px; text-align: right;">
                Unit Price
            </th>

            <th style="padding: 10px; text-align: right;">
                Total
            </th>

        </tr>
    </thead>

    <tbody>

        @foreach($invoice->items as $item)

            <tr>

                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    {{ $item->description }}
                </td>

                <td style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">
                    {{ $item->quantity }}
                </td>

                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">
                    R {{ number_format($item->unit_price, 2) }}
                </td>

                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">
                    R {{ number_format($item->quantity * $item->unit_price, 2) }}
                </td>

            </tr>

        @endforeach

    </tbody>

</table>

<div style="width: 100%; margin-top: 20px;">

    <table style="width: 100%; border-collapse: collapse;">

        <tr>
            <td style="width: 70%;"></td>

            <td style="width: 30%; padding: 6px;">
                <strong>Invoice Total:</strong>
            </td>

            <td style="text-align: right; padding: 6px;">
                R {{ number_format($invoice->total, 2) }}
            </td>
        </tr>

        <tr>
            <td></td>

            <td style="padding: 6px;">
                Amount Paid:
            </td>

            <td style="text-align: right; padding: 6px;">
                R {{ number_format($invoice->total_paid, 2) }}
            </td>
        </tr>

        <tr>
            <td></td>

            <td style="padding: 8px; border-top: 2px solid #000;">
                <strong>Balance Due:</strong>
            </td>

            <td style="text-align: right; padding: 8px; border-top: 2px solid #000;">
                <strong>
                    R {{ number_format($invoice->balance_due, 2) }}
                </strong>
            </td>
        </tr>

    </table>

</div>

{{-- FOOTER SECTION --}}    

@if($invoice->payments->count())

    <div style="margin-top: 25px;">

        <h3 style="margin-bottom: 10px;">
            PAYMENT HISTORY
        </h3>

        <table style="width: 100%; border-collapse: collapse;">

            <thead>
                <tr style="background-color: #f3f4f6;">

                    <th style="padding: 8px; text-align: left;">
                        Date
                    </th>

                    <th style="padding: 8px; text-align: left;">
                        Method
                    </th>

                    <th style="padding: 8px; text-align: left;">
                        Reference
                    </th>

                    <th style="padding: 8px; text-align: right;">
                        Amount
                    </th>

                </tr>
            </thead>

            <tbody>

                @foreach($invoice->payments as $payment)

                    <tr>

                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                            {{ $payment->payment_date?->format('d M Y') }}
                        </td>

                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                            {{ $payment->payment_method ?: '—' }}
                        </td>

                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                            {{ $payment->reference ?: '—' }}
                        </td>

                        <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">
                            R {{ number_format($payment->amount, 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

@endif

{{-- PAYMENT INFORMATION --}}

<div style="margin-top: 25px;">

    <h3 style="margin-bottom: 8px;">
        PAYMENT INFORMATION
    </h3>

    <p style="margin: 4px 0;">
        Please use the invoice number
        <strong>{{ $invoice->invoice_number }}</strong>
        as your payment reference.
    </p>

    <p style="margin: 4px 0;">
        For payment enquiries, please contact
        {{ $invoice->company->email ?? 'our office' }}.
    </p>

</div> 

{{-- FOOTER SECTION --}}

<div style="
    margin-top: 40px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
    text-align: center;
    font-size: 10px;
    color: #666;
">

    <p style="margin: 3px 0;">
        Thank you for your business.
    </p>

    <p style="margin: 3px 0;">
        {{ $invoice->company->name }}
        @if($invoice->company->email)
            | {{ $invoice->company->email }}
        @endif

        @if($invoice->company->phone)
            | {{ $invoice->company->phone }}
        @endif
    </p>

</div>
