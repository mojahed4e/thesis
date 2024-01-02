<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplateSubfolders extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subfolder_name','folder_id', 'subfolder_description','created_by'];
    
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
     * Get the users for the term
     *
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
