<header class=\"bg-white shadow px-6 py-4 flex justify-between items-center\">

    <div>
        <h2 class=\"text-2xl font-semibold\">
            Dashboard
        </h2>
    </div>

    <div class=\"flex items-center gap-4\">

        <span class=\"text-gray-700\">
            {{ Auth::user()->name }}
        </span>

        <form method=\"POST\" action=\"{{ route('logout') }}\">
            @csrf

            <button class=\"bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded\">
                Logout
            </button>
        </form>

    </div>

</header>