<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    protected $fillable = [
        'rental_id', 'gear_id', 'durasi', 'qty', 'subtotal'
    ];

    // Relasi balik ke induk Rental
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    // Relasi ke data Gear/Alat
    public function gear()
    {
        return $this->belongsTo(Gear::class);
    }
}