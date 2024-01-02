<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use App\MeetingLogs;
use App\GroupMember;
use App\ItemAssignment;
use App\ThesisAttachments;
use App\ThesisProgressTrackings;
use App\ThesisRequestDetails;
use App\TermProgressChecklist;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;

class MeetingLogsController extends Controller
{
	public function __construct()
    {
       // $this->authorizeResource(MeetingLogs::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MeetingLogs $model, Item $item)
    {
		
		
        $this->authorize('manageMeetingLogs', User::class);
		
		$aMeetingLogs = $model
						->join('items','items.id','=','meeting_logs.item_id')						
						->where(['meeting_logs.item_id' => $request->id])	
						->select('meeting_logs.*','items.name AS title',"items.id as item_id","items.request_detail_id")
						->orderBy('meeting_logs.id','asc')
						->get();		
        return view('meetinglog.index', ['meetinglogs' => $aMeetingLogs]);
    }

    /**
     * Show the form for prepare meeting minutes
     *
     * @return \Illuminate\Http\Response
     */
    public function prepareMeetingMinutes(Request $request, MeetingLogs $meetinglog, Item $item,ThesisRequestDetails $reqdetails,TermProgressChecklist $progresschecklist)
    { 
 		$aPreviousMeetingInfo = array();
		$aItems = $item->Status()
						->join('users','users.id','=','items.requested_by')						
						->where(['items.id' => $request->id])
						->select('users.name AS requested_by','items.name AS title',"items.*")
						->get();		
		$aRequestDetails = $reqdetails->where(['item_id' => $request->id])->get();
		$vTermProgress = 0;
		if(count($aRequestDetails)) {
			if($aRequestDetails[0]->progress_completion == 0) {
				$vTermProgress = 1;
			}
			else if($aRequestDetails[0]->progress_completion == 1) {
				$vTermProgress = 2;				
			}					
		}		
		if($request->meeting_log_seq < 4){
			$vFetchSeq = $request->meeting_log_seq;
		}
		else{
			$vFetchSeq = $request->meeting_log_seq+1;
		}

		$aProgressChecklist = $progresschecklist->where(['terms_progress_checklist.item_id' => $request->id,'terms_progress_checklist.checklist_type' => $vTermProgress,'terms_progress_checklist.sequence' => $vFetchSeq ])->get();

		$aMeetingInfo = $meetinglog->where(['meeting_log_type' => $vTermProgress, 'meeting_log_seq' => $request->meeting_log_seq,'item_id' => $request->id])->get();

		if(($vTermProgress == 1 && $request->meeting_log_seq > 1) || $vTermProgress == 2){
			if($vTermProgress == 1){
				$aPreviousMeetingInfo = $meetinglog->where(['meeting_log_type' => $vTermProgress, 'item_id' => $request->id, 'meeting_log_seq' => ($request->meeting_log_seq-1)])->get();
			}
			else {
				if($vTermProgress == 2 && $request->meeting_log_seq == 1){
					$aPreviousMeetingInfo = $meetinglog->where(['meeting_log_type' => 1, 'meeting_log_seq' => 5,'item_id' => $request->id])->get();
				}
				else {
					$aPreviousMeetingInfo = $meetinglog->where(['meeting_log_type' => $vTermProgress,'item_id' => $request->id, 'meeting_log_seq' => ($request->meeting_log_seq-1)])->get();
				}
			}
		}		

		if(Auth::user()->role_id == 4)
			$vRoutePath = 'mythesis.student.prepare_meeting_log';
		else if(Auth::user()->role_id == 3 || (Auth::user()->role_id == 2 && $aItems[0]->assigned_to == Auth::user()->id && $request->action == 'ac'))
			$vRoutePath = 'mythesis.supervisor.prepare_meeting_log';
		else if(Auth::user()->role_id == 2)
			$vRoutePath = 'mythesis.manager.prepare_meeting_log';
		else 
			$vRoutePath = 'mythesis.panel.prepare_meeting_log';

        return view($vRoutePath,['iteminfo' => $aItems, 'meetinglogifo' => $aMeetingInfo, 'previousmeetinglogifo' => $aPreviousMeetingInfo ,'logterm' => $vTermProgress,'logseq' => $request->meeting_log_seq,'progresschecklist' => $aProgressChecklist,'action_type' => 1,'storePath' => route('meetinglogs.store', $request->id)]);
    }

     /**
     * Show the form for prepare meeting minutes
     *
     * @return \Illuminate\Http\Response
     */
    public function viewMeetingMinutes(Request $request, MeetingLogs $meetinglog, Item $item,ThesisRequestDetails $reqdetails,TermProgressChecklist $progresschecklist)
    {
 		$aPreviousMeetingInfo = array();
		$aItems = $item->Status()
						->join('users','users.id','=','items.requested_by')						
						->where(['items.id' => $request->id])
						->select('users.name AS requested_by','items.name AS title',"items.*")
						->get();		
		$aRequestDetails = $reqdetails->where(['item_id' => $request->id])->get();
		$vTermProgress = 0;
		if($request->viewflag == 0){
			if(count($aRequestDetails)) {
				if($aRequestDetails[0]->progress_completion == 0) {
					$vTermProgress = 1;
				}
				else if($aRequestDetails[0]->progress_completion == 1) {
					$vTermProgress = 2;				
				}					
			}
		}
		else {
			$vTermProgress = $request->term_seq;
		}

		$aProgressChecklist = $progresschecklist->where(['terms_progress_checklist.item_id' => $request->id,'terms_progress_checklist.checklist_type' => $vTermProgress,'terms_progress_checklist.sequence' => ($request->meeting_log_seq) ])->get();

		if(($vTermProgress == 1 && $request->meeting_log_seq > 1) || $vTermProgress == 2 || $request->viewflag == 1){
			if($vTermProgress == 1 && $request->meeting_log_seq > 1){
				$aPreviousMeetingInfo = $meetinglog->where(['meeting_log_type' => $vTermProgress, 'item_id' => $request->id, 'meeting_log_seq' => ($request->meeting_log_seq-1)])->get();
			}
			else {
				if($vTermProgress == 2 && $request->meeting_log_seq == 1){
					$aPreviousMeetingInfo = $meetinglog->where(['meeting_log_type' => 1, 'meeting_log_seq' => 5,'item_id' => $request->id])->get();
				}
				else {
					$aPreviousMeetingInfo = $meetinglog->where(['meeting_log_type' => $vTermProgress, 'item_id' => $request->id, 'meeting_log_seq' => ($request->meeting_log_seq-1)])->get();
				}
			}
		}
		
		$aMeetingInfo = $meetinglog->where(['meeting_log_type' => $vTermProgress, 'meeting_log_seq' => $request->meeting_log_seq,'item_id' => $request->id])->get();

		if(Auth::user()->role_id == 4)
			$vRoutePath = 'mythesis.student.view_meeting_log';
		else if(Auth::user()->role_id == 3 || (Auth::user()->role_id == 2 && $aItems[0]->assigned_to == Auth::user()->id && $request->action == 'ac'))
			$vRoutePath = 'mythesis.supervisor.view_meeting_log';
		else if(Auth::user()->role_id == 2)
			$vRoutePath = 'mythesis.manager.view_meeting_log';
		else 
			$vRoutePath = 'mythesis.panel.view_meeting_log';

        return view($vRoutePath,['iteminfo' => $aItems, 'previousmeetinglogifo' => $aPreviousMeetingInfo, 'meetinglogifo' => $aMeetingInfo ,'logterm' => $vTermProgress,'logseq' => $request->meeting_log_seq,'progresschecklist' => $aProgressChecklist,'action_type' => 1,'storePath' => route('meetinglogs.store', $request->id)]);
    	
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,  User $userModel, MeetingLogs $meetinglog, Item $itemModel,ThesisProgressTrackings $tracking)
    {  
    	$aThesisInfo = $itemModel::find($request->thesis_id);    	
		switch($request->meeting_log_seq){				
			case 1:
				$vLogname = "minutes1";
				break;
			case 2:
				$vLogname = "minutes2";
				break;
			case 3:
				$vLogname = "minutes3";
				break;
			case 4:
				$vLogname = "minutes4";
				break;
			case 5:
				$vLogname = "minutes5";
				break;				
		}
							
        if(Auth::user()->role_id == 4) {
        	$vRequestID = $meetinglog->insertGetId([
						'user_id' => Auth::user()->id,
						'item_id' => $request->thesis_id,
						'request_detail_id' => $request->request_detail_id,
						'milestone_achived_last_meeting' => $request->milestone_achived_last_meeting,
						'discussed_this_meeting' => $request->issuesprogress,
						'meeting_date' => Carbon::parse($request->meetingdate)->format('Y-m-d'),
						'meeting_log_type' => $request->meeting_log_type,
						'meeting_log_seq' => ($request->meeting_log_seq),
						'student_submit_status' => 1,
						'created_date' =>now()
						]);

        	if($vRequestID > 0) {
        		//Insert message details
				$vTrackID = $tracking->insertGetId([
							'user_id' => Auth::user()->id,
							'item_id' => $request->thesis_id,
							'request_detail_id' => $request->request_detail_id,
							'action_type' => 15,
							'description' => '<p>Term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' prepared and submitted successfully by '.Auth::user()->name."</p>",
							'created_date' =>now()
							]);
				$request->merge(['msg_item_id' => $request->thesis_id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);

				TermProgressChecklist::where(['item_id'=>$request->thesis_id,'document_type' => $vLogname,'checklist_type' => $request->meeting_log_type])->update(['user_id' => Auth::user()->id,'student_upload_status' => 1]);

				//Send request confirmation mail to Student
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
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Term -".$request->meeting_log_type." meeting minutes - ".$request->meeting_log_seq." submitted successfully.";
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.',</p>
									<p>Thesis Title:'.$aThesisInfo->name.' </p>
									<p>Kindly note, your <strong>thesis term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' submitted successfully </strong>in the system. You will get notified once it is approved by the supervisor.</p><p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;

				$aSupervisorInfo = $userModel::find($aThesisInfo->assigned_to);
				
				//Send request notification mail to supervisor
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
					$mail->AddAddress($aSupervisorInfo->email, $aSupervisorInfo->name);
					$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Term -".$request->meeting_log_type." meeting minutes - ".$request->meeting_log_seq." submitted successfully.";
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.$aSupervisorInfo->name.',</p>	
									<p>Thesis Title:'.$aThesisInfo->name.' </p>
									<p>Kindly note, <strong>thesis term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' prepared and submitted successfully by '.Auth::user()->name.' </strong>in the system and is waiting for your action to approve the meeting request scheduled on <strong>'.Carbon::parse($request->meetingdate)->format('d-m-Y').'</strong>.</p>
									<p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;	

				return redirect()->route('mythesis.detail',"id=".$aThesisInfo->id.'&tab=4')->withStatus(__('Meeting minutes successfully created.'));
        	}
			else {
				return redirect()->route('mythesis.detail',"id=".$aThesisInfo->id.'&tab=4')->withStatus(__('Meeting minutes creation failed. Please try again later'));
			}
        }

        if(Auth::user()->role_id == 3 || (Auth::user()->role_id == 2 && $aThesisInfo->assigned_to == Auth::user()->id)) {	

        	// approve_flag = 1  Approved , approve_flag = 2 Completed
        	if($request->approve_flag == 1){
    			$vUpdateResp = $meetinglog->where(['meeting_logs.id'=>$request->meeting_log_id,'meeting_logs.meeting_log_seq'=>$request->meeting_log_seq,'meeting_logs.item_id'=>$request->thesis_id])
								->update(['meeting_logs.milestone_achived_last_meeting' => $request->milestone_achived_last_meeting, 
										'meeting_logs.discussed_this_meeting' => $request->issuesprogress, 										
										'meeting_logs.meeting_date' =>  Carbon::parse($request->meetingdate)->format('Y-m-d'),
										'supervisor_id' => Auth::user()->id,
										'supervisor_approval_status' => 1
									]);

	        	if($vUpdateResp > 0) {
	        		//Insert message details
					$vTrackID = $tracking->insertGetId([
								'user_id' => Auth::user()->id,
								'item_id' => $request->thesis_id,
								'request_detail_id' => $request->request_detail_id,
								'action_type' => 16,
								'description' => '<p>Term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' meeting schedule approved successfully by '.Auth::user()->name."</p>",
								'created_date' =>now()
								]);
					$request->merge(['msg_item_id' => $request->thesis_id,'msg_track_id' => $vTrackID]);
					app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);

					TermProgressChecklist::where(['item_id'=>$request->thesis_id,'document_type' => $vLogname,'checklist_type' => $request->meeting_log_type])->update(['user_id' => Auth::user()->id,'student_upload_status' => 2]);

					//Send request confirmation mail to Student
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
					
					$mail->Subject = "ADSM Thesis Manager Notification - Thesis Term -".$request->meeting_log_type." meeting minutes - ".$request->meeting_log_seq." schedule approved successfully.";
					
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.Auth::user()->name.',</p>
										<p>Thesis Title:'.$aThesisInfo->name.' </p>
										<p>Kindly note, you have approved <strong>thesis term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' schedule successfully </strong>in the system.</p><p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;

					$aStudentInfo = $userModel::find($aThesisInfo->requested_by);
					
					//Send request notification mail to supervisor
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
					
					$mail->Subject = "ADSM Thesis Manager Notification - Thesis Term -".$request->meeting_log_type." meeting minutes - ".$request->meeting_log_seq." schedule successfully approved by ".Auth::user()->name;
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$aStudentInfo->name.',</p>	
										<p>Thesis Title:'.$aThesisInfo->name.' </p>
										<p>Kindly note, your <strong>thesis term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' schedule successfully approved by '.Auth::user()->name.' </strong>in the system and is waiting for your action to meet the supervisor on <strong>'.Carbon::parse($request->meetingdate)->format('d-m-Y').'</strong> .</p>
										<p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;	

					return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__('Meeting minutes schedule approved successfully.'));
				}
				else {
					return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__('Meeting minutes update failed. Please try again later'));
				}
			}
        	else if($request->approve_flag == 2){
        		$vUpdateResp = $meetinglog->where(['meeting_logs.id'=>$request->meeting_log_id,'meeting_logs.meeting_log_seq'=>$request->meeting_log_seq,'meeting_logs.item_id'=>$request->thesis_id])
								->update(['meeting_logs.milestone_achived_last_meeting' => $request->milestone_achived_last_meeting, 
										'meeting_logs.discussed_this_meeting' => $request->issuesprogress, 
										'meeting_logs.next_meeting_agenda' => $request->nextmeetingagenda,	
										'meeting_logs.next_meeting_date' =>  Carbon::parse($request->nextmeetingdate)->format('Y-m-d'),
										'supervisor_id' => Auth::user()->id,
										'modified_date' => now(),
										'supervisor_approval_status' => 2
									]);

	        	if($vUpdateResp > 0) {
	        		//Insert message details
					$vTrackID = $tracking->insertGetId([
								'user_id' => Auth::user()->id,
								'item_id' => $request->thesis_id,
								'request_detail_id' => $request->request_detail_id,
								'action_type' => 17,
								'description' => '<p>Term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' completed successfully by '.Auth::user()->name."</p>",
								'created_date' =>now()
								]);
					$request->merge(['msg_item_id' => $request->thesis_id,'msg_track_id' => $vTrackID]);
					app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);

					TermProgressChecklist::where(['item_id'=>$request->thesis_id,'document_type' => $vLogname,'checklist_type' => $request->meeting_log_type])->update(['user_id' => Auth::user()->id,'student_upload_status' => 3]);

					//Send request confirmation mail to Student
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
					
					$mail->Subject = "ADSM Thesis Manager Notification - Thesis Term -".$request->meeting_log_type." meeting minutes - ".$request->meeting_log_seq." completed successfully.";
					
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.Auth::user()->name.',</p>
										<p>Thesis Title:'.$aThesisInfo->name.' </p>
										<p>Kindly note, you have completed <strong>thesis term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' successfully </strong>in the system.</p><p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;

					$aStudentInfo = $userModel::find($aThesisInfo->requested_by);
					
					//Send request notification mail to supervisor
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
					
					$mail->Subject = "ADSM Thesis Manager Notification - Thesis Term -".$request->meeting_log_type." meeting minutes - ".$request->meeting_log_seq." completed successfully by ".Auth::user()->name;
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$aStudentInfo->name.',</p>	
										<p>Thesis Title:'.$aThesisInfo->name.' </p>
										<p>Kindly note, your <strong>thesis term - '.$request->meeting_log_type.' meeting minutes - '.$request->meeting_log_seq.' successfully completed by '.Auth::user()->name.' </strong>in the system and is waiting for your action.</p>
										<p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;

					return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__('Meeting minutes completed successfully.'));
				}
				else {
					return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__('Meeting minutes update failed. Please try again later'));
				}
        	}
        	else {
        		return redirect()->route('mythesis.detail',$aThesisInfo->id.'&action=ac&tab=4')->withStatus(__('Meeting minutes update failed. Please try again later'));
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
