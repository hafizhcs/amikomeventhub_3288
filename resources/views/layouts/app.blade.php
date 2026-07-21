<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmikomEventHub')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
    @yield('styles')
</head>


<body class="bg-slate-50 text-slate-900">

    <!-- ===== NAVBAR ===== -->
    <nav class="sticky top-0 z-40 mx-6 my-4 px-8 py-4
             backdrop-blur-xl
            border border-white/30 shadow-2xl
            flex justify-between items-center
            rounded-2xl">
    <!-- Logo -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo EventHub" class="w-8 h-8 object-contain">
        </div>
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">AmikomEventHub</a>
    </div>

    <!-- Menu -->
    <div class="hidden md:flex gap-10 font-medium">
        <a href="{{ route('home') }}"
            class="{{ request()->routeIs('home') ? 'text-indigo-600' : 'hover:text-indigo-600' }} transition">Home</a>
        <a href="{{ route('katalog') }}"
            class="{{ request()->routeIs('katalog') ? 'text-indigo-600' : 'hover:text-indigo-600' }} transition">Katalog</a>
        <a href="{{ route('tentang') }}"
            class="{{ request()->routeIs('tentang') ? 'text-indigo-600' : 'hover:text-indigo-600' }} transition">Tentang</a>
        <a href="{{ route('kontak') }}"
            class="{{ request()->routeIs('kontak') ? 'text-indigo-600' : 'hover:text-indigo-600' }} transition">Kontak</a>
        @auth
            <a href="{{ route('ticket') }}"
                class="{{ request()->routeIs('ticket') ? 'text-indigo-600' : 'hover:text-indigo-600' }} transition">Tiket Saya</a>
        @endauth
    </div>

    <!-- Auth Section -->
    <div class="flex items-center gap-4">
        @guest
            <!-- Sebelum Login -->
            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl font-semibold hover:bg-slate-100 transition">
                Login
            </a>
            <a href="{{ route('register') }}"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                Daftar
            </a>
        @else
            <!-- Setelah Login dengan Dropdown Avatar -->
            <div class="relative">
                <button id="userMenuBtn" class="bg-indigo-600 text-white font-bold rounded-full w-10 h-10 flex items-center justify-center">
                    {{ strtoupper(substr(Auth::user()->name,0,2)) }}
                </button>

                <!-- Dropdown -->
                <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border">
                    <div class="px-4 py-2 border-b">
                        <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-slate-100">Profil</a>
                    @if (Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="block px-4 py-2 hover:bg-slate-100">Dashboard Organisasi</a>
                    @elseif (! Auth::user()->isSuperAdmin())
                        <a href="{{ route('organizer.register') }}" class="block px-4 py-2 hover:bg-slate-100">Daftarkan Organisasi</a>
                    @endif
                    @if (Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-slate-100">Panel Admin</a>
                    @endif
                    <form action="{{ route('switch.account') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-100">Ganti Akun</button>
                    </form>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-100">Logout</button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>


    <!-- ===== KONTEN UTAMA ===== -->
    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-indigo-900 text-indigo-100 py-12 px-6 mt-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Brand & Description -->
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo EventHub"
                            class="w-8 h-8 object-contain">
                    </div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300">
                    Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                </p>
            </div>
            <!-- Navigation -->
            <div>
                <h4 class="text-white font-bold mb-4">Navigasi</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('katalog') }}" class="hover:text-white transition">Katalog Event</a></li>
                    <li><a href="{{ route('tentang') }}" class="hover:text-white transition">Tentang</a></li>
                    <li><a href="{{ route('kontak') }}" class="hover:text-white transition">Kontak</a></li>
                </ul>
            </div>
            <!-- Contact -->
            <div>
                <h4 class="text-white font-bold mb-4">Hubungi Kami</h4>
                <ul class="space-y-3">
                    <li>support@amikomeventhub.com</li>
                    <li>+62 812 3456 7890</li>
                    <li>Universitas AMIKOM Yogyakarta</li>
                </ul>
            </div>
        </div>
        <!-- Bottom Bar -->
        <div class="max-w-7xl mx-auto pt-8 mt-8 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2026 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('userMenuBtn');
        const menu = document.getElementById('userMenu');

        // Pastikan tombol dan menu ada di halaman ini
        if (btn && menu) {
            btn.addEventListener('click', function () {
                menu.classList.toggle('hidden');
            });

            // Klik di luar dropdown → tutup
            document.addEventListener('click', function (e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    });


</script>
    @yield('scripts')
</body>

</html>