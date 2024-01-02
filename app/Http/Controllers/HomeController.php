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
namespace App\Http\Controllers;

use App\Item;
use App\User;
use App\GroupMember;
use App\ItemAssignment;
use App\ThesisAttachments;
use App\ThesisProgressTrackings;
use App\ThesisRequestDetails;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Item $itemModel,ThesisRequestDetails $reqDetails)
    {
    	$aUserData = [];
		if(Auth::user()->role_id < 3) {
				
			$atotal = $itemModel->where('status','=',1)->selectRaw('COUNT(items.id) AS total_count')->get();
			
			$aAllocated = $itemModel->selectRaw('COUNT(items.id) AS allocated')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'thesis_request_details.supervisor_acceptence_status' => 1])->get();
			$aCompleted = $itemModel->selectRaw('COUNT(items.id) AS completed')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where([['items.status','>',0],'thesis_request_details.progress_completion' => 3])->get();
			if(count($atotal) > 0){
				$aDataSet[0]['total'] = $atotal[0]->total_count;
			}
			else
				$aDataSet[0]['total'] = 0;
			if(count($aAllocated) > 0){
				$aDataSet[0]['allocated'] = $aAllocated[0]->allocated;
			}
			else
				$aDataSet[0]['allocated'] = 0;
			if(count($aCompleted) > 0){
				$aDataSet[0]['completed'] = $aCompleted[0]->completed;
			}
			else
				$aDataSet[0]['completed'] = 0;
			
			$aTermI = $itemModel->selectRaw('COUNT(items.id) AS termone')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'thesis_request_details.supervisor_acceptence_status' => 0])->get();
			
			$aTermII = $itemModel->selectRaw('COUNT(items.id) AS termtwo')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'thesis_request_details.progress_completion' => 0,'thesis_request_details.supervisor_acceptence_status' => 1])->get();
			$aTermIII = $itemModel->selectRaw('COUNT(items.id) AS termthree')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'thesis_request_details.progress_completion' => 2])->get();
			if(count($aTermI) > 0){
				$aDataSet[1]['termone'] = $aTermI[0]->termone;
			}
			else
				$aDataSet[1]['termone'] = 0;
			if(count($aTermII) > 0){
				$aDataSet[1]['termtwo'] = $aTermII[0]->termtwo;
			}
			else
				$aDataSet[1]['termtwo'] = 0;
			if(count($aTermIII) > 0){
				$aDataSet[1]['termthree'] = $aTermIII[0]->termthree;
			}
			else
				$aDataSet[1]['termthree'] = 0;

			
			$atotal = $itemModel->Status()->where('status','=',1)->selectRaw('COUNT(items.id) AS total_count')->get();
				
		
			$atotal = $itemModel->where('status','=',1)->selectRaw('COUNT(items.id) AS total_count')->get();
			
			$aAllocated = $itemModel->selectRaw('COUNT(items.id) AS allocated')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'items.approval_status' => 1])->get();
			$aCompleted = $itemModel->selectRaw('COUNT(items.id) AS completed')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where([['items.status','>',0],'thesis_request_details.progress_completion' => 3])->get();
			if(count($atotal) > 0){
				$aDataSet[0]['total'] = $atotal[0]->total_count;
			}
			else
				$aDataSet[0]['total'] = 0;
			if(count($aAllocated) > 0){
				$aDataSet[0]['allocated'] = $aAllocated[0]->allocated;
			}
			else
				$aDataSet[0]['allocated'] = 0;
			if(count($aCompleted) > 0){
				$aDataSet[0]['completed'] = $aCompleted[0]->completed;
			}
			else
				$aDataSet[0]['completed'] = 0;
			
			$aTermI = $itemModel->selectRaw('COUNT(items.id) AS termone')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'thesis_request_details.supervisor_acceptence_status' => 0])->get();
			
			$aTermII = $itemModel->selectRaw('COUNT(items.id) AS termtwo')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where(['items.status' => 1,'thesis_request_details.progress_completion' => 0,'thesis_request_details.supervisor_acceptence_status' => 1])->get();
			$aTermIII = $itemModel->selectRaw('COUNT(items.id) AS termthree')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->where([['items.status','>',0],'thesis_request_details.progress_completion' => 1])->get();
			if(count($aTermI) > 0){
				$aDataSet[1]['termone'] = $aTermI[0]->termone;
			}
			else
				$aDataSet[1]['termone'] = 0;
			if(count($aTermII) > 0){
				$aDataSet[1]['termtwo'] = $aTermII[0]->termtwo;
			}
			else
				$aDataSet[1]['termtwo'] = 0;
			if(count($aTermIII) > 0){
				$aDataSet[1]['termthree'] = $aTermIII[0]->termthree;
			}
			else
				$aDataSet[1]['termthree'] = 0;
			
			$aAssigned = $itemModel->select('users.id','users.name',$itemModel::raw('COUNT(items.id) AS assigned'))
							->join('users','users.id','=','items.assigned_to')
							->groupBy('users.id')
							->where(['items.status' => 1])
							->get();
			$aSubmitted = $itemModel->select('users.id','users.name',$itemModel::raw('COUNT(items.id) AS submited'))
							->join('users','users.id','=','items.created_by')
							->groupBy('users.id')								
							->where('items.status','!=', 2)
							->where('users.role_id','!=', 4)
							->get();
			$aCapsCompleted = array();
			$aCapsCompleted = $itemModel->select('users.id','users.name',$itemModel::raw('COUNT(items.id) AS completed'))
							->join('users','users.id','=','items.assigned_to')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')
							->groupBy('users.id')
							->where([['items.status','>', 0],'thesis_request_details.progress_completion' => 3])->get();
			if(count($aAssigned) > 0) {
				$aUserData = [];
				$loop = 0;
				foreach($aAssigned as $assigned) {
					$match = 0;					
					$aUserData['name'][$loop] = $assigned['name'];
					$aUserData['assigned'][$loop] = $assigned['assigned'];
					if(count($aSubmitted) > 0) {
						foreach($aSubmitted as $submitted) {
							if($assigned['id'] == $submitted['id']) {
								$aUserData['submited'][$loop] = $submitted['submited'];
								$match = 1;
							}
						}
						if($match == 0) {
							
							$aUserData['submited'][$loop] = 0;
						}
					}
					else {
						$aUserData['submited'][$loop] = 0;
					}
					if(count($aCapsCompleted) > 0) {
						foreach($aCapsCompleted as $acompleted) {
							if($acompleted['id'] == $assigned['id']) {
								$aUserData['completed'][$loop] = $acompleted['completed'];
								$match = 1;
							}
						}
						if($match == 0) {
							
							$aUserData['completed'][$loop] = 0;
						}
					}
					else {
						$aUserData['completed'][$loop] = 0;
					}
					$loop++;
				}
			}	
			return view('pages.dashboard',['graphdata' => $aDataSet,'supervisor' => $aUserData]);
		}
		else {
			if(Auth::user()->thesis_alloted != 0)
				return redirect()->route('item.index');
			else
				return redirect()->route('mythesis.detail');
		}
    }
}
