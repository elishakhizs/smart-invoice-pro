<aside class="w-64 bg-gray-900 text-white flex flex-col">

    <div class="p-6 border-b border-gray-800">
        <h1 class="text-2xl font-bold">
            Smart Invoice Pro
        </h1>
    </div>

    <nav class="flex-1 p-4 space-y-2">

        <a href="/dashboard" class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Dashboard
        </a>

        <a href="/invoices" class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Invoices
        </a>
        
        <a href="{{ route('invoices.dashboard') }}"class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Invoice Dashboard
        </a>

        <a href="/clients" class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Clients
        </a>

        <a href="/payments" class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Payments
        </a>

        <a href="/reports" class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Reports & Analytics
        </a>

        <a href="{{ route('company.settings') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-800 transition">
            Settings
        </a>
    </nav>

</aside>