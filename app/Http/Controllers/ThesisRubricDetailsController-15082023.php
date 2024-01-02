<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use App\MeetingLogs;
use App\GroupMember;
use App\ItemAssignment;
use App\ThesisAttachments;
use App\ThesisProgressTrackings;
use App\TermProgressChecklist;
use App\Category;
use App\ThesisRequestDetails;
use App\ThesisRubricDetails;
use App\ThesisRubricTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;

class ThesisRubricDetailsController extends Controller
{
	public function __construct()
    {
       // $this->authorizeResource(MeetingLogs::class);
    }    

    public function index()
    {
       // $this->authorizeResource(MeetingLogs::class);
    }  
    /**
     * Show the form for prepare meeting minutes
     *
     * @return \Illuminate\Http\Response
     */
    public function prepareThesisRubrics(Request $request, MeetingLogs $meetinglog, Item $item,ThesisRequestDetails $reqdetails,TermProgressChecklist $aProgressChecklist,ThesisRubricDetails $rubric,ThesisRubricTemplate $template)
    { 
 		$aThesisRubricInfo = array();
 		$aItems = $item->Status()
						->join('users','users.id','=','items.requested_by')						
						->where(['items.id' => $request->item_id])
						->select('users.name AS requested_by','items.name AS title',"items.*")
						->get();	
		$aRubricTempleInfo = $template->Status()												
						->select('thesis_rubric_template.*')
						->where(['thesis_rubric_template.template_type' => $request->rubrictype])
						->orderBy('display_sequence','asc')
						->get();		
		$vRoutePath = 'items.panel.prepare_rubric';
      return view($vRoutePath,['iteminfo' => $aItems,'templateinfo' => $aRubricTempleInfo, 'type' => $request->rubrictype, 'term' => $request->rubricterm ,'item_id' => $request->item_id,'action_type' => 1,'storePath' => route('item.store-rubric', $request->item_id)]);
    }

     /**
     * Show the form for prepare meeting minutes
     *
     * @return \Illuminate\Http\Response
     */
    public function viewThesisRubrics(Request $request, MeetingLogs $meetinglog, Item $item,ThesisRequestDetails $reqdetails,ThesisRubricDetails $rubric,ThesisRubricTemplate $template,TermProgressChecklist $progresschecklist)
    {
 		$aThesisRubricInfo = array();
 		$aItems = $item->Status()
						->join('users','users.id','=','items.requested_by')						
						->where(['items.id' => $request->item_id])
						->select('users.name AS requested_by','items.name AS title',"items.*")
						->get();	
		$aRubricTempleInfo = $template->Status()												
						->select('thesis_rubric_template.*')
						->get();	
		if(isset($request->rubriccreatedby)){
			$aRubricDetailsInfo = $rubric->where(['item_id' => $request->item_id,'rubric_term' => $request->rubricterm,'rubric_type' => $request->rubrictype, 'created_by' => $request->rubriccreatedby])->get();
		}
		else {
			$aRubricDetailsInfo = $rubric->where(['item_id' => $request->item_id,'rubric_term' => $request->rubricterm,'rubric_type' => $request->rubrictype, 'created_by' => Auth::user()->id ])->get();
		}
		

		$aViewRubricData = array();
		foreach($aRubricTempleInfo as $template){
			foreach($aRubricDetailsInfo as $rubric){				
				if($template->template_id == $rubric->rubric_template_id){
					$aViewRubricData['templateinfo'][] = $template;
					$aViewRubricData['valueinfo'][] = $rubric;
				}
				if($rubric->rubric_template_id == 0){
					$aViewRubricData['comments'] = $rubric->rubric_comments;
					$aViewRubricData['overallpercentage'] = $rubric->overall_percentage; 
					$aViewRubricData['presentation_date'] = $rubric->presentation_date;
					$aViewRubricData['additional_comments_students'] = $rubric->additional_comments_students;
				}
			}
		}
		$vRoutePath = 'items.panel.view_rubric';
      return view($vRoutePath,['iteminfo' => $aItems,'templateinfo' => $aViewRubricData, 'type' => $request->rubrictype,'rubriccreatedby' => $request->rubriccreatedby , 'term' => $request->rubricterm ,'item_id' => $request->item_id,'action_type' => 1,'storePath' => route('item.store-rubric', $request->item_id)]);
    	
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeThesisRubrics(Request $request,  User $userModel, ThesisRequestDetails $reqdetails, Item $itemModel,ThesisProgressTrackings $tracking,ThesisRubricTemplate $template,ThesisRubricDetails $rubric,TermProgressChecklist $checklist)
    {
    	$aThesisInfo = $itemModel::find($request->thesis_id);
    	$aRubricTempleInfo = $template->Status()												
						->select('thesis_rubric_template.*')
						->where(['thesis_rubric_template.template_type' => $request->rubrictype])
						->orderBy('display_sequence','asc')
						->get();	

		foreach($aRubricTempleInfo as $template){
			$vTempCriteria = str_replace(" ","_",strtolower($template->criteria));
			$txtScoreField = 'txt_'.$vTempCriteria;
			if(isset($_REQUEST[$vTempCriteria]) && !empty($_REQUEST[$vTempCriteria])){
				$vRubricID = $rubric->insertGetId([
									'rubric_template_id' => $template->template_id,
									'created_by' => Auth::user()->id,
									'item_id' => $request->thesis_id,
									'rubric_term' => $request->rubricterm,
									'criteria_score_percent' => $_REQUEST[$txtScoreField],
									'does_not_meet_expectations' => $_REQUEST[$vTempCriteria][0],
									'meets_expectations' => $_REQUEST[$vTempCriteria][1],
									'exceeds_expectations' => $_REQUEST[$vTempCriteria][2],									
									'created_date' => now(),
									'presentation_date' => $request->presentationdate ? Carbon::parse(
									$request->presentationdate)->format('Y-m-d') : NULL,
									'rubric_type' => $request->rubrictype
								]);
			}
		}
		$vRubricID = $rubric->insertGetId([
								'rubric_template_id' => 0,
								'created_by' => Auth::user()->id,
								'item_id' => $request->thesis_id,
								'rubric_term' => $request->rubricterm,
								'does_not_meet_expectations' => 0,
								'meets_expectations' => 0,
								'exceeds_expectations' => 0,
								'created_date' => now(),
								'presentation_date' => $request->presentationdate ? Carbon::parse(
								$request->presentationdate)->format('Y-m-d') : NULL,
								'rubric_type' => $request->rubrictype,
								'rubric_comments' => $request->confidential_comments,
								'additional_comments_students' => $request->students_comments,
								'overall_percentage' => $request->overall_percentage
							]);
	  	if($vRubricID > 0) {
     		//Insert message details
     		if($request->rubrictype == 2){
     			$vDescription = 'Thesis term - '.$request->rubricterm.' presentation rubric prepared and submitted successfully by '.Auth::user()->name;
     			$vDesStudent = 'Thesis term - '.$request->rubricterm.' presentation feedback and comments posted by '.Auth::user()->name;
     			$vDocumentType = 'presentationrubric';
     			$vMessage = " presentation ";
     		}
     		else{
     			$vDescription = 'Thesis term - '.$request->rubricterm.' report rubric prepared and submitted successfully by '.Auth::user()->name;
     			$vDesStudent = 'Thesis term - '.$request->rubricterm.' report feedback and comments posted by '.Auth::user()->name;
     			$vDocumentType = 'proposalrubric';
     			$vMessage = " report ";
     		}

     		$vActionType = 0;
     		if($request->rubricterm == 1){
     			if($request->rubrictype == 1)
     				$vActionType = 21;
     			else
     				$vActionType = 22;
     		}
     		else{
     			if($request->rubrictype == 1)
     				$vActionType = 23;
     			else
     				$vActionType = 24;
     		}
			$vTrackID = $tracking->insertGetId([
						'user_id' => Auth::user()->id,
						'item_id' => $request->thesis_id,
						'request_detail_id' => $aThesisInfo->request_detail_id,
						'action_type' => $vActionType,
						'description' => "<p>".$vDescription."</p>",
						'created_date' =>now()
						]);			
			$request->merge(['msg_item_id' => $request->thesis_id,'msg_track_id' => $vTrackID]);
			app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
			
			$aTermChecklist = $checklist->where(['item_id' => $request->thesis_id, 'checklist_type' => $request->rubricterm])->orderBy('terms_progress_checklist.sequence','desc')->get();

			$vTrackID = $tracking->insertGetId([
						'user_id' => Auth::user()->id,
						'item_id' => $request->thesis_id,
						'request_detail_id' => $aThesisInfo->request_detail_id,
						'action_type' => 0,						
						'description' => "<p>".$vDesStudent.$request->students_comments."</p>",
						'created_date' =>now()
						]);

			$vTermProgressID = $checklist->insertGetId([
										'item_id' => $request->thesis_id, 
										'user_id' => Auth::user()->id,
										'student_upload_status' => 2,
										'document_type' => $vDocumentType,
										'created_date' => now(),
										'checklist_type' => $request->rubricterm,
										'sequence' => ($aTermChecklist[0]->sequence+1)
										]);

			//Send confirmation mail to Supervisor/Panel Member
			$mail = new PHPMailer(); 
			$mail->isSMTP();			
			$mail->Port = config('mail.port'); // Your Outgoing Port
			$mail->Host = config('mail.host');
			$mail->Username = config("mail.username"); // SMTP username
			$mail->Password = config("mail.password"); // SMTP password
			$mail->SMTPDebug = 0;
			$mail->Priority = 3;		
			$mail->Debugoutput = 'html';
			$mail->SMTPSecure = config("mail.encryption"); 
			$mail->SMTPOptions = config("mail.stream");
			$mail->SMTPAuth = false;		
			$mail->IsHTML(true);
			
			$mail->From = config('mail.from.address');
			$mail->FromName = config('mail.from.name');
			
			if(config('mail.email_flag') > 0) {
				$mail->AddAddress(Auth::user()->email, Auth::user()->name);
				$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			else {
				$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			
			$mail->Subject = "ADSM Thesis Manager Notification - ".$vDescription;

			$mail->Body = '<!DOCTYPE html>
							<html>
							<head>
								<title>ADSM - Thesis Manager</title>
							</head>
							<body>
								<p>Dear '.Auth::user()->name.',</p>
								<p>Thesis Title:'.$aThesisInfo->name.' </p>
								<p>Kindly note, your <strong>thesis term - '.$request->rubricterm.$vMessage.' rubric successfully submitted </strong>in the system.</p>
								<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
								<p>Thank you</p>
								<p>&nbsp;</p>
								<p>'.config('mail.mail_bottom').'</p>
							</body>
							</html>';
			$mail_result = $mail->Send();				
			$mail = NULL;

			$aRequestDetailsInfo = $reqdetails::find($aThesisInfo->request_detail_id);
			$aManagerInfo = $userModel::find($aRequestDetailsInfo->manager);
			$aPanelMembers = $userModel->Available()
										->join("panel_members",'user_id','=','users.id')
										->where('panel_members.item_id','=',$request->thesis_id);

			
			//Send confirmation mail to Manager
			$mail = new PHPMailer(); 
			$mail->isSMTP();			
			$mail->Port = config('mail.port'); // Your Outgoing Port
			$mail->Host = config('mail.host');
			$mail->Username = config("mail.username"); // SMTP username
			$mail->Password = config("mail.password"); // SMTP password
			$mail->SMTPDebug = 0;
			$mail->Priority = 3;		
			$mail->Debugoutput = 'html';
			$mail->SMTPSecure = config("mail.encryption"); 
			$mail->SMTPOptions = config("mail.stream");
			$mail->SMTPAuth = false;		
			$mail->IsHTML(true);
			
			$mail->From = config('mail.from.address');
			$mail->FromName = config('mail.from.name');

			if(config('mail.email_flag') > 0) {
				$mail->AddAddress($aManagerInfo->email, $aManagerInfo->name);
				if(count($aPanelMembers) > 0){
					foreach($aPanelMembers as $member){
						if($member->id != Auth::user()->id && $request->rubrictype == 2){
							$mail->addBcc($member->email, $member->name);			
						}
					}
				}
				$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			else {
				$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			
			$mail->Subject = "ADSM Thesis Manager Notification - ".$vDescription;
			$mail->Body = '<!DOCTYPE html>
							<html>
							<head>
								<title>ADSM - Thesis Manager</title>
							</head>
							<body>
								<p>Dear '.$aManagerInfo->name.',</p>	
								<p>Thesis Title:'.$aThesisInfo->name.' </p>
								<p>Kindly note, <strong>thesis term - '.$request->rubricterm.$vMessage.' rubric prepared and submitted successfully by '.Auth::user()->name.' </strong>in the system.</p>
								<p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
								<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
								<p>Thank you</p>
								<p>&nbsp;</p>
								<p>'.config('mail.mail_bottom').'</p>
							</body>
							</html>';
			$mail_result = $mail->Send();				
			$mail = NULL;	

			$aStudentInfo = $userModel::find($aThesisInfo->requested_by);

			//Send additional comments information to the Student
			$mail = new PHPMailer(); 
			$mail->isSMTP();			
			$mail->Port = config('mail.port'); // Your Outgoing Port
			$mail->Host = config('mail.host');
			$mail->Username = config("mail.username"); // SMTP username
			$mail->Password = config("mail.password"); // SMTP password
			$mail->SMTPDebug = 0;
			$mail->Priority = 3;		
			$mail->Debugoutput = 'html';
			$mail->SMTPSecure = config("mail.encryption"); 
			$mail->SMTPOptions = config("mail.stream");
			$mail->SMTPAuth = false;		
			$mail->IsHTML(true);
			
			$mail->From = config('mail.from.address');
			$mail->FromName = config('mail.from.name');

			if(config('mail.email_flag') > 0) {
				$mail->AddAddress($aStudentInfo->email, $aStudentInfo->name);				
				$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			else {
				$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			
			$mail->Subject = "ADSM Thesis Manager Notification - ".$vDesStudent;
			$mail->Body = '<!DOCTYPE html>
							<html>
							<head>
								<title>ADSM - Thesis Manager</title>
							</head>
							<body>
								<p>Dear '.$aStudentInfo->name.',</p>	
								<p>Thesis Title:'.$aThesisInfo->name.' </p>
								<p>Kindly note, <strong>thesis term - '.$request->rubricterm.$vMessage.' rubric prepared successfully by '.Auth::user()->name.' </strong>in the system.</p>
								<p>Also posted the below<strong> Feedback & Comments </strong></p>
								<p><strong>'.$request->students_comments.'</strong></p>
								<p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
								<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
								<p>Thank you</p>
								<p>&nbsp;</p>
								<p>'.config('mail.mail_bottom').'</p>
							</body>
							</html>';
			$mail_result = $mail->Send();				
			$mail = NULL;

			if($request->rubrictype == 2){
				$vSuccessMsg = 'Presentation rubric successfully created';
				$vRoutePath = 'mythesis.examine';
				return redirect()->route($vRoutePath)->withStatus(__($vSuccessMsg));
			}
			else{
				$vSuccessMsg = 'Report rubric successfully created';				
				return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__($vSuccessMsg));
			}
			
     	}
		else {
			if($request->rubrictype == 2){
				$vRoutePath = 'mythesis.examine';
				$vFailureMsg = 'Presentation rubric creation failed. Please try again later...';
				return redirect()->route($vRoutePath)->withStatus(__($vFailureMsg));
			}
			else {
				$vFailureMsg = 'Report rubric creation failed. Please try again later...';				
				return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__($vFailureMsg));
			}
			
		}
        	
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MeetingLogs  $meetingLogs
     * @return \Illuminate\Http\Response
     */
    public function show(MeetingLogs $meetingLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MeetingLogs  $meetingLogs
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MeetingLogs $meetinglog, Item $item,ThesisRequestDetails $reqdetails)
    {
				
        //$this->authorize('manageMeetingLogs', User::class);
		
		$aItems = $item->Status()
						->join('users','users.id','=','items.requested_by')						
						->where(['items.id' => $request->item_id])
						->select('users.name AS requested_by','items.name AS title',"items.id","items.request_detail_id")
						->get();
		$aGroupInfo = $item->Status()->join('group_members','group_members.item_id','=','items.id')
						->join('users','users.id','=','group_members.user_id')	
						->where(['items.id' => $request->item_id])
						->select('users.name AS group_members')
						->get();
		$aRequestDetails = $reqdetails->where(['item_id' => $request->item_id])->get();
		$vTermProgress = 0;
		if(count($aRequestDetails)) {
			if($aRequestDetails[0]->progress_completion == 0) {
				$vTermProgress = 1;
				$vLogTerm = "Term - I";
			}
			else if($aRequestDetails[0]->progress_completion == 1) {
				$vTermProgress = 2;
				$vLogTerm = "Term - II";
			}
			else {
				$vTermProgress = 3;	
				$vLogTerm = "Term - III";
			}			
		}
		
		$aMeetingInfo = $meetinglog
							->where(['meeting_log_type' => $vTermProgress,'meeting_logs.id' => $request->segment(2)])
							->select('meeting_logs.*',\DB::raw("(select max(logs.meeting_log_seq) FROM  meeting_logs AS logs WHERE logs.item_id = ".$request->item_id.") AS meeting_log_seq"))
							->get();		
        return view('meetinglog.edit',['iteminfo' => $aItems, 'groupinfo' => $aGroupInfo, 'meetinglogifo' => $aMeetingInfo ,'logterm' => $vTermProgress,'action_type' => 2]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MeetingLogs  $meetingLogs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeetingLogs $meetingLogs)
    {
        //
		$vUpdateResp = $meetingLogs->where(['meeting_logs.id'=>$request->log_id,'meeting_logs.meeting_log_seq'=>$request->meeting_log_seq,'meeting_logs.item_id'=>$request->item_id])
									->update(['meeting_logs.milestone_achived_last_meeting' => $request->lastmilestoneachieved, 
											'meeting_logs.discussed_this_meeting' => $request->discussthismeeting, 
											'meeting_logs.next_meeting_agenda' => $request->nextmeetingagenda, 
											'meeting_logs.next_meeting_date' =>  Carbon::parse($request->nextmeetdate)->format('Y-m-d'),
											'meeting_logs.meeting_date' =>  Carbon::parse($request->meeetingdate)->format('Y-m-d')
										]);
		if($vUpdateResp) 
			return redirect()->route('meetinglogs.index','id='.$request->item_id.'&action=ac')->withStatus(__('Meeting minutes updted successfully.'));
		else
			
			return redirect()->route('meetinglogs.index','id='.$request->item_id.'&action=ac')->withStatus(__('Meeting minutes update failed. Please try again later'));		
    }
	

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MeetingLogs  $meetingLogs
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeetingLogs $meetingLogs)
    {
        //
    }
}
