<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    protected $table = 'kunjungans';
    public $timestamps = true;
    protected $fillable = [
        'barcode_toko',
        'nama_toko',
        'lat_toko',
        'lng_toko',
        'accuracy_toko',
        'lat_sales',
        'lng_sales',
        'accuracy_sales',
        'jarak',
        'threshold',
        'threshold_efektif',
        'status',
    ];

    public function lokasiToko(): BelongsTo
    {
        return $this->belongsTo(LokasiToko::class, 'barcode_toko', 'barcode');
    }
}
