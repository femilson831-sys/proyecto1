<aside class="w-64 bg-gray-900 text-white p-5">

    <h1 class="text-2xl font-bold mb-8">🏨 JACHA TOURS</h1>

    <nav class="space-y-2">

        <a href="/admin" class="block p-2 rounded hover:bg-gray-700">📊 Dashboard</a>
        <a href="/admin/cholets" class="block p-2 rounded hover:bg-gray-700">🏠 Cholets</a>
        <a href="/admin/reservas" class="block p-2 rounded hover:bg-gray-700">📅 Reservas</a>
        <a href="/admin/users" class="block p-2 rounded hover:bg-gray-700">👤 Usuarios</a>

        <hr class="my-4 opacity-30">

        <form method="POST" action="/logout">
            @csrf
            <button class="w-full text-left p-2 bg-red-600 rounded hover:bg-red-700">
                🚪 Salir
            </button>
        </form>

    </nav>

</aside>