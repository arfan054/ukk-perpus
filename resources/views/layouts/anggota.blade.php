<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-indigo-700 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fas fa-book-open text-white text-xl"></i>
                <span class="text-white font-bold text-lg">Perpustakaan</span>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-indigo-200 text-sm hidden md:block">
                    <i class="fas fa-user mr-1"></i> {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-white text-indigo-700 hover:bg-indigo-50 px-4 py-1.5 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- SIDEBAR + CONTENT -->
    <div class="max-w-7xl mx-auto px-4 py-6 flex gap-6">

        <!-- SIDEBAR -->
        <aside class="w-56 shrink-0 hidden md:block">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-indigo-50 p-4 border-b border-indigo-100">
                    <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg mx-auto">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <p class="text-center font-semibold text-gray-800 mt-2 text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-center text-xs text-indigo-500">Anggota</p>
                </div>

                <nav class="p-3 space-y-1">
                    <a href="{{ route('anggota.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('anggota.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-home w-4 text-center"></i> Dashboard
                    </a>
                    <a href="{{ route('anggota.buku') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('anggota.buku') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-book w-4 text-center"></i> Daftar Buku
                    </a>
                    <a href="{{ route('anggota.pengembalian') }}" class="flex items-center gap-3 p-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="font-medium">Pengembalian</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 min-w-0">
            @yield('content')
        </main>
    </div>

</body>

</html>