<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gear extends Model
{
    protected $fillable = [
        'nama_gear', 'merk', 'harga_4jam', 'harga_6jam', 
        'harga_12jam', 'harga_24jam', 'jaminan', 'status', 'stok'
    ];

    // Relasi: Satu gear bisa ada di banyak detail penyewaan
    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }
}