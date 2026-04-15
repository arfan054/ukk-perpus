<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Perpustakaan | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-indigo-900 text-white p-6 hidden md:block shrink-0">
            <h1 class="text-2xl font-bold mb-8 italic"><i class="fas fa-book-reader mr-2"></i> LibAdmin</h1>
            <nav>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-800 transition' }}">
                            <i class="fas fa-home mr-3"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.buku') }}" class="block py-2 px-4 rounded {{ request()->is('buku*') ? 'bg-indigo-700' : 'hover:bg-indigo-800 transition' }}">
                            <i class="fas fa-book mr-3"></i>Data Buku
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.anggota') }}" class="block py-2 px-4 rounded {{ request()->is('anggota*') ? 'bg-indigo-700' : 'hover:bg-indigo-800 transition' }}">
                            <i class="fas fa-user mr-3"></i>Data Anggota
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transaksi') }}" class="block py-2 px-4 rounded {{ request()->is('transaksi*') ? 'bg-indigo-700' : 'hover:bg-indigo-800 transition' }}">
                            <i class="fas fa-exchange mr-3"></i>Data Peminjaman
                        </a>
                    </li>
                    <div class="mt-auto pt-10">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>

                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center p-3 rounded-lg text-red-400 hover:bg-red-900/20 hover:text-red-300 transition group">
                            <i class="fas fa-sign-out-alt mr-3 group-hover:translate-x-1 transition-transform"></i>
                            <span class="font-semibold">Keluar Aplikasi</span>
                        </a>
                    </div>
                </ul>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-semibold text-gray-800">@yield('page_title')</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Halo, Admin Super</span>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=4338ca&color=fff" class="w-10 h-10 rounded-full border-2 border-white shadow">
                </div>
            </header>

            @yield('content')
        </main>
    </div>
</body>

</html>