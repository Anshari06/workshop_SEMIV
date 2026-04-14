<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_pesanan extends Model
{
    protected $table = 'detail_pesanans';
    protected $fillable = ['jumlah', 'subtotal', 'catatan', 'id_menu', 'id_pesanan'];
    public $timestamps = true;

    public function menu()
    {
        return $this->belongsTo(menu::class, 'id_menu');
    }

    public function pesanan()
    {
        return $this->belongsTo(pesanan::class, 'id_pesanan');
    }
}
