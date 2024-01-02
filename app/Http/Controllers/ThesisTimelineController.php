<?php

namespace App\Http\Controllers;

use App\Program;
use App\Item;
use App\User;
use App\Term;
use App\ThesisTimeline;
use App\TermProgressChecklist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ThesisTimeline $model, TermProgressChecklist $checklist)
    {
        $aTimelineInfo = $model->Active()->with(['term','program'])->get();        
        return view('templates.index-thesis-timeline', ['timelines' => $aTimelineInfo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Term $terms, Program $programs, ThesisTimeline $model)
    {
        if($request->type == 'tc'){
            $aPrograms = $programs->where('status','!=',2)->get();
            $aThesisInfo = $model->Active()->select('thesis_timelines.term_id')->get();        
            $aTerms = $terms->select('terms.*')->where('terms.status','!=',2)
                            ->get(); 
            return view('templates.create-thesis-timeline', ['cohorts' => $aTerms, 'programs' =>$aPrograms]);
        } 
        if($request->type == 'te') {
            $aPrograms = $programs->where('status','!=',2)->get();
            $aThesisInfo = $model->Active()
                                    ->select('thesis_timelines.*')
                                    ->where('timeline_id','=',$request->timeline_id)
                                    ->get();        
            $aTerms = $terms->select('terms.*')->where('terms.status','!=',2)
                            ->whereNotIn('terms.id',$aThesisInfo)->get();              
            return view('templates.edit-thesis-timeline', ['timelineinfo' =>$aThesisInfo, 'cohorts' => $aTerms, 'programs' =>$aPrograms]);
        }   
            
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ThesisTimeline $timeline, Program $programs, Term $terms)
    {          
        if($request->type == 'tc'){
            if(count($request->sle_program_selected) > 0) {
                for($prog_loop = 0; $prog_loop < count($request->sle_program_selected); $prog_loop++) {
                    if($request->sle_program_selected[$prog_loop] > 0) {
                        $aProgramDet = $programs::find($request->sle_program_selected[$prog_loop]);
                        if(count($request->sle_cohort_selected) > 0) {
                            for($term_loop = 0; $term_loop < count($request->sle_cohort_selected); $term_loop++) {
                                if($request->sle_cohort_selected[$term_loop] > 0) {
                                    $aTermsDet = $terms::find($request->sle_cohort_selected[$term_loop]);
                                    $aTimelineInfo = $timeline->where([['term_id', '=', $request->sle_cohort_selected[$term_loop]],['program_id', '=', $request->sle_program_selected[$prog_loop]], ['status','=', 1]])->get();
                                    if(count($aTimelineInfo) == 0) {                 
                                        $vTimelineID = $timeline->insertGetId([
                                            'term_id' => trim($request->sle_cohort_selected[$term_loop]),
                                            'program_id' => trim($request->sle_program_selected[$prog_loop]),
                                            'timeline_name' => trim($request->folder_name),
                                            'timeline_description' => trim($request->folder_description),
                                            'status' => 1,
                                            'created_by' => Auth::user()->id ? (int) Auth::user()->id : 0,
                                            'term1_completion' => $request->term1date ? Carbon::parse($request->term1date)->format('Y-m-d') : NULL, 
                                            't1_meeting_minutes1' => $request->term1meet1 ? Carbon::parse($request->term1meet1)->format('Y-m-d') : NULL, 
                                            't1_meeting_minutes2' => $request->term1meet2 ? Carbon::parse($request->term1meet2)->format('Y-m-d') : NULL, 
                                            't1_meeting_minutes3' => $request->term1meet3 ? Carbon::parse($request->term1meet3)->format('Y-m-d') : NULL, 
                                            'term1chapter1' => $request->term1chapter1 ? Carbon::parse($request->term1chapter1)->format('Y-m-d') : NULL, 
                                            't1_meeting_minutes4' => $request->term1meet4 ? Carbon::parse($request->term1meet4)->format('Y-m-d') : NULL, 
                                            't1_meeting_minutes5' => $request->term1meet5 ? Carbon::parse($request->term1meet5)->format('Y-m-d') : NULL, 
                                            'term1chapter2' => $request->term1chapter2 ? Carbon::parse($request->term1chapter2)->format('Y-m-d') : NULL, 
                                            'term1presentation' => $request->term1presentation ? Carbon::parse($request->term1presentation)->format('Y-m-d') : NULL,
                                            'term2_completion' => $request->term2date ? Carbon::parse($request->term2date)->format('Y-m-d') : NULL, 
                                            't2_meeting_minutes1' => $request->term2meet1 ? Carbon::parse($request->term2meet1)->format('Y-m-d') : NULL, 
                                            't2_meeting_minutes2' => $request->term2meet2 ? Carbon::parse($request->term2meet2)->format('Y-m-d') : NULL, 
                                            't2_meeting_minutes3' => $request->term2meet3 ? Carbon::parse($request->term2meet3)->format('Y-m-d') : NULL,
                                            'term2chapter1' => $request->term2chapter1 ? Carbon::parse($request->term2chapter1)->format('Y-m-d') : NULL, 
                                            't2_meeting_minutes4' => $request->term2meet4 ? Carbon::parse($request->term2meet4)->format('Y-m-d') : NULL, 
                                            't2_meeting_minutes5' => $request->term2meet5 ? Carbon::parse($request->term2meet5)->format('Y-m-d') : NULL,
                                            'term2chapter2' => $request->term2chapter2 ? Carbon::parse($request->term2chapter2)->format('Y-m-d') : NULL, 
                                            'term2presentation' => $request->term2presentation ? Carbon::parse($request->term2presentation)->format('Y-m-d') : NULL,
                                            'created_at' => now()
                                            ]);
                                    }                                    
                                }
                                $aTermsDet = "";
                            }
                        }
                    }
                }
            }
            return redirect()->route('timeline.view-thesis-timeline')->withStatus(__('Thesis timelines created successfully.'));      
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ThesisTimeline  $thesisTimeline
     * @return \Illuminate\Http\Response
     */
    public function show(ThesisTimeline $thesisTimeline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ThesisTimeline  $thesisTimeline
     * @return \Illuminate\Http\Response
     */
    public function edit(ThesisTimeline $thesisTimeline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ThesisTimeline  $thesisTimeline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThesisTimeline $thesisTimeline)
    {
        //Update timeline details        
        if((int)$request->timeline_id > 0 && $request->type == 'tu' ){            
            $thesisTimeline->where(['timeline_id'=>$request->timeline_id])
                ->update(['timeline_name' => trim($request->folder_name),
                'timeline_description' => trim($request->folder_description),                    
                'term1_completion' => $request->term1date ? Carbon::parse($request->term1date)->format('Y-m-d') : NULL, 
                't1_meeting_minutes1' => $request->term1meet1 ? Carbon::parse($request->term1meet1)->format('Y-m-d') : NULL, 
                't1_meeting_minutes2' => $request->term1meet2 ? Carbon::parse($request->term1meet2)->format('Y-m-d') : NULL, 
                't1_meeting_minutes3' => $request->term1meet3 ? Carbon::parse($request->term1meet3)->format('Y-m-d') : NULL,
                'term1chapter1' => $request->term1chapter1 ? Carbon::parse($request->term1chapter1)->format('Y-m-d') : NULL,  
                't1_meeting_minutes4' => $request->term1meet4 ? Carbon::parse($request->term1meet4)->format('Y-m-d') : NULL, 
                't1_meeting_minutes5' => $request->term1meet5 ? Carbon::parse($request->term1meet5)->format('Y-m-d') : NULL, 
                'term1chapter2' => $request->term1chapter2 ? Carbon::parse($request->term1chapter2)->format('Y-m-d') : NULL, 
                'term1presentation' => $request->term1presentation ? Carbon::parse($request->term1presentation)->format('Y-m-d') : NULL,
                'term2_completion' => $request->term2date ? Carbon::parse($request->term2date)->format('Y-m-d') : NULL, 
                't2_meeting_minutes1' => $request->term2meet1 ? Carbon::parse($request->term2meet1)->format('Y-m-d') : NULL, 
                't2_meeting_minutes2' => $request->term2meet2 ? Carbon::parse($request->term2meet2)->format('Y-m-d') : NULL, 
                't2_meeting_minutes3' => $request->term2meet3 ? Carbon::parse($request->term2meet3)->format('Y-m-d') : NULL,
                'term2chapter1' => $request->term2chapter1 ? Carbon::parse($request->term2chapter1)->format('Y-m-d') : NULL,  
                't2_meeting_minutes4' => $request->term2meet4 ? Carbon::parse($request->term2meet4)->format('Y-m-d') : NULL, 
                't2_meeting_minutes5' => $request->term2meet5 ? Carbon::parse($request->term2meet5)->format('Y-m-d') : NULL,
                'term2chapter2' => $request->term2chapter2 ? Carbon::parse($request->term2chapter2)->format('Y-m-d') : NULL, 
                'term2presentation' => $request->term2presentation ? Carbon::parse($request->term2presentation)->format('Y-m-d') : NULL,
                'updated_at' => now()]);
        }
        return redirect()->route('timeline.view-thesis-timeline')->withStatus(__('Thesis timelines updated successfully.')); 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ThesisTimeline  $thesisTimeline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ThesisTimeline $thesisTimeline)
    {
       //Update timeline details        
        if((int)$request->timeline_id > 0 && $request->_method == 'delete' ){ 
            $thesisTimeline->where(['timeline_id'=>$request->timeline_id])
                ->update(['status' => 2,
                     'updated_at' => now()]);
        }
        return redirect()->route('timeline.view-thesis-timeline')->withStatus(__('Thesis timelines deleted successfully.')); 
    }
}
