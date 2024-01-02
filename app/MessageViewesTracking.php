<?php

namespace App;

use App\Item;
use App\User;
use App\ThesisProgressTrackings;
use Illuminate\Database\Eloquent\Model;

class MessageViewesTracking extends Model
{
    //
	const UPDATED_AT = null;
	
	protected  $table = 'message_viewes_tracking';
	
	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'track_id', 'user_id', 'view_flag'];
	
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
    public function tracking()
    {
        return $this->belongsToMany(ThesisProgressTrackings::class);
    }
}