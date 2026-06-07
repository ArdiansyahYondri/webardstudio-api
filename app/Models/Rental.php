<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id', 'tanggal_sewa', 'tenggat_waktu', 'waktu_kembali', 
        'total_harga', 'nominal_denda', 'status_penyewaan'
    ];

    // Relasi: Penyewaan ini milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu penyewaan punya banyak detail barang
    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }
}