<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $collection = 'menus';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'parent_id', 'slug', 'order_number', 'status'];


    /**
     * @return HasMany
     */

    public function children()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

     /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Menu','parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    public function page()
    {
        return $this->hasOne(DynamicPage::class, 'menu_id');
    }
    

    /**
     * @param $date
     * @return false|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }

    public function history()
    {
        return $this->morphMany(CrudHistory::class, 'linkable')->with('user');
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }
}
