@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">
        <!-- Logo / Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-purple-700">AmikomEventHub</h1>
            <h2 class="text-xl font-semibold mt-2">Buat Akun Baru</h2>
            <p class="text-gray-600">Daftar untuk mulai memesan tiket event</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" class="w-full border rounded-lg p-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Masukkan nama lengkap" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg p-2 focus:ring-purple-500 focus:border-purple-500" placeholder="nama@email.com" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border rounded-lg p-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Minimal 6 karakter" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded-lg p-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Ketik ulang password" required>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg font-semibold hover:bg-purple-700">
                Daftar Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
