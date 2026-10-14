@extends('layouts.app')

@section('title', 'Toko Baju Ghani - Katalog Busana')

@section('content')
<header class="border-b border-slate-800 bg-slate-900/90 py-4 px-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-fuchsia-600 flex items-center justify-center">
            <i class="fa-solid fa-shirt text-white"></i>
        </div>
        <span class="text-xl font-extrabold text-white">TokoBaju-Ghani</span>
    </div>
    <div class="text-xs text-fuchsia-400 font-mono">Laravel 10 Blade + Docker</div>
</header>

<main class="max-w-7xl mx-auto px-4 py-8 space-y-8">
    <div class="rounded-3xl bg-gradient-to-r from-fuchsia-950 via-slate-900 to-indigo-950 p-8 border border-fuchsia-500/20">
        <h1 class="text-3xl font-extrabold text-white">Koleksi Busana Ghani</h1>
        <p class="text-slate-400 mt-2">Katalog resmi pakaian distro premium dengan Laravel & PostgreSQL.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $prod)
            <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
                <img src="{{ $prod->gambar }}" class="w-full h-60 object-cover" alt="{{ $prod->nama_produk }}">
                <div class="p-5 space-y-2">
                    <span class="text-xs font-bold text-fuchsia-400">{{ $prod->kategori }}</span>
                    <h3 class="text-lg font-bold text-white">{{ $prod->nama_produk }}</h3>
                    <p class="text-xs text-slate-400">{{ $prod->deskripsi }}</p>
                    <div class="text-xl font-extrabold text-white pt-2">
                        Rp {{ number_format($prod->harga, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                Belum ada produk di database.
            </div>
        @endforelse
    </div>
</main>
@endsection
