<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Core\Database\Factories\SchoolFactory;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'motto',
        'domain',
        'level',
        'phone',
        'email',
        'address',
        'logo',
        'is_active',
    ];

    public function admins()
    {
        return $this->belongsToMany(\App\Models\User::class, 'school_user_admin', 'school_id', 'user_id') ?: collect();
    }

    // protected static function newFactory(): SchoolFactory
    // {
    //     // return SchoolFactory::new();
    // }
}
