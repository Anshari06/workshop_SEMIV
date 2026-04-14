<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class district extends Model
{
    protected $table = 'reg_districts';
    protected $fillable = ['id', 'regency_id', 'name'];
    public $timestamps = false;

     public function regency()
    {
        return $this->belongsTo(regencies::class, 'regency_id');
    }
}
