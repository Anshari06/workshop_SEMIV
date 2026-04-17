<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = [
        'nama_customer',
        'province_id',
        'province_name',
        'regency_id',
        'regency_name',
        'village_id',
        'village_name',
        'foto_blob',
        'foto_path',
        'foto_mime',
    ];
}
