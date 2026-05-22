<header class="bg-white shadow-sm border-b px-6 py-4 flex items-center justify-between">

    <h2 class="text-2xl font-bold text-gray-800">
        Dashboard
    </h2>

    <div class="flex items-center gap-4">

        <span class="font-medium text-gray-700">
            {{ Auth::user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                Logout
            </button>
        </form>

    </div>

</header>