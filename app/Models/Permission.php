<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'slug'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() 
    {

        return $this->belongsToMany(Role::class,'roles_permissions');
            
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() 
    {

        return $this->belongsToMany(User::class,'users_permissions');
            
    }

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
}
