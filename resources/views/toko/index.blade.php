@extends('layouts.app')

@section('title', 'Toko Baju Ghani - Katalog Busana Distro & E-Commerce')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Hero Section Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-fuchsia-950 via-slate-900 to-indigo-950 p-8 border border-fuchsia-500/20 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20 text-xs font-semibold mb-3">
                <i class="fa-solid fa-fire text-amber-400"></i> Koleksi Fashion Distro Original
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Koleksi Busana Ghani</h1>
            <p class="text-slate-400 mt-2 text-sm max-w-2xl">
                Katalog busana distro kekinian dengan arsitektur <strong>Laravel 10</strong>, dikemas dalam container <strong>Docker</strong> dan didukung basis data <strong>PostgreSQL 15</strong>.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="toggleAddProductModal()" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-fuchsia-600 to-pink-600 text-white font-bold text-sm shadow-lg shadow-fuchsia-600/30 hover:from-fuchsia-500 hover:to-pink-500 transition flex items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i> Tambah Produk Baru
            </button>
        </div>
    </div>

    <!-- Category Filter Bar -->
    @php
        $categories = ['Semua', 'Kaos & T-Shirt', 'Kemeja', 'Outerwear', 'Celana', 'Batik Modern'];
        $currentKat = request('kategori', 'Semua');
    @endphp
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        @foreach($categories as $cat)
            <a href="{{ route('toko.index', ['kategori' => $cat === 'Semua' ? null : $cat]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ ($currentKat === $cat || (!$currentKat && $cat === 'Semua')) ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/30' : 'bg-slate-900/90 text-slate-400 hover:text-white border border-slate-800 hover:border-slate-700' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $prod)
            <div class="group rounded-2xl bg-slate-900/90 border border-slate-800 hover:border-fuchsia-500/40 overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-fuchsia-500/10 transition flex flex-col">
                <!-- Image Container with Badges -->
                <div class="relative h-64 overflow-hidden bg-slate-950">
                    <img src="{{ $prod->gambar ?: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80' }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                         alt="{{ $prod->nama_produk }}">
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-slate-950/80 backdrop-blur-md text-fuchsia-400 border border-fuchsia-500/30">
                            {{ $prod->kategori }}
                        </span>
                        @if($prod->ukuran)
                        <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-950/80 backdrop-blur-md text-slate-300 border border-slate-700">
                            {{ $prod->ukuran }}
                        </span>
                        @endif
                    </div>

                    <!-- Delete Button Form -->
                    <form action="{{ route('toko.destroy', $prod->id) }}" method="POST"
                          onsubmit="return confirm('Hapus produk {{ $prod->nama_produk }} dari katalog?');"
                          class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Produk" class="w-8 h-8 rounded-lg bg-rose-600/90 hover:bg-rose-500 text-white flex items-center justify-center shadow-lg transition">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>

                <!-- Product Body Details -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-white group-hover:text-fuchsia-400 transition leading-snug">
                            {{ $prod->nama_produk }}
                        </h3>
                        <p class="text-xs text-slate-400 line-clamp-2">
                            {{ $prod->deskripsi ?: 'Bahan premium berkualitas tinggi nyaman untuk aktivitas harian.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-500 block">Harga</span>
                            <div class="text-lg font-black text-white">
                                Rp {{ number_format($prod->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <button onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->nama_produk) }}', {{ $prod->harga }}, '{{ $prod->gambar }}')"
                                class="px-4 py-2.5 rounded-xl bg-fuchsia-600/20 hover:bg-fuchsia-600 text-fuchsia-300 hover:text-white border border-fuchsia-500/30 text-xs font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500 bg-slate-900/40 rounded-3xl border border-slate-800">
                <i class="fa-solid fa-shirt text-4xl block mb-3 opacity-30 text-fuchsia-400"></i>
                <h4 class="text-base font-bold text-white">Belum ada produk di kategori ini</h4>
                <p class="text-xs text-slate-400 mt-1 mb-4">Tambahkan produk baru ke basis data PostgreSQL.</p>
                <button onclick="toggleAddProductModal()" class="px-4 py-2 rounded-xl bg-fuchsia-600 text-white text-xs font-bold">
                    + Tambah Produk Sekarang
                </button>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Tambah Produk Baru -->
<div id="add-product-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-fuchsia-600/20 text-fuchsia-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-shirt"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Tambah Produk Baru</h3>
                    <p class="text-xs text-slate-400">Data akan tersimpan ke PostgreSQL</p>
                </div>
            </div>
            <button onclick="toggleAddProductModal()" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('toko.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nama Busana / Produk</label>
                <input type="text" name="nama_produk" required placeholder="Contoh: Kaos Oversize Ghani Signature"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 transition">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:outline-none focus:border-fuchsia-500 transition">
                        <option value="Kaos & T-Shirt">Kaos & T-Shirt</option>
                        <option value="Kemeja">Kemeja</option>
                        <option value="Outerwear">Outerwear</option>
                        <option value="Celana">Celana</option>
                        <option value="Batik Modern">Batik Modern</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Ukuran</label>
                    <select name="ukuran" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:outline-none focus:border-fuchsia-500 transition">
                        <option value="All Size">All Size</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" required min="1000" step="500" placeholder="125000"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Stok</label>
                    <input type="number" name="stok" required min="0" value="15"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:outline-none focus:border-fuchsia-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">URL Gambar (Opsional)</label>
                <input type="url" name="gambar" placeholder="https://images.unsplash.com/..."
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="2" placeholder="Bahan katun combed 24s sablon discharge..."
                          class="w-full px-4 py-2 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 transition"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="toggleAddProductModal()" class="w-1/2 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                    Batal
                </button>
                <button type="submit" class="w-1/2 py-2.5 rounded-xl bg-fuchsia-600 hover:bg-fuchsia-500 text-white font-bold text-xs transition shadow-lg shadow-fuchsia-600/30">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAddProductModal() {
        const modal = document.getElementById('add-product-modal');
        modal.classList.toggle('hidden');
    }
</script>
@endsection
