@extends('layouts.app')

@section('content')

<div class="space-y-8">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Dashboard
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Overview of your business finances and invoice activity.
        </p>
    </div>


    {{-- Financial Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Revenue --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <h3 class="text-gray-500 text-sm">
                Total Revenue
            </h3>

            <p class="text-3xl font-bold mt-2 text-gray-900">
                R{{ number_format($totalRevenue, 2) }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                From paid invoices
            </p>

        </div>


        {{-- Outstanding --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <h3 class="text-gray-500 text-sm">
                Outstanding
            </h3>

            <p class="text-3xl font-bold mt-2 text-gray-900">
                R{{ number_format($outstandingAmount, 2) }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                {{ $outstandingInvoices }} unpaid invoice(s)
            </p>

        </div>


        {{-- Clients --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <h3 class="text-gray-500 text-sm">
                Clients
            </h3>

            <p class="text-3xl font-bold mt-2 text-gray-900">
                {{ $totalClients }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                Total registered clients
            </p>

        </div>


        {{-- Paid Invoices --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <h3 class="text-gray-500 text-sm">
                Paid Invoices
            </h3>

            <p class="text-3xl font-bold mt-2 text-gray-900">
                {{ $paidInvoices }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                Successfully paid
            </p>

        </div>

        {{-- Partially Paid --}}
        <div class="bg-white rounded-lg shadow p-6">

            <p class="text-sm font-medium text-gray-500">
                Partially Paid
            </p>

            <p class="mt-2 text-3xl font-bold text-yellow-600">
                {{ $partialInvoices }}
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Invoices with outstanding balances
            </p>

        </div>

    </div>


    {{-- Invoice Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Total Invoices --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Total Invoices
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $totalInvoices }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                All invoices created
            </p>

        </div>


        {{-- Draft Invoices --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Draft Invoices
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $draftInvoices }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                Not yet sent
            </p>

        </div>


        {{-- Overdue --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Overdue
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $overdueInvoices }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                R{{ number_format($overdueAmount, 2) }} overdue
            </p>

        </div>

    </div>


    {{-- Recent Invoices --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

        <div class="p-6 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Recent Invoices
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Your latest invoice activity.
                    </p>
                </div>

                <a
                    href="{{ route('invoices.index') }}"
                    class="text-sm font-medium text-gray-700 hover:text-gray-900"
                >
                    View All
                </a>

            </div>

        </div>


        <div class="overflow-x-auto">

            @if($recentInvoices->count())

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-gray-500">

                            <th class="px-6 py-4 font-medium">
                                Invoice
                            </th>

                            <th class="px-6 py-4 font-medium">
                                Client
                            </th>

                            <th class="px-6 py-4 font-medium">
                                Amount
                            </th>

                            <th class="px-6 py-4 font-medium">
                                Due Date
                            </th>

                            <th class="px-6 py-4 font-medium">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($recentInvoices as $invoice)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 font-medium text-gray-900">

                                    {{ $invoice->invoice_number }}

                                </td>


                                <td class="px-6 py-4 text-gray-600">

                                    {{ $invoice->client->name ?? 'No client' }}

                                </td>


                                <td class="px-6 py-4 font-medium text-gray-900">

                                    R{{ number_format($invoice->total, 2) }}

                                </td>


                                <td class="px-6 py-4 text-gray-600">

                                    {{ $invoice->due_date
                                        ? $invoice->due_date->format('d M Y')
                                        : '—'
                                    }}

                                </td>


                                <td class="px-6 py-4">

                                    @if($invoice->status === 'paid')

                                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                            Paid
                                        </span>

                                    @elseif($invoice->status === 'sent')

                                        @if($invoice->due_date && $invoice->due_date->isPast())

                                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                Overdue
                                            </span>

                                        @else

                                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                                Sent
                                            </span>

                                        @endif

                                    @else

                                        <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                            Draft
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="p-8 text-center">

                    <p class="text-gray-500">
                        No invoices have been created yet.
                    </p>

                    <a
                        href="{{ route('invoices.create') }}"
                        class="inline-block mt-4 px-4 py-2 bg-gray-900 text-white rounded-lg text-sm hover:bg-gray-800"
                    >
                        Create Your First Invoice
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <h3 class="text-xl font-bold text-gray-900">
            Quick Actions
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Common tasks you may want to perform.
        </p>


        <div class="flex flex-wrap gap-3 mt-5">

            <a
                href="{{ route('invoices.create') }}"
                class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800"
            >
                + Create Invoice
            </a>


            <a
                href="{{ route('clients.create') }}"
                class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50"
            >
                + Add Client
            </a>


            <a
                href="{{ route('clients.index') }}"
                class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50"
            >
                Manage Clients
            </a>


            <a
                href="{{ route('reports.index') }}"
                class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50"
            >
                View Reports
            </a>

        </div>

    </div>

</div>

@endsection