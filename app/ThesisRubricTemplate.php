<?php

namespace App;

use App\Item;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ThesisRubricTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const UPDATED_AT = null;
    protected  $table = 'thesis_rubric_template';
    /**
     * Get the items of the tag
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
    /**
    * Scope a query to only include active thesis.
    *
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeStatus($query)
    {
        return $query->where('thesis_rubric_template.status', '!=', 2);
    }
    /**
     * Get the items of the tag
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
