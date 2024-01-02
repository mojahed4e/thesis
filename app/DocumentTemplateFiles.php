<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplateFiles extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['files_name', 'folder_id', 'file_path', 'file_description', 'uploaded_by'];
    
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
