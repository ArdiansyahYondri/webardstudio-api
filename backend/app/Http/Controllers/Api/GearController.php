<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gear;
use Illuminate\Http\Request;

class GearController extends Controller
{
    // Menampilkan semua daftar alat (Bisa diakses siapa saja yang login)
    public function index()
    {
        $gears = Gear::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar katalog alat berhasil diambil',
            'data' => $gears
        ]);
    }

    // Menyimpan alat baru (Khusus Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gear' => 'required|string',
            'merk' => 'required|string',
            'harga_4jam' => 'required|numeric',
            'harga_6jam' => 'required|numeric',
            'harga_12jam' => 'required|numeric',
            'harga_24jam' => 'required|numeric',
            // Saya mengadaptasi validasi regex dari kode PHP lamamu agar sangat aman!
            'jaminan' => 'required|string|regex:/^[A-Za-z\s]+$/', 
            'status' => 'required|string',
            'stok' => 'required|numeric|min:0'
        ]);

        $gear = Gear::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alat baru berhasil ditambahkan',
            'data' => $gear
        ], 201);
    }

    // Menampilkan detail satu alat
    public function show(Gear $gear)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail alat',
            'data' => $gear
        ]);
    }

    // Mengubah data alat (Khusus Admin)
    public function update(Request $request, Gear $gear)
    {
        $validated = $request->validate([
            'nama_gear' => 'sometimes|string',
            'merk' => 'sometimes|string',
            'harga_4jam' => 'sometimes|numeric',
            'harga_6jam' => 'sometimes|numeric',
            'harga_12jam' => 'sometimes|numeric',
            'harga_24jam' => 'sometimes|numeric',
            'jaminan' => 'sometimes|string|regex:/^[A-Za-z\s]+$/',
            'status' => 'sometimes|string',
            'stok' => 'sometimes|numeric|min:0'
        ]);

        $gear->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data alat berhasil diperbarui',
            'data' => $gear
        ]);
    }

    // Menghapus data alat (Khusus Admin)
    public function destroy(Gear $gear)
    {
        $gear->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data alat berhasil dihapus'
        ]);
    }
}