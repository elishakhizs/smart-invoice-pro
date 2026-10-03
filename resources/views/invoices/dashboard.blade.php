@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Dashboard
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Overview of your company's invoicing and financial activity.
            </p>
        </div>

        <div class="mt-4 md:mt-0">

            <a
                href="{{ route('invoices.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800"
            >
                + Create Invoice
            </a>

        </div>

    </div>


    {{-- Financial KPI Cards --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total Invoiced --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                Total Invoiced
            </p>

            <p class="mt-2 text-2xl font-bold text-gray-900">
                R {{ number_format($totalInvoiced, 2) }}
            </p>

            <p class="mt-2 text-xs text-gray-500">
                {{ $totalInvoices }} invoice(s)
            </p>

        </div>


        {{-- Total Paid --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                Total Paid
            </p>

            <p class="mt-2 text-2xl font-bold text-green-600">
                R {{ number_format($totalPaid, 2) }}
            </p>

            <p class="mt-2 text-xs text-gray-500">
                {{ $paidInvoices }} paid invoice(s)
            </p>

        </div>


        {{-- Outstanding --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                Outstanding
            </p>

            <p class="mt-2 text-2xl font-bold text-yellow-600">
                R {{ number_format($totalOutstanding, 2) }}
            </p>

            <p class="mt-2 text-xs text-gray-500">
                {{ $sentInvoices + $partialInvoices }} outstanding invoice(s)
            </p>

        </div>


        {{-- Overdue --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                Overdue
            </p>

            <p class="mt-2 text-2xl font-bold text-red-600">
                R {{ number_format($overdueAmount, 2) }}
            </p>

            <p class="mt-2 text-xs text-gray-500">
                {{ $overdueInvoices }} overdue invoice(s)
            </p>

        </div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <a href="{{ route('invoices.create') }}" class="bg-white border border-gray-200 rounded-xl p-5 hover:border-red-400 hover:shadow-sm transition">
            <p class="text-sm font-semibold text-gray-900">
                Create Invoice
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Create a new customer invoice.
            </p>
        </a>

        <a href="{{ route('clients.index') }}" class="bg-white border border-gray-200 rounded-xl p-5 hover:border-red-400 hover:shadow-sm transition">
            <p class="text-sm font-semibold text-gray-900">
                Manage Clients
            </p>

            <p class="text-xs text-gray-500 mt-1">
                View and manage your clients.
            </p>
        </a>

        <a href="{{ route('reports.index') }}" class="bg-white border border-gray-200 rounded-xl p-5 hover:border-red-400 hover:shadow-sm transition">
            <p class="text-sm font-semibold text-gray-900">
                View Reports
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Analyse your invoicing activity.
            </p>
        </a>

        <a href="{{ route('company.settings') }}" class="bg-white border border-gray-200 rounded-xl p-5 hover:border-red-400 hover:shadow-sm transition">
            <p class="text-sm font-semibold text-gray-900">
                Company Settings
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Manage your business information.
            </p>
        </a>

    </div>


    {{-- Invoice Status --}}

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-8">

        <div class="px-6 py-5 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-900">
                Invoice Overview
            </h2>

        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-200">

            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Draft
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    {{ $draftInvoices }}
                </p>

            </div>

            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Sent
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-600">
                    {{ $sentInvoices }}
                </p>

            </div>

            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Partially Paid
                </p>

                <p class="mt-2 text-2xl font-bold text-yellow-600">
                    {{ $partialInvoices }}
                </p>

            </div>

            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Paid
                </p>

                <p class="mt-2 text-2xl font-bold text-green-600">
                    {{ $paidInvoices }}
                </p>

            </div>

        </div>

    </div>


    {{-- Revenue + Recent Payments --}}

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-8">

            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-900">
                    Upcoming Due Invoices
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Outstanding invoices due within the next 30 days.
                </p>

            </div>

            <div class="divide-y divide-gray-100">

                @forelse($upcomingInvoices as $invoice)

                    <div class="px-6 py-4 flex items-center justify-between">

                        <div>

                            <a href="{{ route('invoices.show', $invoice) }}"class="text-sm font-semibold text-gray-900 hover:underline">
                                {{ $invoice->invoice_number }}
                            </a>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $invoice->client->name }}
                            </p>

                        </div>

                        <div class="text-right">

                            <p class="text-sm font-semibold text-gray-900">
                                R {{ number_format($invoice->balance_due, 2) }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Due {{ $invoice->due_date->format('d M Y') }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center">

                        <p class="text-sm text-gray-500">
                            No invoices are due within the next 30 days.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>
        {{-- Revenue --}}

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-900">
                    Revenue
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Paid invoice revenue for {{ now()->year }}.
                </p>

            </div>

            <div class="p-6">

                @if($monthlyRevenue->count())

                    <div class="space-y-4">

                        @foreach($monthlyRevenue as $month => $revenue)

                            <div>

                                <div class="flex justify-between text-sm mb-1">

                                    <span class="text-gray-600">
                                        {{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') }}
                                    </span>

                                    <span class="font-semibold text-gray-900">
                                        R {{ number_format($revenue, 2) }}
                                    </span>

                                </div>

                                <div class="w-full bg-gray-100 rounded-full h-2">

                                    @php
                                        $maxRevenue = $monthlyRevenue->max();
                                        $percentage = $maxRevenue > 0
                                            ? ($revenue / $maxRevenue) * 100
                                            : 0;
                                    @endphp

                                    <div
                                        class="bg-gray-900 h-2 rounded-full"
                                        style="width: {{ $percentage }}%"
                                    ></div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-10">

                        <p class="text-gray-500">
                            No paid invoices yet.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Recent Payments --}}

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-900">
                    Recent Payments
                </h2>

            </div>

            <div class="divide-y divide-gray-100">

                @forelse($recentPayments as $payment)

                    <div class="px-6 py-4 flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-900">

                                {{ $payment->invoice->invoice_number }}

                            </p>

                            <p class="text-xs text-gray-500 mt-1">

                                {{ $payment->invoice->client->name }}

                                @if($payment->payment_method)
                                    • {{ $payment->payment_method }}
                                @endif

                            </p>

                        </div>

                        <div class="text-right">

                            <p class="text-sm font-semibold text-green-600">

                                + R {{ number_format($payment->amount, 2) }}

                            </p>

                            <p class="text-xs text-gray-500 mt-1">

                                {{ $payment->payment_date->format('d M Y') }}

                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center">

                        <p class="text-sm text-gray-500">
                            No payments recorded yet.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- Recent Invoices --}}

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">

            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Recent Invoices
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Your most recently created invoices.
                </p>

            </div>

            <a
                href="{{ route('invoices.index') }}"
                class="text-sm font-medium text-gray-700 hover:text-gray-900"
            >
                View all →
            </a>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Invoice
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Client
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($recentInvoices as $invoice)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <a
                                    href="{{ route('invoices.show', $invoice) }}"
                                    class="font-medium text-gray-900 hover:underline"
                                >
                                    {{ $invoice->invoice_number }}
                                </a>

                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">

                                {{ $invoice->client->name }}

                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">

                                {{ $invoice->issue_date->format('d M Y') }}

                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-gray-900">

                                R {{ number_format($invoice->total, 2) }}

                            </td>

                            <td class="px-6 py-4">

                                @if($invoice->status === 'paid')

                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        Paid
                                    </span>

                                @elseif($invoice->status === 'partial')

                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                        Partial
                                    </span>

                                @elseif($invoice->status === 'sent')

                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                        Sent
                                    </span>

                                @else

                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                        Draft
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('invoices.show', $invoice) }}"
                                    class="text-sm font-medium text-gray-700 hover:text-gray-900"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-10 text-center text-sm text-gray-500"
                            >
                                No invoices found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection