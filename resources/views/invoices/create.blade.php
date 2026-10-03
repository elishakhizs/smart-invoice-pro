@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-700">
        <strong>Please fix the following:</strong>

        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-sm rounded-lg p-6">

            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                Create Invoice
            </h1>

            <form method="POST" action="{{ route('invoices.store') }}">
                @csrf

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

                        <option value="">
                            Select Client
                        </option>

                        @foreach($clients as $client)

                            <option
                                value="{{ $client->id }}"
                                {{ old('client_id') == $client->id ? 'selected' : '' }}
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
                            value="{{ old('issue_date', now()->format('Y-m-d')) }}"
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
                            value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
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

                        <option value="draft">
                            Draft
                        </option>

                        <option value="sent">
                            Sent
                        </option>

                        <option value="paid">
                            Paid
                        </option>

                    </select>

                </div>

                {{-- Invoice Items --}}
                <div class="mb-6">

                    <div class="flex justify-between items-center mb-4">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Invoice Items
                        </h2>

                        <button
                            type="button"
                            id="add-item"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            + Add Item
                        </button>

                    </div>

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

                                    <th class="text-right py-3 px-2">
                                        Total
                                    </th>

                                    <th class="py-3 px-2">
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="invoice-items">

                                <tr class="invoice-item">

                                    <td class="py-3 px-2">

                                        <input
                                            type="text"
                                            name="items[0][description]"
                                            class="w-full border-gray-300 rounded-lg"
                                            placeholder="Item description"
                                            required
                                        >

                                    </td>

                                    <td class="py-3 px-2">

                                        <input
                                            type="number"
                                            name="items[0][quantity]"
                                            value="1"
                                            min="0.01"
                                            step="0.01"
                                            class="quantity w-full border-gray-300 rounded-lg"
                                            required
                                        >

                                    </td>

                                    <td class="py-3 px-2">

                                        <input
                                            type="number"
                                            name="items[0][unit_price]"
                                            value="0"
                                            min="0"
                                            step="0.01"
                                            class="unit-price w-full border-gray-300 rounded-lg"
                                            required
                                        >

                                    </td>

                                    <td class="py-3 px-2 text-right">

                                        <span class="item-total">
                                            R 0.00
                                        </span>

                                    </td>

                                    <td class="py-3 px-2 text-center">

                                        <button
                                            type="button"
                                            class="remove-item text-red-600 hover:text-red-800"
                                        >
                                            Remove
                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Totals --}}
                <div class="flex justify-end mb-6">

                <div class="w-full md:w-80 space-y-3">

        {{-- Tax Rate --}}
        <div>
            <label
                for="tax_rate"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Tax / VAT Rate (%)
            </label>

            <input
                type="number"
                name="tax_rate"
                id="tax_rate"
                value="{{ old('tax_rate', 0) }}"
                min="0"
                max="100"
                step="0.01"
                class="w-full border-gray-300 rounded-lg"
                required
            >

            @error('tax_rate')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Subtotal --}}
        <div class="flex justify-between">

            <span class="text-gray-600">
                Subtotal
            </span>

            <span
                id="subtotal"
                class="font-medium"
            >
                R 0.00
            </span>

        </div>

        {{-- Tax --}}
        <div class="flex justify-between">

            <span class="text-gray-600">
                Tax
            </span>

            <span
                id="tax"
                class="font-medium"
            >
                R 0.00
            </span>

        </div>

        {{-- Total --}}
        <div class="border-t pt-3 flex justify-between text-lg">

            <span class="font-bold">
                Total
            </span>

            <span
                id="total"
                class="font-bold"
            >
                R 0.00
            </span>

        </div>

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
                        placeholder="Optional notes"
                    >{{ old('notes') }}</textarea>

                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Create Invoice
                    </button>

                    <a
                        href="{{ route('invoices.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>
</div>


<script>

let itemIndex = 1;

function calculateTotals() {

    let subtotal = 0;

    document.querySelectorAll('.invoice-item').forEach(row => {

        const quantity = parseFloat(
            row.querySelector('.quantity')?.value
        ) || 0;

        const unitPrice = parseFloat(
            row.querySelector('.unit-price')?.value
        ) || 0;

        const itemTotal = quantity * unitPrice;

        const itemTotalElement = row.querySelector('.item-total');

        if (itemTotalElement) {
            itemTotalElement.textContent =
                'R ' + itemTotal.toFixed(2);
        }

        subtotal += itemTotal;
    });

    const taxRate = parseFloat(
        document.getElementById('tax_rate')?.value
    ) || 0;

    const tax = subtotal * (taxRate / 100);

    const total = subtotal + tax;

    document.getElementById('subtotal').textContent =
        'R ' + subtotal.toFixed(2);

    document.getElementById('tax').textContent =
        'R ' + tax.toFixed(2);

    document.getElementById('total').textContent =
        'R ' + total.toFixed(2);
}


document.getElementById('add-item').addEventListener('click', function () {

    const tbody = document.getElementById('invoice-items');

    const row = document.createElement('tr');

    row.className = 'invoice-item';

    row.innerHTML = `
        <td class="py-3 px-2">

            <input
                type="text"
                name="items[${itemIndex}][description]"
                class="w-full border-gray-300 rounded-lg"
                placeholder="Item description"
                required
            >

        </td>

        <td class="py-3 px-2">

            <input
                type="number"
                name="items[${itemIndex}][quantity]"
                value="1"
                min="0.01"
                step="0.01"
                class="quantity w-full border-gray-300 rounded-lg"
                required
            >

        </td>

        <td class="py-3 px-2">

            <input
                type="number"
                name="items[${itemIndex}][unit_price]"
                value="0"
                min="0"
                step="0.01"
                class="unit-price w-full border-gray-300 rounded-lg"
                required
            >

        </td>

        <td class="py-3 px-2 text-right">

            <span class="item-total">
                R 0.00
            </span>

        </td>

        <td class="py-3 px-2 text-center">

            <button
                type="button"
                class="remove-item text-red-600 hover:text-red-800"
            >
                Remove
            </button>

        </td>
    `;

    tbody.appendChild(row);

    itemIndex++;

});


document.addEventListener('input', function (event) {

    if (
        event.target.classList.contains('quantity') ||
        event.target.classList.contains('unit-price') ||
        event.target.id === 'tax_rate'
    ) {
        calculateTotals();
    }

});


document.addEventListener('click', function (event) {

    if (event.target.classList.contains('remove-item')) {

        const rows = document.querySelectorAll('.invoice-item');

        if (rows.length > 1) {

            event.target.closest('.invoice-item').remove();

            calculateTotals();

        }

    }

});


calculateTotals();

</script>

@endsection