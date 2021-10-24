<?php

namespace MohamedReda\Authorizable\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * @var array
     */
    protected $casts = ['permissions' => 'array'];

    /**
     * @var array
     */
    protected $fillable = [
        'permissions',
    ];

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
