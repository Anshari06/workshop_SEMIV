<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pesanan extends Model
{
    protected $table = 'pesanans';
    protected $fillable = ['nama', 'total', 'metode_pembayaran', 'status'];
    public $timestamps = true;

    public function detail_pesanans()
    {
        return $this->hasMany(detail_pesanan::class, 'id_pesanan');
    }
}
