<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = 'roles';
    protected $guarded = ['id'];

    /**
     * Relation
     */
    public function users() {
        $this->hasMany(User::class, 'role_id', 'id');
    }
}
