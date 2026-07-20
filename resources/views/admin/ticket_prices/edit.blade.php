@extends('layouts.admin')

@section('title', 'Edit Dynamic Pricing')

@section('content')

<div class="container mx-auto px-6 py-6">

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6">

        <form action="{{ route('admin.ticket-prices.update', $ticketPrice->id) }}" method="POST">

            @csrf
            @method('PUT')

            {{-- Event --}}
            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Event
                </label>

                <select name="event_id"
                    class="w-full border rounded-lg px-4 py-2">

                    @foreach($events as $event)

                        <option value="{{ $event->id }}"
                            {{ old('event_id', $ticketPrice->event_id) == $event->id ? 'selected' : '' }}>

                            {{ $event->title }}

                        </option>

                    @endforeach

                </select>
            </div>

            {{-- Kategori --}}
            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Kategori Harga
                </label>

                <select name="category"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="Early Bird"
                        {{ old('category', $ticketPrice->category) == 'Early Bird' ? 'selected' : '' }}>
                        Early Bird
                    </option>

                    <option value="Presale 1"
                        {{ old('category', $ticketPrice->category) == 'Presale 1' ? 'selected' : '' }}>
                        Presale 1
                    </option>

                    <option value="Presale 2"
                        {{ old('category', $ticketPrice->category) == 'Presale 2' ? 'selected' : '' }}>
                        Presale 2
                    </option>

                    <option value="Regular"
                        {{ old('category', $ticketPrice->category) == 'Regular' ? 'selected' : '' }}>
                        Regular
                    </option>

                </select>
            </div>

            {{-- Harga --}}
            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Harga
                </label>

                <input
                    type="number"
                    name="price"
                    value="{{ old('price', $ticketPrice->price) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            {{-- Tanggal Mulai --}}
            <div class="mb-5">
                <label class="block font-semibold mb-2">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date', \Carbon\Carbon::parse($ticketPrice->start_date)->format('Y-m-d')) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            {{-- Tanggal Berakhir --}}
            <div class="mb-6">
                <label class="block font-semibold mb-2">
                    Tanggal Berakhir
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ old('end_date', \Carbon\Carbon::parse($ticketPrice->end_date)->format('Y-m-d')) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('admin.ticket-prices.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">

                    Update

                </button>

            </div>

        </form>

    </div>

</div>

@endsection