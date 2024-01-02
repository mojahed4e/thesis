<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingLogs extends Model
{
	const UPDATED_AT = null;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'manager_id', 'supervisor_id', 'panel_id', 'request_detail_id', 'milestone_achived_last_meeting', 'discussed_this_meeting', 'next_meeting_agenda', 'meeting_date', 'next_meeting_date', 'meeting_log_type', 'meeting_log_seq', 'student_submit_status', 'supervisor_approval_status', 'manager_approval_status', 'panel_approval_status'];
    
    /**
     * Get the users for the role
     *
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
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
}
