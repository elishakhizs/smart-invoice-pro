@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Invoice Management
            </h1>

            <p class="text-gray-500 mt-1">
                Create and manage your company's invoices.
            </p>
        </div>

        <a
            href="{{ route('invoices.create') }}"
            class="bg-gray-900 text-white px-5 py-2.5 rounded-lg hover:bg-gray-800 transition"
        >
            + Create Invoice
        </a>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

        <form method="GET" action="{{ route('invoices.index') }}"class="grid grid-cols-1 md:grid-cols-4 gap-4">

        {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Search
                </label>

                <input type="text"name="search"value="{{ request('search') }}" placeholder="Invoice number or client..." class="w-full rounded-lg border-gray-300">
            </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Status
            </label>

            <select name="status" class="w-full rounded-lg border-gray-300">
                <option value="">All Statuses</option>

                <option value="overdue"{{ request('status') === 'overdue' ? 'selected' : '' }}>
                    Overdue
                </option>

                <option value="draft"
                    {{ request('status') === 'draft' ? 'selected' : '' }}>
                    Draft
                </option>

                <option value="sent"
                    {{ request('status') === 'sent' ? 'selected' : '' }}>
                    Sent
                </option>

                <option value="paid"
                    {{ request('status') === 'paid' ? 'selected' : '' }}>
                    Paid
                </option>
            </select>
        </div>

        {{-- From Date --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                From Date
            </label>

            <input
                type="date"
                name="from_date"
                value="{{ request('from_date') }}"
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        {{-- To Date --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                To Date
            </label>

            <input
                type="date"
                name="to_date"
                value="{{ request('to_date') }}"
                class="w-full rounded-lg border-gray-300"
            >
        </div>
        {{-- Total --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-500">
                Total Invoices
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $totalInvoices }}
            </p>
        </div>

        {{-- Draft --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-500">
                Draft
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $draftInvoices }}
            </p>
        </div>

        {{-- Sent --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-500">
                Sent
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $sentInvoices }}
            </p>
        </div>

        {{-- Paid --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-500">
                Paid
            </p>

            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $paidInvoices }}
            </p>
        </div>
        
        {{-- Partial --}}
        <div class="bg-white rounded-lg shadow p-6">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Partially Paid
                    </p>

                    <p class="mt-2 text-3xl font-bold text-yellow-600">
                        {{ $partialInvoices }}
                    </p>
                </div>

                <div class="text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg"class="h-8 w-8"fill="none"viewBox="0 0 24 24"stroke="currentColor">

                        <path stroke-linecap="round"stroke-linejoin="round"stroke-width="2"d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3m0-6V5m0 9v5m9-7a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>
                </div>

            </div>

        </div>

        {{-- Filter & Clear Buttons --}}
        <div class="md:col-span-4 flex gap-3">

            <button
                type="submit"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Filter Invoices
            </button>

            <a
                href="{{ route('invoices.index') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
            >
                Clear
            </a>

        </div><br>

        </form>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>

    @endif


    {{-- Invoice Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-lg font-semibold text-gray-800">
                Invoices
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                {{ $invoices->total() }} invoice(s) found
            </p>

        </div>


        @if($invoices->count())

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-gray-50 border-b">

                        <tr>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Invoice #
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Client
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Issue Date
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Due Date
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Total
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($invoices as $invoice)

                            <tr class="hover:bg-gray-50">

                                {{-- Invoice Number --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $invoice->invoice_number }}
                                    </div>

                                </td>


                                {{-- Client --}}
                                <td class="px-6 py-4 text-gray-600">

                                    {{ $invoice->client->company_name ?? $invoice->client->name }}

                                </td>


                                {{-- Issue Date --}}
                                <td class="px-6 py-4 text-gray-600">

                                    {{ $invoice->issue_date->format('d M Y') }}

                                </td>


                                {{-- Due Date --}}
                                <td class="px-6 py-4 text-gray-600">

                                    {{ $invoice->due_date->format('d M Y') }}

                                </td>


                                {{-- Total --}}
                                <td class="px-6 py-4 font-medium text-gray-900">

                                    R{{ number_format($invoice->total, 2) }}

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    @if( $invoice->status !== 'paid' && $invoice->due_date->isPast())

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Overdue
                                        </span>

                                    @elseif($invoice->status === 'paid')

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Paid
                                        </span>

                                    @elseif($invoice->status === 'sent')

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Sent
                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Draft
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('invoices.show', $invoice) }}"
                                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('invoices.edit', $invoice) }}"
                                            class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('invoices.destroy', $invoice) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this invoice?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="p-6 border-t">

                {{ $invoices->links() }}

            </div>


        @else

            <div class="p-12 text-center">

                <h3 class="text-lg font-semibold text-gray-800">
                    No invoices yet
                </h3>

                <p class="text-gray-500 mt-2">
                    Create your first invoice to get started.
                </p>

                <a
                    href="{{ route('invoices.create') }}"
                    class="inline-block mt-5 bg-gray-900 text-white px-5 py-2.5 rounded-lg hover:bg-gray-800"
                >
                    Create First Invoice
                </a>

            </div>

        @endif

    </div>

</div>

@endsection