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

use App\Item;
use App\User;
use App\ThesisRequestDetails;
use Illuminate\Database\Eloquent\Model;

class TermProgressChecklist extends Model
{
	const UPDATED_AT = null;
	
	protected  $table = 'terms_progress_checklist';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'track_id', 'document_title', 'document_file_path','created_date','checklist_type','approval_status','upload_file_status'];

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
     * Get the items of the tag
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
	/**
     * Get the items of the tag
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function requests()
    {
        return $this->belongsToMany(ThesisRequestDetails::class);
    }
}
