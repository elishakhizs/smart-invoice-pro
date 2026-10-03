@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $client->name }}
            </h1>

            @if($client->company_name)
                <p class="text-gray-500 mt-1">
                    {{ $client->company_name }}
                </p>
            @endif
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('clients.edit', $client) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Edit Client
            </a>

            <a
                href="{{ route('clients.index') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
            >
                Back to Clients
            </a>

        </div>

    </div>


    {{-- Financial Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Invoiced --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Total Invoiced
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                R {{ number_format($totalInvoiced, 2) }}
            </p>

        </div>


        {{-- Total Paid --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Total Paid
            </p>

            <p class="text-2xl font-bold text-green-600 mt-2">
                R {{ number_format($totalPaid, 2) }}
            </p>

        </div>


        {{-- Outstanding --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Outstanding
            </p>

            <p class="text-2xl font-bold text-orange-600 mt-2">
                R {{ number_format($totalOutstanding, 2) }}
            </p>

        </div>


        {{-- Overdue --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Overdue Invoices
            </p>

            <p class="text-2xl font-bold text-red-600 mt-2">
                {{ $overdueInvoices }}
            </p>

        </div>

    </div>


    {{-- Client Information --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Contact Information --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">

            <h2 class="text-lg font-semibold text-gray-800 mb-5">
                Client Information
            </h2>

            <div class="space-y-4">

                <div>
                    <p class="text-sm text-gray-500">
                        Name
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->name }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        Company
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->company_name ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        Email
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->email ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        Phone
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->phone ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        Tax / VAT Number
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->tax_number ?? 'Not provided' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Address --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">

            <h2 class="text-lg font-semibold text-gray-800 mb-5">
                Address
            </h2>

            <div class="space-y-4">

                <div>
                    <p class="text-sm text-gray-500">
                        Address
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->address ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        City
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->city ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        ZIP / Postal Code
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->postal_code ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500">
                        Country
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $client->country ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- Invoice History --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="p-6 border-b flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Invoice History
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $totalInvoices }} invoice(s)
                </p>
            </div>

            {{-- Create Invoice button will be connected in the Invoice Module --}}
            <button
                type="button"
                class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed"
                title="Invoice module coming next"
            >
                + Create Invoice
            </button>

        </div>


        @if($client->invoices->count())

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-gray-50 border-b">

                        <tr>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Invoice
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Date
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Due Date
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Amount
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($client->invoices as $invoice)

                            <tr class="hover:bg-gray-50">

                                {{-- Invoice Number --}}
                                <td class="px-6 py-4">

                                    <a
                                        href="{{ route('invoices.show', $invoice) }}"
                                        class="font-medium text-blue-600 hover:underline"
                                    >
                                        {{ $invoice->invoice_number }}
                                    </a>

                                </td>


                                {{-- Invoice Date --}}
                                <td class="px-6 py-4 text-sm text-gray-600">

                                    {{ $invoice->issue_date
                                        ? $invoice->issue_date->format('d M Y')
                                        : '-' }}

                                </td>


                                {{-- Due Date --}}
                                <td class="px-6 py-4 text-sm text-gray-600">

                                    {{ $invoice->due_date
                                        ? $invoice->due_date->format('d M Y')
                                        : '-' }}

                                </td>


                                {{-- Amount --}}
                                <td class="px-6 py-4 font-medium text-gray-900">

                                    R {{ number_format($invoice->total ?? 0, 2) }}

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    @if($invoice->status === 'paid')

                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            Paid
                                        </span>

                                    @elseif(
                                        $invoice->status === 'sent' &&
                                        $invoice->due_date &&
                                        $invoice->due_date->isPast()
                                    )

                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                            Overdue
                                        </span>

                                    @elseif($invoice->status === 'sent')

                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                            Sent
                                        </span>

                                    @elseif($invoice->status === 'cancelled')

                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                            Draft
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('invoices.show', $invoice) }}"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-10 text-center">

                <h3 class="text-lg font-semibold text-gray-800">
                    No invoices yet
                </h3>

                <p class="text-gray-500 mt-2">
                    Invoices created for this client will appear here.
                </p>

            </div>

        @endif

    </div>

    {{-- Payment History --}}
    <div class="mt-8 bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">
                Payment History
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Payments received from this client
            </p>
        </div>

        @if($payments->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Date
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Invoice
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Method
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Reference
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                Amount
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $payment->payment_date?->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('invoices.show', $payment->invoice) }}"class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                        {{ $payment->invoice->invoice_number }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $payment->payment_method ?: '—' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $payment->reference ?: '—' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                    R {{ number_format($payment->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                No payments have been recorded for this client yet.
            </div>
        @endif
    </div>

</div>

@endsection