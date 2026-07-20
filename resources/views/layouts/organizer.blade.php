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

    <aside class="w-64 bg-emerald-900 text-emerald-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-900 font-bold text-xl">
                OZ
            </div>
            <span class="text-lg font-bold text-white tracking-tight leading-tight">
                {{ auth()->user()->organization->name ?? 'Organizer' }}
            </span>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-400 mb-4 px-2">Menu</p>

            <a href="{{ route('organizer.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.dashboard') ? 'bg-emerald-800 text-white' : 'hover:bg-emerald-800' }} rounded-xl font-bold transition">
                Dashboard
            </a>

            <a href="{{ route('organizer.events.index') }}"
                class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.events.*') ? 'bg-emerald-800 text-white' : 'hover:bg-emerald-800' }} rounded-xl font-bold transition">
                Event Saya
            </a>

            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-800 rounded-xl font-bold transition">
                &larr; Kembali ke Beranda
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-3 hover:bg-emerald-800 rounded-xl font-bold transition">
                Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-10">
        <h1 class="text-2xl font-black mb-8">@yield('page_title', 'Dashboard')</h1>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-600 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>

</html>
