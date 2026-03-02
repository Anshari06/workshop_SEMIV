<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    protected $primaryKey = 'idrle_user';

    protected $fillable = [
        'iduser',
        'idrole',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole');
    }   
}
