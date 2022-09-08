<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'slug'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions() 
    {

        return $this->belongsToMany(Permission::class,'roles_permissions');
            
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() 
    {

        return $this->belongsToMany(User::class,'users_roles');
            
    }


//    public function permissions()
//    {
//
//        return $this->belongsToMany(Permission::class,'roles_permissions');
//
//    }

    /**
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }

    /**
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }


    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_roles');
    }
}
