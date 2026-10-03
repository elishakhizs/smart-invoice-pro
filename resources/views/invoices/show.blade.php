@extends('layouts.app')

@section('content')

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Invoice Header --}}
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <div class="flex justify-between items-start">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Invoice {{ $invoice->invoice_number }}
                    </h1>

                    <p class="text-gray-500 mt-1">
                        {{ $invoice->client->company_name ?? $invoice->client->name }}
                    </p>
                </div>

                <div class="text-right">
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold
                       @php
                            $statusClasses = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'sent' => 'bg-blue-100 text-blue-800',
                                'partial' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                            ];

                            $statusLabels = [
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'partial' => 'Partially Paid',
                                'paid' => 'Paid',
                            ];
                        @endphp

                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">

                            {{ $statusLabels[$invoice->status] ?? ucfirst($invoice->status) }}

                        </span>
                    </span>
                </div>

            </div>

        </div>


        {{-- Invoice Information --}}
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Invoice Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <p class="text-sm text-gray-500">
                        Client
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $invoice->client->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Issue Date
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $invoice->issue_date->format('d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Due Date
                    </p>

                    <p class="font-medium text-gray-900">
                        {{ $invoice->due_date->format('d M Y') }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Client Details --}}
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Bill To
            </h2>

            <div class="space-y-1">

                <p class="font-semibold text-gray-900">
                    {{ $invoice->client->name }}
                </p>

                @if($invoice->client->company_name)
                    <p class="text-gray-600">
                        {{ $invoice->client->company_name }}
                    </p>
                @endif

                @if($invoice->client->email)
                    <p class="text-gray-600">
                        {{ $invoice->client->email }}
                    </p>
                @endif

                @if($invoice->client->phone)
                    <p class="text-gray-600">
                        {{ $invoice->client->phone }}
                    </p>
                @endif

            </div>

        </div>


        {{-- Invoice Items --}}
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Invoice Items
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>
                        <tr class="border-b text-left">

                            <th class="py-3 px-2">
                                Description
                            </th>

                            <th class="py-3 px-2 text-right">
                                Quantity
                            </th>

                            <th class="py-3 px-2 text-right">
                                Unit Price
                            </th>

                            <th class="py-3 px-2 text-right">
                                Total
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach($invoice->items as $item)

                            <tr class="border-b">

                                <td class="py-3 px-2">
                                    {{ $item->description }}
                                </td>

                                <td class="py-3 px-2 text-right">
                                    {{ $item->quantity }}
                                </td>

                                <td class="py-3 px-2 text-right">
                                    R {{ number_format($item->unit_price, 2) }}
                                </td>

                                <td class="py-3 px-2 text-right font-medium">
                                    R {{ number_format($item->total, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Totals --}}
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

            <div class="flex justify-end">

                <div class="w-full md:w-80 space-y-3">

                    <div class="flex justify-between">
                        <span class="text-gray-600">
                            Subtotal
                        </span>

                        <span class="font-medium">
                            R {{ number_format($invoice->subtotal, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">
                            Tax
                        </span>

                        <span class="font-medium">
                            R {{ number_format($invoice->tax, 2) }}
                        </span>
                    </div>

                    <div class="border-t pt-3 flex justify-between text-lg">

                        <span class="font-bold">
                            Total
                        </span>

                        <span class="font-bold">
                            R {{ number_format($invoice->total, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        <!-- Payment Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Invoice Total</p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    R {{ number_format($invoice->total, 2) }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Total Paid</p>

                <p class="mt-2 text-2xl font-bold text-green-600">
                        R {{ number_format($invoice->total_paid, 2) }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Balance Due</p>

                    <p class="mt-2 text-2xl font-bold text-red-600">
                        R {{ number_format($invoice->balance_due, 2) }}
                    </p>
                </div>

            </div>

        </div>

        @if($invoice->balance_due > 0)

            <div class="bg-white rounded-lg shadow p-6 mb-6">

                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Record Payment
                </h2>

                <form method="POST" action="{{ route('payments.store', $invoice) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Payment Amount
                            </label>

                            <input type="number"step="0.01"min="0.01"max="{{ $invoice->balance_due }}"name="amount"id="amount"value="{{ old('amount') }}"required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="mt-1 text-xs text-gray-500">
                                Maximum payment:
                                R {{ number_format($invoice->balance_due, 2) }}
                            </p>
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date"class="block text-sm font-medium text-gray-700">
                                Payment Date
                            </label>

                            <input type="date"name="payment_date"id="payment_date"value="{{ old('payment_date', now()->toDateString()) }}"required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method"class="block text-sm font-medium text-gray-700">
                                Payment Method
                            </label>

                            <select name="payment_method" id="payment_method"class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select method</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="EFT">EFT</option>
                                <option value="Card">Card</option>
                                <option value="Other">Other</option>
                            </select>

                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Reference -->
                        <div>
                            <label for="reference"class="block text-sm font-medium text-gray-700">
                                Payment Reference
                            </label>

                            <input type="text" name="reference" id="reference"value="{{ old('reference') }}"placeholder="e.g. EFT-12345" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('reference')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes"class="block text-sm font-medium text-gray-700">
                                Notes
                            </label>

                            <textarea name="notes"id="notes"rows="3"class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"placeholder="Optional payment notes">
                                {{ old('notes') }}
                            </textarea>

                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>


                    <div class="mt-6">
                        <button type="submit"class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Record Payment
                        </button>
                    </div>

                </form>

            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-6">

            <div class="flex items-center justify-between mb-4">

                <h2 class="text-lg font-semibold text-gray-900">
                    Payment History
                </h2>

                <span class="text-sm text-gray-500">
                    {{ $invoice->payments->count() }} payment(s)
                </span>

            </div>

            @if($invoice->payments->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Date
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Method
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Reference
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Amount
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach($invoice->payments->sortByDesc('payment_date') as $payment)

                                <tr>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $payment->payment_date->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm">
                                        <a href="{{ route('payments.edit', $payment) }}"class="text-blue-600 hover:text-blue-800 mr-3">
                                        Edit
                                        </a>

                                        <form action="{{ route('payments.destroy', $payment) }}"method="POST"class="inline"onsubmit="return confirm('Are you sure you want to delete this payment?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $payment->payment_method ?: '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-sm">
                                        <a href="{{ route('payments.edit', $payment) }}"class="text-blue-600 hover:text-blue-800 mr-3">
                                        Edit
                                        </a>

                                        <form action="{{ route('payments.destroy', $payment) }}"method="POST"class="inline"onsubmit="return confirm('Are you sure you want to delete this payment?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $payment->reference ?: '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-sm">
                                        <a href="{{ route('payments.edit', $payment) }}"class="text-blue-600 hover:text-blue-800 mr-3">
                                        Edit
                                        </a>

                                        <form action="{{ route('payments.destroy', $payment) }}"method="POST"class="inline"onsubmit="return confirm('Are you sure you want to delete this payment?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                    <td class="px-4 py-3 text-sm font-semibold text-green-600 text-right">
                                        R {{ number_format($payment->amount, 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-sm">
                                        <a href="{{ route('payments.edit', $payment) }}"class="text-blue-600 hover:text-blue-800 mr-3">
                                        Edit
                                        </a>

                                        <form action="{{ route('payments.destroy', $payment) }}"method="POST"class="inline"onsubmit="return confirm('Are you sure you want to delete this payment?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="py-8 text-center text-gray-500">
                    No payments have been recorded for this invoice yet.
                </div>

            @endif

        </div>


        {{-- Notes --}}
        @if($invoice->notes)

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                    Notes
                </h2>

                <p class="text-gray-600">
                    {{ $invoice->notes }}
                </p>

            </div>

        @endif


        {{-- Actions --}}
        <div class="flex gap-3">

            <a
                href="{{ route('invoices.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
            >
                Back to Invoices
            </a>

            <a
                href="{{ route('invoices.edit', $invoice) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Edit Invoice
            </a>

            <a
                href="{{ route('invoices.pdf', $invoice) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
            >
                Download PDF
            </a>

            <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                 @csrf

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Send Invoice
                </button>
            </form>

            @if($invoice->status !== 'paid')

                <form action="{{ route('invoices.markPaid', $invoice) }}"method="POST"class="inline">
                    @csrf
                    @method('PATCH')

                    <button type="submit"class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                        Mark as Paid
                    </button>
                </form>

            @endif

        </div>

    </div>
</div>

@endsection