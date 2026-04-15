<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $fillable = ['nama_vendor', 'iduser'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function menus()
    {
        return $this->hasMany(menu::class, 'id_vendor');
    }
}
