<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class regencies extends Model
{
    protected $table = 'reg_regencies';
    protected $fillable = ['id', 'province_id', 'name'];
    public $timestamps = false;

     public function province()
    {
        return $this->belongsTo(provinsi::class, 'province_id');
    }
}
