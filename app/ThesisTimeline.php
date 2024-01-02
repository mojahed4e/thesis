<?php

namespace App;
use App\Item;
use App\Term;
use App\Program;
use Illuminate\Database\Eloquent\Model;

class ThesisTimeline extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['program_id', 'term_id','timeline_name', 'timeline_description', 'term1_completion', 't1_meeting_minutes1', 't1_meeting_minutes2', 't1_meeting_minutes3','term1chapter1', 't1_meeting_minutes4', 't1_meeting_minutes5','term1chapter2','term1presentation','term2_completion', 't2_meeting_minutes1', 't2_meeting_minutes2', 't2_meeting_minutes3','term2chapter1', 't2_meeting_minutes4', 't2_meeting_minutes5','term2chapter2','term2presentation'];

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
     * Get the users for the term
     *
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
