<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    protected $table = 'menus';
    protected $fillable = ['nama_menu', 'harga', 'path_gambar', 'id_vendor'];
    public $timestamps = false;
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }
}
