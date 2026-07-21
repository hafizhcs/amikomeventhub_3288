<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Organizer - AmikomEventHub')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-emerald-900 text-emerald-100 flex flex-col p-6 justify-between sticky top-0 h-screen">
        <div class="space-y-8">
            <!-- Brand / Nama Organisasi -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-900 font-bold text-xl shadow-sm">
                    OZ
                </div>
                <span class="text-lg font-bold text-white tracking-tight leading-tight line-clamp-1">
                    {{ auth()->user()->organization->name ?? 'Organizer' }}
                </span>
            </div>

            <!-- Navigasi Menu -->
            <nav class="space-y-2">
                <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-400 mb-4 px-2">Menu</p>

                <a href="{{ route('organizer.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition {{ request()->routeIs('organizer.dashboard') ? 'bg-emerald-800 text-white shadow-sm' : 'hover:bg-emerald-800/60 text-emerald-200' }}">
                    Dashboard
                </a>

                <a href="{{ route('organizer.events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition {{ request()->routeIs('organizer.events.*') ? 'bg-emerald-800 text-white shadow-sm' : 'hover:bg-emerald-800/60 text-emerald-200' }}">
                    Event Saya
                </a>
            </nav>
        </div>

        <!-- Tombol Aksi Bawah (Kembali & Keluar) -->
        <div class="space-y-2 pt-6 border-t border-emerald-800/60">
            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-800/60 rounded-xl font-bold transition text-emerald-200 text-sm">
                Kembali ke Beranda
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 hover:bg-red-500/20 hover:text-red-200 text-emerald-200 rounded-xl font-bold transition text-sm">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">
        <h1 class="text-2xl font-black mb-8">@yield('page_title', 'Dashboard')</h1>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold border border-green-100">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-600 text-sm font-semibold border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>