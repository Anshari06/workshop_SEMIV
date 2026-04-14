<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villages extends Model
{
    protected $table = 'reg_villages';
    protected $fillable = ['id', 'name', 'district_id'];
    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}

