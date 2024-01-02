<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'academic_year','description'];
    
	/**
	* Scope a query to only include active term.
	*
	* @return \Illuminate\Database\Eloquent\Builder
	*/
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
	
	/**
     * Get the items of the term
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
	
    /**
     * Get the users for the term
     *
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
