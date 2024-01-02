<?php

namespace App;

use App\Item;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ThesisRubricDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const UPDATED_AT = null;
    protected $fillable = ['rubric_template_id', 'item_id','created_by','rubric_term', 'does_not_meet_expectations', 'meets_expectations', 'exceeds_expectations', 'rubric_comments','rubric_type'];
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
        return $query->where('thesis_rubric_details.status', '!=', 2);
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
