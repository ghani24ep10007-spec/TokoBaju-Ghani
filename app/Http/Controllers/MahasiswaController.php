<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::orderBy('id', 'desc')->get();
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'    => 'required|string|max:20',
            'nama'   => 'required|string|max:100',
            'prodi'  => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
        ]);

        Mahasiswa::updateOrCreate(
            ['nim' => $validated['nim']],
            [
                'nama'   => $validated['nama'],
                'prodi'  => $validated['prodi'] ?? 'Sistem Informasi',
                'status' => $validated['status'] ?? 'Aktif',
            ]
        );

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil disimpan!');
    }

    public function destroy($id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        $mhs->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}
