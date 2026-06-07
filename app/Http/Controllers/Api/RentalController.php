<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gear;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RentalController extends Controller
{
    // Menampilkan riwayat transaksi (Filter per user sesuai syarat UTS) [cite: 1025]
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            $rentals = Rental::with('rentalItems.gear')->latest()->get();
        } else {
            $rentals = Rental::with('rentalItems.gear')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar riwayat penyewaan',
            'data' => $rentals
        ]);
    }

    // Membuat transaksi penyewaan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.gear_id' => 'required|exists:gears,id',
            'items.*.durasi' => 'required|in:4,6,12,24', 
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            // Gunakan DB Transaction untuk mencegah data error di tengah jalan 
            $rental = DB::transaction(function () use ($validated, $request) {
                $totalHarga = 0;
                $rentalItems = [];
                $waktuSekarang = Carbon::now();
                $tenggatWaktuTerlama = $waktuSekarang->copy();

                foreach ($validated['items'] as $item) {
                    $gear = Gear::lockForUpdate()->find($item['gear_id']);

                    // Validasi stok server [cite: 812]
                    if ($gear->stok < $item['qty']) {
                        throw new Exception("Stok ({$gear->nama_gear}) tidak mencukupi. Sisa: {$gear->stok}");
                    }

                    // Penentuan harga otomatis dari durasi sewa
                    $hargaSatuan = 0;
                    if ($item['durasi'] == 4) $hargaSatuan = $gear->harga_4jam;
                    elseif ($item['durasi'] == 6) $hargaSatuan = $gear->harga_6jam;
                    elseif ($item['durasi'] == 12) $hargaSatuan = $gear->harga_12jam;
                    elseif ($item['durasi'] == 24) $hargaSatuan = $gear->harga_24jam;

                    $subtotal = $hargaSatuan * $item['qty'];
                    $totalHarga += $subtotal;

                    $rentalItems[] = [
                        'gear_id' => $gear->id,
                        'durasi' => $item['durasi'],
                        'qty' => $item['qty'],
                        'subtotal' => $subtotal,
                    ];

                    // Cari tenggat waktu paling lama dari barang yang disewa
                    $tenggatItem = $waktuSekarang->copy()->addHours($item['durasi']);
                    if ($tenggatItem->greaterThan($tenggatWaktuTerlama)) {
                        $tenggatWaktuTerlama = $tenggatItem;
                    }

                    // Kurangi stok barang [cite: 827]
                    $gear->decrement('stok', $item['qty']);
                }

                $rental = Rental::create([
                    'user_id' => $request->user()->id,
                    'tanggal_sewa' => $waktuSekarang,
                    'tenggat_waktu' => $tenggatWaktuTerlama,
                    'total_harga' => $totalHarga,
                    'status_penyewaan' => 'aktif'
                ]);

                // Menyimpan detail keranjang [cite: 835]
                $rental->rentalItems()->createMany($rentalItems);

                return $rental->load('rentalItems.gear');
            });

            return response()->json([
                'success' => true,
                'message' => 'Nota penyewaan berhasil dibuat',
                'data' => $rental
            ], 201);

        } catch (Exception $e) {
            // Error handling stok kurang dengan status 500 [cite: 993]
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500); 
        }
    }

    // Mengembalikan barang & Menghitung denda (Khusus Admin)
    public function returnRental(Request $request, Rental $rental)
    {
        if ($rental->status_penyewaan === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Penyewaan ini sudah selesai sebelumnya.'
            ], 400);
        }

        DB::transaction(function () use ($rental) {
            $waktuKembali = Carbon::now();
            $tenggatWaktu = Carbon::parse($rental->tenggat_waktu);
            $denda = 0;

            if ($waktuKembali->greaterThan($tenggatWaktu)) {
                $selisihJam = $tenggatWaktu->diffInHours($waktuKembali);
                if ($selisihJam > 0) {
                    $denda = $selisihJam * 10000;
                }
            }

            $rental->update([
                'waktu_kembali' => $waktuKembali,
                'nominal_denda' => $denda,
                'status_penyewaan' => 'selesai'
            ]);

            // Mengembalikan stok barang agar bisa disewa lagi
            foreach ($rental->rentalItems as $item) {
                $gear = Gear::find($item->gear_id);
                if ($gear) {
                    $gear->increment('stok', $item->qty);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Barang dikembalikan. Total denda: Rp' . number_format($rental->nominal_denda, 0, ',', '.'),
            'data' => $rental->fresh('rentalItems.gear')
        ]);
    }
}