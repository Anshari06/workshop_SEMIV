<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LokasiToko extends Model
{
    protected $table = 'lokasi_toko';
    protected $primaryKey = 'barcode';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['barcode', 'nama_toko', 'latitude', 'longitude', 'accuracy'];
    public $timestamps = false;

    public function kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'barcode_toko', 'barcode');
    }
}
