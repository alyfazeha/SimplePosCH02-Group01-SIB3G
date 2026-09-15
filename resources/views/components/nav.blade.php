<nav class="bg-slate-900 text-white px-6 py-4 flex items-center justify-between shadow-md">

    <h1 class="text-xl font-bold">
        Simple POS
    </h1>

    <div class="flex gap-6">

        <a href="{{ route('pos.create') }}"
           class="{{ request()->routeIs('pos.create')
                    ? 'text-blue-400 font-semibold border-b-2 border-blue-400 pb-1'
                    : 'text-gray-300 hover:text-white transition' }}">
            Kasir
        </a>

        <a href="{{ route('transactions.index') }}"
           class="{{ request()->routeIs('transactions.index')
                    ? 'text-blue-400 font-semibold border-b-2 border-blue-400 pb-1'
                    : 'text-gray-300 hover:text-white transition' }}">
            Transaksi
        </a>

    </div>

</nav>