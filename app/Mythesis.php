<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App;

use App\Tag;
use App\Category;
use App\Term;
use App\MyThesis;
use Illuminate\Database\Eloquent\Model;

class Mythesis extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'picture', 'category_id', 'term_id', 'status', 'date', 'created_by', 'modified_by', 'assigned_to', 'requested_by', 'approval_status', 'show_on_homepage', 'options'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];
	
	/**
	* Scope a query to only include active thesis.
	*
	* @return \Illuminate\Database\Eloquent\Builder
	*/
    public function scopeStatus($query)
    {
        return $query->where('status', '!=', 2);
    }

    /**
     * Get the category of the item
     *
     * @return \App\Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
	
	/**
     * Get the category of the item
     *
     * @return \App\Category
     */
    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get the tags of the item
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
	

    /**
     * Get the path to the picture
     *
     * @return string
     */
    public function path()
    {
        return "/storage/{$this->picture}";
    }
}
