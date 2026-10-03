@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Client Management
            </h1>

            <p class="text-gray-500 mt-1">
                Manage your company's clients, invoices and financial activity.
            </p>
        </div>

        <a
            href="{{ route('clients.create') }}"
            class="bg-gray-900 text-white px-5 py-2.5 rounded-lg hover:bg-gray-800 transition"
        >
            + Add Client
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>

    @endif


    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

        <form
            method="GET"
            action="{{ route('clients.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4"
        >

            {{-- Search --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Search Clients
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name, company, email or phone..."
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- Invoice Status --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Invoice Status
                </label>

                <select
                    name="invoice_status"
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        All Clients
                    </option>

                    <option
                        value="draft"
                        {{ request('invoice_status') === 'draft' ? 'selected' : '' }}
                    >
                        Has Draft Invoices
                    </option>

                    <option
                        value="sent"
                        {{ request('invoice_status') === 'sent' ? 'selected' : '' }}
                    >
                        Has Sent Invoices
                    </option>

                    <option
                        value="paid"
                        {{ request('invoice_status') === 'paid' ? 'selected' : '' }}
                    >
                        Has Paid Invoices
                    </option>

                    <option
                        value="overdue"
                        {{ request('invoice_status') === 'overdue' ? 'selected' : '' }}
                    >
                        Has Overdue Invoices
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-3">

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Search
                </button>

                <a
                    href="{{ route('clients.index') }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                >
                    Clear
                </a>

            </div>

        </form>

    </div>


    {{-- Clients Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-lg font-semibold text-gray-800">
                Clients
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                {{ $clients->total() }} client(s) found
            </p>

        </div>


        @if($clients->count())

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-gray-50 border-b">

                        <tr>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Client
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Contact
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Invoices
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                Financial Summary
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($clients as $client)

                            <tr class="hover:bg-gray-50 align-top">

                                {{-- Client --}}
                                <td class="px-6 py-5">

                                    <div class="font-medium text-gray-900">
                                        {{ $client->name }}
                                    </div>

                                    @if($client->company_name)

                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ $client->company_name }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Contact --}}
                                <td class="px-6 py-5">

                                    <div class="text-sm text-gray-700">
                                        {{ $client->email ?? '-' }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $client->phone ?? '-' }}
                                    </div>

                                </td>


                                {{-- Invoice Summary --}}
                                <td class="px-6 py-5">

                                    <div class="font-semibold text-gray-900">
                                        {{ $client->invoices_count }}
                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">
                                        Invoice(s)
                                    </div>

                                    @if($client->invoices_count > 0)

                                        <div class="mt-3 space-y-1">

                                            @foreach($client->invoices->take(3) as $invoice)

                                                <div class="flex items-center gap-2">

                                                    <a
                                                        href="{{ route('invoices.show', $invoice) }}"
                                                        class="text-xs text-blue-600 hover:underline"
                                                    >
                                                        {{ $invoice->invoice_number }}
                                                    </a>

                                                    @if($invoice->status === 'paid')

                                                        <span class="text-xs text-green-600">
                                                            Paid
                                                        </span>

                                                    @elseif(
                                                        $invoice->status === 'sent' &&
                                                        $invoice->due_date->isPast()
                                                    )

                                                        <span class="text-xs text-red-600">
                                                            Overdue
                                                        </span>

                                                    @elseif($invoice->status === 'sent')

                                                        <span class="text-xs text-blue-600">
                                                            Sent
                                                        </span>

                                                    @else

                                                        <span class="text-xs text-gray-500">
                                                            Draft
                                                        </span>

                                                    @endif

                                                </div>

                                            @endforeach

                                        </div>

                                    @endif

                                </td>


                                {{-- Financial Summary --}}
                                <td class="px-6 py-5">

                                    <div class="space-y-1 text-sm">

                                        <div class="flex justify-between gap-6">
                                            <span class="text-gray-500">
                                                Invoiced
                                            </span>

                                            <span class="font-medium text-gray-900">
                                                R {{ number_format($client->invoices_sum_total ?? 0, 2) }}
                                            </span>
                                        </div>


                                        <div class="flex justify-between gap-6">
                                            <span class="text-gray-500">
                                                Paid
                                            </span>

                                            <span class="font-medium text-green-600">
                                                R {{ number_format($client->paid_invoices_total ?? 0, 2) }}
                                            </span>
                                        </div>


                                        <div class="flex justify-between gap-6">
                                            <span class="text-gray-500">
                                                Outstanding
                                            </span>

                                            <span class="font-medium text-orange-600">
                                                R {{ number_format($client->outstanding_invoices_total ?? 0, 2) }}
                                            </span>
                                        </div>

                                    </div>

                                </td>


                                {{-- Actions --}}
                                <td class="px-6 py-5">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('clients.show', $client) }}"
                                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ route('clients.edit', $client) }}"
                                            class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            action="{{ route('clients.destroy', $client) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this client?');"
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
                {{ $clients->links() }}
            </div>


        @else

            <div class="p-12 text-center">

                <h3 class="text-lg font-semibold text-gray-800">
                    No clients found
                </h3>

                <p class="text-gray-500 mt-2">
                    Try changing your search or filter.
                </p>

                <a
                    href="{{ route('clients.create') }}"
                    class="inline-block mt-5 bg-gray-900 text-white px-5 py-2.5 rounded-lg hover:bg-gray-800"
                >
                    Add Client
                </a>

            </div>

        @endif

    </div>

</div>

@endsection