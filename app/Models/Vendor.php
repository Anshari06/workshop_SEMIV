<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $fillable = ['nama_vendor'];
    public $timestamps = false;

    public function menus()
    {
        return $this->hasMany(menu::class, 'id_vendor');
    }
}
