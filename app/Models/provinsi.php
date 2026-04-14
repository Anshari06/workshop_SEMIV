<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class provinsi extends Model
{
    protected $table = 'reg_provinces';
    protected $fillable = ['id', 'name'];

    public $timestamps = false;
    public function regencies()
    {
        return $this->hasMany(regencies::class, 'province_id', 'id');
    }


}
