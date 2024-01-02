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
use App\Term;
use App\GroupMember;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;	

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email', 'availabe_flage', 'program_availability', 'course_title', 'objectguid','password', 'picture' ,'role_id','term_id','program_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	/**
	* Scope a query to only include active supervisors.
	*
	* @return \Illuminate\Database\Eloquent\Builder
	*/
    public function scopeAvailable($query)
    {
        return $query->where('users.availabe_flage', '!=', 2);
    }
	
	/**
     * Set the user's thesis request flag.
     *
     * @param  string  $value
     * @return void
     */
    private function CustomAttribute()
    {	
		//Set request flag value
		$requested = 0;
		$this->group_member = 0;
		$chkrequest = Item::Status()->where('requested_by', '=', $this->id)->get();			
		if(count($chkrequest) > 0) {			
			$requested = 1;
		}
		else {
			$aMemeberThesis = GroupMember::select('group_members.*')->join('items','items.id','=','group_members.item_id')->where('group_members.user_id','=',$this->id)->get();
			if(count($aMemeberThesis) > 0) {
				$requested = 1;
				$this->group_member = 1;
			}
		}
		$this->thesis_alloted = $requested;
		//#--
		
		//Set assign flag value
		$assigned = 0;		
		$chkassigned = Item::Status()->where('assigned_to', '=', $this->id)->get();			
		if(count($chkassigned) > 0) {			
			$assigned = 1;
		}
		$this->thesis_assigned = $assigned;
		//#-- 
		
        return $this;
    }

    /**
     * Get the role of the user
     *
     * @return \App\Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
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
     * Get the path to the profile picture
     *
     * @return string
     */
    public function profilePicture()
    {
        if ($this->picture) {
            return "{$this->picture}";
        }
		$this->CustomAttribute();
        return '/storage/img/default-avatar.png';
    }

    /**
     * Check if the user has admin role
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role_id == 1;
    }

	/**
    * Check if the user has creator role
    *
    * @return boolean
    */
    public function isManager()
    {
        return $this->role_id == 2;
    }
    /**
     * Check if the user has creator role
     *
     * @return boolean
     */
    public function isCreator()
    {
        return $this->role_id == 3;
    }

    /**
     * Check if the user has user role
     *
     * @return boolean
     */
    public function isMember()
    {
        return $this->role_id == 4;
    }
}
