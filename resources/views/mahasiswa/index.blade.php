@extends('layouts.app')

@section('title', 'Data Mahasiswa | Toko Baju Ghani & Praktikum Web')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header Hero Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-fuchsia-950 p-8 border border-indigo-500/20 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-semibold mb-3">
                <i class="fa-solid fa-graduation-cap"></i> Modul Praktikum Pemrograman Web SI
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Manajemen Data Mahasiswa</h1>
            <p class="text-slate-400 mt-2 text-sm max-w-2xl">
                Sistem CRUD data mahasiswa terintegrasi basis data relasional <strong>PostgreSQL 15</strong> dan dikontainerisasi menggunakan <strong>Docker Compose</strong>.
            </p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="flex items-center gap-4">
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 text-center min-w-[110px]">
                <span class="text-2xl font-black text-indigo-400">{{ count($mahasiswa) }}</span>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Total Mhs</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 text-center min-w-[110px]">
                <span class="text-2xl font-black text-emerald-400">{{ $mahasiswa->where('status', 'Aktif')->count() }}</span>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Aktif</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid: Form (Left) & Table (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Form Card (1 Col) -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 shadow-xl space-y-5">
            <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Tambah / Update Data</h3>
                    <p class="text-xs text-slate-400">Gunakan NIM yang sama untuk memperbarui data</p>
                </div>
            </div>

            <form action="{{ route('mahasiswa.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">NIM</label>
                    <input type="text" name="nim" required placeholder="Contoh: 24EP10007"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Contoh: Rizqi Ghani Adinata"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Program Studi</label>
                    <select name="prodi" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="Sistem Informasi" selected>Sistem Informasi</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sains Data">Sains Data</option>
                        <option value="Bisnis Digital">Bisnis Digital</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Status Akademik</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                        <option value="Aktif" selected>Aktif</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Lulus">Lulus</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Simpan ke PostgreSQL
                </button>
            </form>
        </div>

        <!-- Table Card (2 Cols) -->
        <div class="lg:col-span-2 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base">Daftar Mahasiswa Terdaftar</h3>
                        <p class="text-xs text-slate-400">Tabel PostgreSQL: <code class="text-indigo-400">mahasiswa</code></p>
                    </div>
                </div>

                <!-- Live Client-side Search -->
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-500 text-xs"></i>
                    <input type="text" id="mhs-search" onkeyup="filterMahasiswa()" placeholder="Cari nama atau NIM..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3.5 px-5">NIM</th>
                            <th class="py-3.5 px-5">Nama Lengkap</th>
                            <th class="py-3.5 px-5">Program Studi</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="mhs-tbody" class="divide-y divide-slate-800/80 text-xs">
                        @forelse($mahasiswa as $m)
                        <tr class="hover:bg-slate-800/40 transition mhs-row">
                            <td class="py-4 px-5 font-mono font-bold text-indigo-400 mhs-nim">{{ $m->nim }}</td>
                            <td class="py-4 px-5 font-semibold text-white mhs-nama">{{ $m->nama }}</td>
                            <td class="py-4 px-5 text-slate-400">{{ $m->prodi }}</td>
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $m->status === 'Aktif' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ $m->status }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus mahasiswa {{ $m->nama }} ({{ $m->nim }})?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Mahasiswa" class="p-1.5 rounded-lg text-rose-400 hover:text-white hover:bg-rose-500/20 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <i class="fa-regular fa-folder-open text-3xl block mb-2 opacity-40"></i>
                                Belum ada data mahasiswa di PostgreSQL.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40 text-[11px] text-slate-500 flex items-center justify-between">
                <span>Total Record: {{ count($mahasiswa) }}</span>
                <span>Tersimpan di Container Docker <code class="text-slate-400">postgres_db</code></span>
            </div>
        </div>

    </div>

</div>

<script>
    function filterMahasiswa() {
        const query = document.getElementById('mhs-search').value.toLowerCase();
        const rows = document.querySelectorAll('.mhs-row');
        rows.forEach(row => {
            const nim = row.querySelector('.mhs-nim').textContent.toLowerCase();
            const nama = row.querySelector('.mhs-nama').textContent.toLowerCase();
            if (nim.includes(query) || nama.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
