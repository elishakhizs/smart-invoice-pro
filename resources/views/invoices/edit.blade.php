@extends('layouts.app')

@section('content')

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-sm rounded-lg p-6">

            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                Edit Invoice
            </h1>

            <form method="POST" action="{{ route('invoices.update', $invoice) }}">

                @csrf
                @method('PUT')

                {{-- Client --}}
                <div class="mb-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client
                    </label>

                    <select
                        name="client_id"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                        @foreach($clients as $client)

                            <option
                                value="{{ $client->id }}"
                                {{ $invoice->client_id == $client->id ? 'selected' : '' }}
                            >
                                {{ $client->company_name ?? $client->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Issue Date
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            value="{{ $invoice->issue_date->format('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            value="{{ $invoice->due_date->format('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>

                </div>

                {{-- Status --}}
                <div class="mb-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                        <option value="draft" {{ $invoice->status === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="sent" {{ $invoice->status === 'sent' ? 'selected' : '' }}>
                            Sent
                        </option>

                        <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>
                            Paid
                        </option>

                    </select>

                </div>

                {{-- Invoice Items --}}
                <div class="mb-6">

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Invoice Items
                    </h2>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead>

                                <tr class="border-b">

                                    <th class="text-left py-3 px-2">
                                        Description
                                    </th>

                                    <th class="text-left py-3 px-2">
                                        Quantity
                                    </th>

                                    <th class="text-left py-3 px-2">
                                        Unit Price
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($invoice->items as $item)

                                    <tr>

                                        <td class="py-3 px-2">

                                            <input
                                                type="hidden"
                                                name="items[{{ $item->id }}][id]"
                                                value="{{ $item->id }}"
                                            >

                                            <input
                                                type="text"
                                                name="items[{{ $item->id }}][description]"
                                                value="{{ $item->description }}"
                                                class="w-full border-gray-300 rounded-lg"
                                                required
                                            >

                                        </td>

                                        <td class="py-3 px-2">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][quantity]"
                                                value="{{ $item->quantity }}"
                                                min="0.01"
                                                step="0.01"
                                                class="w-full border-gray-300 rounded-lg"
                                                required
                                            >

                                        </td>

                                        <td class="py-3 px-2">

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][unit_price]"
                                                value="{{ $item->unit_price }}"
                                                min="0"
                                                step="0.01"
                                                class="w-full border-gray-300 rounded-lg"
                                                required
                                            >

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Notes --}}
                <div class="mb-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="4"
                        class="w-full border-gray-300 rounded-lg"
                    >{{ $invoice->notes }}</textarea>

                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Update Invoice
                    </button>

                    <a
                        href="{{ route('invoices.show', $invoice) }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>
</div>

@endsection