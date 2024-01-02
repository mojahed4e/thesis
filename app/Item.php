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
use App\Program;
use App\ThesisRequestDetails;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'picture', 'category_id','program_id', 'term_id', 'request_detail_id', 'status', 'created_by', 'modified_by', 'assigned_to', 'requested_by', 'approval_status', 'show_on_homepage', 'options'
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
        return $query->where('items.status', '!=', 3);
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
     * Get the category of the item
     *
     * @return \App\Category
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
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
	 * Get the request_detail_id of the item
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function requestdetails()
    {
        return $this->belongsToMany(ThesisRequestDetails::class);
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
