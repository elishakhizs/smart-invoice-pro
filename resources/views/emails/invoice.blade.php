<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>
        Invoice {{ $invoice->invoice_number }}
    </title>

</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4;font-family: Arial, Helvetica, sans-serif;">

    <div style="width: 100%; padding: 30px 0;">

        <div style="max-width: 650px; margin: 0 auto; background: #ffffff; padding: 30px;">

            {{-- Company --}}

            <h2 style=" margin: 0 0 5px 0;">
                {{ $invoice->company->name }}
            </h2>

            <p style="margin: 0 0 25px 0; color: #666666;">
                Invoice {{ $invoice->invoice_number }}
            </p>


            {{-- Greeting --}}

            <p>
                Hello {{ $invoice->client->name }},
            </p>

            <p>
                Please find your invoice attached to this email.
            </p>


            {{-- Invoice summary --}}

            <table style="width: 100%; border-collapse: collapse; margin: 25px 0;">

                <tr>

                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee;">
                        Invoice Number
                    </td>

                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee; text-align: right;">
                        <strong>
                            {{ $invoice->invoice_number }}
                        </strong>
                    </td>

                </tr>

                <tr>

                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee;">
                        Issue Date
                    </td>

                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee; text-align: right;">
                        {{ $invoice->issue_date->format('d M Y') }}
                    </td>

                </tr>

                <tr>

                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee;">
                        Due Date
                    </td>

                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee; text-align: right;">
                        {{ $invoice->due_date->format('d M Y') }}
                    </td>

                </tr>

                <tr>
                    <td style=" padding: 10px; border-bottom: 1px solid #eeeeee;">
                        Amount Due
                    </td>

                    <td style=" padding: 10px;text-align: right; font-size: 18px;">
                        <strong>
                            R {{ number_format($invoice->balance_due, 2) }}
                        </strong>
                    </td>

                </tr>

            </table>


            {{-- Payment status --}}

            @if($invoice->status === 'paid')

                <p style="padding: 15px; background: #ecfdf5; text-align: center;">
                    <strong>
                        PAID IN FULL
                    </strong>
                </p>

            @elseif($invoice->status === 'partial')

                <p style="padding: 15px; background: #fffbeb; text-align: center;">
                    <strong>
                        PARTIALLY PAID
                    </strong>
                    <br>
                    Balance due:
                    R {{ number_format($invoice->balance_due, 2) }}
                </p>

            @else

                <p style=" padding: 15px; background: #fef2f2; text-align: center;">
                    <strong>
                        PAYMENT OUTSTANDING
                    </strong>
                </p>

            @endif


            {{-- Message --}}

            <p>
                If you have any questions regarding this invoice,
                please contact us.
            </p>

            @if($invoice->company->email)

                <p>
                    Email:
                    <strong>
                        {{ $invoice->company->email }}
                    </strong>
                </p>

            @endif

            @if($invoice->company->phone)

                <p>
                    Phone:
                    <strong>
                        {{ $invoice->company->phone }}
                    </strong>
                </p>

            @endif


            {{-- Footer --}}

            <hr style=" margin: 30px 0; border: 0; border-top: 1px solid #eeeeee;">

            <p style=" margin: 0; text-align: center; color: #777777; font-size: 12px;">
                Thank you for your business.
            </p>

            <p style=" margin: 5px 0 0 0; text-align: center; color: #999999; font-size: 11px;">
                {{ $invoice->company->name }}
            </p>

        </div>

    </div>

</body>
</html>