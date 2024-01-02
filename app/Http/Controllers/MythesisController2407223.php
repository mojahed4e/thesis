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

use Mail;
use DB;
use App\Tag;
use App\Term;
use App\Item;
use App\User;
use App\Category;
use App\Mythesis;
use App\GroupMember;
use App\PanelMembers;
use App\ItemAssignment;
use App\ThesisAttachments;
use App\ThesisProgressTrackings;
use App\ThesisTimeline;
use App\ThesisProgressTimeline;
use App\ThesisRequestDetails;
use App\TermProgressChecklist;
use App\MessageViewesTracking;
use App\MeetingLogs;
use Carbon\Carbon;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\AcceptRequest;
use App\Http\Requests\ItemRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MythesisController extends Controller
{
    public function __construct()
    {
       // $this->authorizeResource(Item::class);
    }

    /**
     * Display a listing of the items
     *
     * @param \App\Item  $model
     * @return \Illuminate\View\View
     */
    public function index(Item $model)
    {		
		$items = $model->Status()->with(['tags', 'category','term'])->get();
		return view('mythesis.index', ['items' => $items]);
    }
	
	 /**
     * Display a listing of the items
     *
     * @param \App\Item  $model
     * @return \Illuminate\View\View
     */
    public function vewAssignedThesisDetails(Item $model)
    {		
		$items = $model->Status()
							->where('assigned_to', '=', Auth::user()->id)->with(['tags', 'category','term'])->get();	
		$reqDetails = $model->Status()->select('thesis_request_details.*')
								->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->get();
		return view('mythesis.index', ['items' => $items,'requestinfo' => $reqDetails]);
    }

     /**
     * Display a listing of the items
     *
     * @param \App\Item  $model
     * @return \Illuminate\View\View
     */
    public function vewExamineThesisDetails(Item $model,PanelMembers $panelmembers)
    {	
    	$items = array();
    	$aExamineThesisDetails = $panelmembers->select('panel_members.item_id')->where('user_id','=',Auth::user()->id)->get();	

    	if(count($aExamineThesisDetails) > 0){
    		$items = $model->Status()
							->whereIn('id',$aExamineThesisDetails)->with(['tags', 'category','term'])->get();
    	}	
		$reqDetails = $model->Status()->select('thesis_request_details.*')
								->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->get();
		$aSupervisors = $model->Status()->select('users.*')
								->join('users','users.id','=','items.assigned_to')->distinct()->get();
		$aCohorts = $model->Status()->select('terms.*')
								->join('terms','terms.id','=','items.term_id')->distinct()->get();

		$aAcademic_year = $model->Status()->select('terms.id','terms.academic_year')
							->join('terms','terms.id','=','items.term_id')->distinct()->get();

		$aProgams = $model->Status()->select('programs.*')
								->join('programs','programs.id','=','items.program_id')->distinct()->get();

		return view('items.panel.index', ['items' => $items,'requestinfo' => $reqDetails,'supervisors' => $aSupervisors,'cohorts' => $aCohorts,'programs' => $aProgams]);
    }

	
	/**
     * Show the form for editing the specified item
     *
     * @param  \App\Item  $item
     * @param  \App\Term   $termModel
     * @param  \App\Category $categoryModel
     * @return \Illuminate\View\View
     */
    public function vewThesisDetails(Request $request, Item $item, User $userModel, TermProgressChecklist $progress, Term $termModel, Category $categoryModel, MeetingLogs $meetinglogModel, GroupMember $groupmemberModel, ItemAssignment $assignmentModel,ThesisAttachments $attachment, ThesisProgressTrackings $tracking, ThesisRequestDetails $requsetModel, ThesisProgressTimeline $progresstimeline)
    {
		$requested = 0;
		$thesisprogress['progress'] = [];		
		$thesisprogress['attachments'] = [];		
		if(Auth::user()->role_id == 4) {
			$vReqThesis = $item->Status()->where('requested_by', '=', Auth::user()->id)->get();
			if(count($vReqThesis) > 0) {			
				$thesis_id = $vReqThesis[0]->id;				
				$request_detail_id = $vReqThesis[0]->request_detail_id;
				$requested = 1;
				$item = Item::find($thesis_id);
			}			
			else {
				return redirect()->route('item.index')->withError(__('Thesis not found.'));
			}

			$trackinfo = $tracking->select('thesis_progress_trackings.*','users.role_id','users.name','users.email')
								->join('users','users.id','=','thesis_progress_trackings.user_id')
								->where(['thesis_progress_trackings.request_detail_id' => $request_detail_id])->orderBy('thesis_progress_trackings.id', 'asc')->get();
			
			if(count($trackinfo) > 0) {
				$loop = 0;
				for($track_loop = 0; $track_loop < count($trackinfo); $track_loop++) {
					$aAttchments = $attachment->select('thesis_attachments.file_name','thesis_attachments.file_path','thesis_attachments.user_id','thesis_attachments.track_id',									  'thesis_attachments.id')
											->where(['thesis_attachments.track_id' => $trackinfo[$track_loop]->id,'thesis_attachments.item_id' => $thesis_id])->get();
					$thesisprogress['progress'][$loop] = $trackinfo[$track_loop];
					$thesisprogress['attachments'][$loop] = $aAttchments;	
					$loop++;
				}
			}
			
		}
		else {
			
			$item_id = array_keys(request()->query()) ? array_keys(request()->query()) : request()->item_id;
			if($item_id) {				
				$requested = 1;	
				$thesis_id = $item_id[0];			
				$item = Item::find($thesis_id);				
				if(!empty($item)) {
					if($item->request_detail_id > 0 && $item->status  == 1) {
						$request_detail_id = $item->request_detail_id;
						
						$trackinfo = $tracking->select('thesis_progress_trackings.*','users.role_id','users.name','users.email')
											->join('users','users.id','=','thesis_progress_trackings.user_id')->orderBy('thesis_progress_trackings.id', 'asc')
											->where(['thesis_progress_trackings.request_detail_id' => $request_detail_id])->get();									
						
						if(count($trackinfo) > 0) {
							$loop = 0;
							for($track_loop = 0; $track_loop < count($trackinfo); $track_loop++) {
								$aAttchments = $attachment->select('thesis_attachments.file_name','thesis_attachments.file_path','thesis_attachments.user_id','thesis_attachments.track_id',									  'thesis_attachments.id')
														->where(['thesis_attachments.track_id' => $trackinfo[$track_loop]->id,'thesis_attachments.item_id' => $thesis_id])->get();
								$thesisprogress['progress'][$loop] = $trackinfo[$track_loop];
								$thesisprogress['attachments'][$loop] = $aAttchments;	
								$loop++;
							}
						}
					}
					else 
						return redirect()->route('item.index')->withError(__('Thesis not found.'));
				}
				else 
					return redirect()->route('item.index')->withError(__('Thesis not found.'));
			}
			else
				return redirect()->route('item.index')->withError(__('Thesis not found.'));
		}

		if(Auth::user()->role_id == 2){
			if($request->action == 'ac')
				$vResourceFile = 'mythesis.supervisor.detail';
			else
				$vResourceFile = 'mythesis.manager.detail';
		}
		else if(Auth::user()->role_id == 3)
			$vResourceFile = 'mythesis.supervisor.detail';
		else if(Auth::user()->role_id == 4)
			$vResourceFile = 'mythesis.student.detail';
		else if(Auth::user()->role_id == 5)
			$vResourceFile = 'mythesis.panel.detail';
		else
			$vResourceFile = 'mythesis.detail';

		$aTermDetails = $requsetModel->select('thesis_request_details.*')
							            ->join('items','items.request_detail_id','=','thesis_request_details.id')
							            ->where(['thesis_request_details.id'=> $request_detail_id])->get();
		if(count($aTermDetails) > 0){
			if($aTermDetails[0]->progress_completion == 0)
				$vTermProgressStat = 1;
			else
				$vTermProgressStat = 1;
		}
		
        return view($vResourceFile, [
            'item' => $item->load('tags'),
            'timelineinfo'	=> $progresstimeline->Active()->where(['item_id' => $thesis_id])->get(),
            'itemtags' => DB::table('item_tag')->select('tags.*')
            					->join('tags','tags.id','=','item_tag.tag_id')
            					->where(['item_id' => $thesis_id,'item_tag.status' => 1])->get(),
			'requested' => $requested,	
			'requestdetails' => $requsetModel->select('thesis_request_details.*')
							            ->join('items','items.request_detail_id','=','thesis_request_details.id')
							            ->where(['thesis_request_details.id'=> $request_detail_id])->get(),
			'supervisors' => $userModel->Available()->where(function($query)
															{
																$query->where(['role_id' => 2,"manager_flag" => 1])
																->orWhere('role_id','=',3);
															})
													->where('status','=',1)->get(['id', 'name','program_availability']),
			'prefsupervisor' => $userModel->select('users.id','users.name','users.email')
							            ->join('item_assignments','item_assignments.user_id','=','users.id')
							            ->where(['item_assignments.item_id' => $thesis_id, 'item_assignments.request_detail_id'=> $request_detail_id,
							        		'item_assignments.status' => 1])->get(),
			'panelmembers' => $userModel->select('users.id')
							            ->join('panel_members','panel_members.user_id','=','users.id')
							            ->where(['panel_members.item_id' => $thesis_id,
							        		'panel_members.status' => 1])->get(),
			'groupowner' => $userModel->select('users.id','users.name','users.email')
							            ->join('items','items.requested_by','=','users.id')
							            ->where(['items.id' => $thesis_id, 'items.request_detail_id' => $request_detail_id])->get(),
			'meetinglogs' => $meetinglogModel->select('meeting_logs.*')
										->orderBy('meeting_logs.meeting_log_seq', 'desc')
							            ->where(['meeting_logs.item_id' => $thesis_id, 'meeting_logs.request_detail_id' => $request_detail_id,'meeting_log_type' => 1])->get(),	
			'meetinglogsterm2' => $meetinglogModel->select('meeting_logs.*')
										->orderBy('meeting_logs.meeting_log_seq', 'desc')
							            ->where(['meeting_logs.item_id' => $thesis_id, 'meeting_logs.request_detail_id' => $request_detail_id,'meeting_log_type' => 2])->get(),			            			
			'progressdetails' => $progress->select('terms_progress_checklist.*')
							            ->join('items','items.id','=','terms_progress_checklist.item_id')
										 ->orderBy('terms_progress_checklist.sequence', 'asc')
							            ->where([['terms_progress_checklist.item_id','=', $thesis_id],['terms_progress_checklist.checklist_type','=', 1],['terms_progress_checklist.upload_file_status','>',-1],['terms_progress_checklist.status','=',1]])->get(),
			'term2progressdetails' => $progress->select('terms_progress_checklist.*')
							            ->join('items','items.id','=','terms_progress_checklist.item_id')
										->orderBy('terms_progress_checklist.sequence', 'asc')
							            ->where([['terms_progress_checklist.item_id','=', $thesis_id],['terms_progress_checklist.checklist_type','=', 2],['terms_progress_checklist.upload_file_status','>',-1],['terms_progress_checklist.status','=',1]])->get(),
			'term3progressdetails' => $progress->select('terms_progress_checklist.*')
							            ->join('items','items.id','=','terms_progress_checklist.item_id')
										->orderBy('terms_progress_checklist.sequence', 'asc')
							            ->where([['terms_progress_checklist.item_id','=', $thesis_id],['terms_progress_checklist.checklist_type','=', 2],['terms_progress_checklist.upload_file_status','>',-1],['terms_progress_checklist.status','=',1]])->get(),
			'progressmessage' => $tracking->select('thesis_progress_trackings.*')
										  ->orderBy('thesis_progress_trackings.id', 'desc')
										  ->limit(1)
										  ->where([['thesis_progress_trackings.request_detail_id','=', $request_detail_id],['thesis_progress_trackings.user_id','!=',Auth::user()->id],['thesis_progress_trackings.term_flag','=',1]])->get(),
			'trackinginfo' => $thesisprogress,
            'terms' => $termModel->Active()->get(['id', 'name']),
			'upload' => route('mythesis.comment-update', $thesis_id),
			'acceptPath' => route('mythesis.request-accept', $thesis_id),
			'successPath' => route('mythesis.progress-update', $thesis_id),
            'categories' => $categoryModel->Active()->get(['id', 'name'])
        ]);
    }
	
	
	/**
	 * Update Thesis comments
     *
     * @param  \App\Http\Requests\AllocationRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateThesisComments($id, CommentRequest $request, Item $item, ThesisAttachments $attachment, ThesisProgressTrackings $tracking)
    {		
		$vDescription   = trim($request->description);
		$vTrackID		= trim($request->track_id);
		$aItemDetails 	= $item::find($id);
		if((int) $request->update_comments == 1) {
			
			    //Send Message post confirmation email
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
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis message posted by ".Auth::user()->name;
				if(Auth::user()->role_id == 4) {				
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.Auth::user()->name.',</p>
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Your Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>you have successfully posted a message.</strong> </p><p>You will get notified once it is reviewed successfully by the Supervisor/Manager and posted any response in the system. </p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
				}
				else {
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.Auth::user()->name.',</p>
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Your Message:'.$vDescription.' </p>
										<p> Kindly note, <strong>you have successfully posted a message.</strong></p><p> You will get notified once it is reviewed successfully and posted any response in the system. </p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
				}
		
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				
				
				//Send Message post notification email to Manager and Supervisor
				if($aItemDetails->assigned_to > 0) {
					$aSupervisorInfo = User::find($aItemDetails->assigned_to);
				}
				
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

				//$mail->AddAddress(Auth::user()->email, Auth::user()->name);
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Message Posted.";
				
				if(Auth::user()->role_id == 4) {
					if(config('mail.email_flag') > 0) {							
						$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));						
						if($aItemDetails->assigned_to > 0) {
							$mail->AddAddress($aSupervisorInfo->email, $aSupervisorInfo->name);
							$vDearName = config('mail.manager.name').",".$aSupervisorInfo->name;
						}
						else
							$vDearName = config('mail.manager.name');
						$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					else {
						$vDearName = config('mail.dev_email.name');
						//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
						$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$vDearName.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>'.Auth::user()->name.' posted a message in the system and waiting for your action.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>	
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>								
										<p>Thank you</p>
										<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
				}
				else {
					if(Auth::user()->role_id == 3 && $aItemDetails->assigned_to > 0) {						
						$vPersonText = Auth::user()->name;
						$vDearName	= config('mail.manager.name');
						
						if(config('mail.email_flag') > 0) {							
							$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {							
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						
						
					}
					else if(Auth::user()->role_id == 2 &&  $aItemDetails->assigned_to > 0) {
						$vDearName = $aSupervisorInfo->name;
						$vPersonText = config('mail.manager.name');
						if(config('mail.email_flag') > 0) {							
							$mail->AddAddress($aSupervisorInfo->email, $aSupervisorInfo->name);
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {							
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
					}
					else {
						//Send Thesis 				
						$aStudentInfo = User::find($aItemDetails->requested_by);						
						$vDearName = $aStudentInfo->name;
						$vPersonText	= Auth::user()->name;
						if(config('mail.email_flag') > 0) {	
							if($request->private_message == 1) {
								$mail->AddAddress($aStudentInfo->email, $aStudentInfo->name);
							}							
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {
							//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
					}
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$vDearName.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>'.$vPersonText.' posted a message in the system and waiting for your action.</strong> </p><p>You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>			
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>						
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
				}
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				
				if((Auth::user()->role_id == 3 || Auth::user()->role_id == 2) && $request->private_message == 1) {
					
					$aStudentInfo = User::find($aItemDetails->requested_by);

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
					
					$vPersonText = Auth::user()->name;
					$vDearName	= $aStudentInfo->name;
					
					if(config('mail.email_flag') > 0) {							
						$mail->AddAddress($aStudentInfo->email, $aStudentInfo->name);						
						$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					else {						
						$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					
					$mail->Subject = "ADSM Theisi Manager Notification - Thesis Message Posted.";
					
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$vDearName.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>'.$vPersonText.' posted a message in the system and waiting for your action.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>			
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>						
										<p>Thank you</p>
										<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;
				}
				

			if($vTrackID == 0) {					
				$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'item_id' => $id,
												'description' => $vDescription,
												'message_type' => $request->private_message ? $request->private_message : 1,
												'request_detail_id' => $aItemDetails->request_detail_id,	
												'created_date' =>now()
											]);
			}
			else {
				//Update request detail id
				$tracking->where(['id'=>$vTrackID])->update(['request_detail_id' => $aItemDetails->request_detail_id]);
			}
			
			$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
			app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);	
				
			if(!empty($request->action_page))
				return redirect()->route('item.approve',array("id" => $id,'tab'=>2) )->withStatus(__('Comment successfully updated.'));
			else  			
				return redirect()->route('mythesis.detail',$id."&tab=2&action=".$request->action_flag )->withStatus(__('Comment successfully updated.'));
			
		}
		
		if($request->hasFile('thesisfiles')) {
			
			if($vTrackID == 0) {					
				$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'item_id' => $id,
												'description' => $vDescription,
												'message_type' => $request->private_message ? $request->private_message : 1,
												'request_detail_id' => $aItemDetails->request_detail_id,	
												'created_date' =>now()
											]);
			}
			else	
				$vInsertID = $vTrackID;
			
			if($vInsertID) {
				$aFile = request()->file('thesisfiles');				
				$file     = request()->file('thesisfiles');
				$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();
				$filePath = "/thesis" . $id . '/user' . Auth::user()->id . "/" . $fileName;
				$file->storeAs("/thesis" . $id . '/user' . Auth::user()->id . "/", $fileName, 'attachments_folder');
				
				$vAttachID = $attachment->insert(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'track_id' => $vInsertID,
													'file_name' => $file->getClientOriginalName(),
													'file_path' => $filePath,
													'created_date' =>now()
												]);
			
				return response()->json(['file_name' => $fileName, 'path' => $filePath, 'file_extension' => $file->getClientOriginalExtension(),'track_id' => $vInsertID]);
			}				
		}
	}
	
	
	/**
	 * Update Thesis Allocation Request Details
     *
     * @param  \App\Http\Requests\AllocationRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateThesisRequestApprovalStatus($id, ApproveRequest $request,ItemAssignment $assignmentModel, Item $item, ThesisAttachments $attachment, GroupMember $groupmemberModel, ThesisProgressTrackings $tracking,ThesisRequestDetails $requsetModel, ThesisProgressTimeline $progresstimeline, ThesisTimeline $timeline)
    {
		$vDescription   = trim($request->approvemessage);
		$vStatus 		= trim($request->approve_status);
		$vSupervisor_id	= trim($request->supervisor_id);
		$vTrackID		= trim($request->approve_track_id);
		$vTerm1Date		= $request->term1date ? Carbon::parse($request->term1date)->format('Y-m-d') : NULL ;
		$vTerm2Date		= $request->term2date ? Carbon::parse($request->term2date)->format('Y-m-d') : NULL ;
		$vTerm3Date		= $request->term3date ? Carbon::parse($request->term3date)->format('Y-m-d') : NULL ;
		$aItemDetails 	= $item::find($id);
		
		if((int) $request->approve_update_comments == 1) {			
						
			if((int)$vStatus == 1) {				
				$item->where(['id'=>$id])->update(
												['assigned_to' => $vSupervisor_id,'approval_status' => $vStatus,'request_approval_flag' => 1] 				
											);

				//#---------
				//#---------

				$assignmentModel->where(['item_id' => $aItemDetails->id,'request_detail_id' => $aItemDetails->request_detail_id])->update(['user_id' => $vSupervisor_id]);

				$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['manager_approval_status' => $vStatus,'manager' => Auth::user()->id,'termI_completion_date' => $vTerm1Date,'termII_completion_date' => $vTerm2Date,'termIII_completion_date' => $vTerm3Date ]); 

				$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'action_type' => 2,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'description' => $vDescription,
													'created_date' =>now()
												]);
				$aTimelineInfo = $timeline->Active()->where(['term_id' => $aItemDetails->term_id, 'program_id' => $aItemDetails->program_id])->get();

				if(count($aTimelineInfo) > 0){
					$vTimelineProID = $progresstimeline->insertGetId(
									['timeline_id' => $aTimelineInfo[0]->timeline_id,
									'item_id' => $id,
									'created_by' => Auth::user()->id,
									'term1_completion' => $aTimelineInfo[0]->term1_completion,	
									't1_meeting_minutes1' => $aTimelineInfo[0]->t1_meeting_minutes1,
									't1_meeting_minutes2' => $aTimelineInfo[0]->t1_meeting_minutes2,
									't1_meeting_minutes3' => $aTimelineInfo[0]->t1_meeting_minutes3,
									't1_meeting_minutes4' => $aTimelineInfo[0]->t1_meeting_minutes4,
									't1_meeting_minutes5' => $aTimelineInfo[0]->t1_meeting_minutes5,
									'term2_completion' 	=> $aTimelineInfo[0]->term2_completion,	
									't2_meeting_minutes1' => $aTimelineInfo[0]->t2_meeting_minutes1,
									't2_meeting_minutes2' => $aTimelineInfo[0]->t2_meeting_minutes2,
									't2_meeting_minutes3' => $aTimelineInfo[0]->t2_meeting_minutes3,
									't2_meeting_minutes4' => $aTimelineInfo[0]->t2_meeting_minutes4,
									't2_meeting_minutes5' => $aTimelineInfo[0]->t2_meeting_minutes5,
									'created_at' =>now()
									]);
				}

												
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
												
				//Send request approved mail to Manager
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
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Request Approved Successfully.";
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.',</p>
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Kindly note, <strong>you have successfully approved the thesis.</strong></p><p> You will get notified once it is accepted by the the Supervisor. Also track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				
				//Send approved notification mail to Supervisor							
				$aSupervisorInfo = User::find($vSupervisor_id);
				
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
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Request Approved by ".Auth::user()->name;				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.$aSupervisorInfo->name.',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>									
									<p>Kindly note, a <strong>thesis request assigned to you and approved successfully by '.Auth::user()->name.' in the system and waiting for your action(Accept/Reject)</strong>. </p><p>You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				
				return redirect()->route('item.index',$id)->withStatus(__('Request approval successfully updated.'));
			}
			else if((int)$vStatus == 2) {				
				$vMessage = "Comment and files posted successfully";				
				if($vTrackID == 0) {					
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'description' => $vDescription,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);
					$vMessage = "Comment posted successfully";
				}
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
				
				//Send Message post confirmation email to Student
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
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Message Posted by ".Auth::user()->name;
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.',</p>
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Your Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>you have successfully posted a message.</strong></p><p> You will get notified once it is reviewed successfully and posted response response in the system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;				
				
				//Send Message post notification email to Manager and Supervisor
				$aStdentInfo = User::find($aItemDetails->requested_by);
				
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
					$mail->AddAddress($aStdentInfo->email, $aStdentInfo->name);					
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Message Posted by ".Auth::user()->name;				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.$aStdentInfo->name.',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>'.Auth::user()->name.' posted a message in the system and waiting for your action.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>				
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				
				return redirect()->route('item.approve',$id)->withStatus(__($vMessage));
			}
			else {
				if($aItemDetails->user_role_id == 4) {						
					$item->where(['id' => $id])->update([
													'assigned_to' => 0, 
													'approval_status' => 0,
													'status' => 3
												]);				

					$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['supervisor_acceptence_status' => 0,'supervisor' => 0,'manager_approval_status' => 0,'status' => 2]);	
					$assignmentModel->where(['item_id'=>$id,'user_id' => Auth::user()->id])->update(
													['status' => 2]);				
				}
				else {
					$item->where(['id' => $id])->update([
													'assigned_to' => 0, 
													'approval_status' => 0,
													'requested_by' => 0
												]);						
					$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['manager_approval_status' => $vStatus,'manager' => Auth::user()->id,'status' => 2]);
					$assignmentModel->where(['item_id'=>$id,'user_id' => Auth::user()->id])->update(
													['status' => 2]);
				}
				
				$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'action_type' => 3,
													'description' => $vDescription,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);
				
				//Send Thesis Request Rejected email to Student				
				$aStudentInfo = User::find($aItemDetails->requested_by);
				
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
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis request rejected by ".Auth::user()->name;
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.$aStudentInfo->name.',</p>
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Rejection Message:'.$vDescription.' </p>';
				if($aItemDetails->user_role_id == 4)
					$mail->Body .= '<p>Kindly note, <strong>your proposed thesis title rejected by '.Auth::user()->name.'.</strong> So please create some other title or select any one title available in the system.</p><p> For further assistance and clarification please contact '.Auth::user()->name.'.</p>';
				else
					$mail->Body .= '<p>Kindly note, <strong>your requested thesis rejected by '.Auth::user()->name.'.</strong> So please propose some other topic or select any other title available in the system.</p><p> For further assistance and clarification please contact '.Auth::user()->name.'.</p>';

				$mail->Body .= '<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the 			Thesis</p>
								<p>Thank you</p>
								<p>&nbsp;</p>
								<p>'.config('mail.mail_bottom').'</p>
							</body>
							</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				
				//Send Thesis Request Rejected confirmation to Manager
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
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis rejected by ".Auth::user()->name;				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.' ,</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Rejection Message:'.$vDescription.' </p>';
				if($aItemDetails->user_role_id == 4)
					$mail->Body .= '<p>Kindly note, <strong>You have successfully rejected '.$aStudentInfo->name.' proposed thesis</strong>.</p>';
				else
					$mail->Body .= '<p>Kindly note, <strong>You have successfully rejected '.$aStudentInfo->name.' thesis request</strong> and it is available for other students in the system. </p>';												
				$mail->Body .= '<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
						<p>Thank you</p>
						<p>&nbsp;</p>
						<p>'.config('mail.mail_bottom').'</p>
					</body>
					</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				return redirect()->route('item.index',$id)->withStatus(__('Request rejected successfully.'));
			}
			
		}
		else {
			if($request->hasFile('approvefiles')) {
				
				if($vTrackID == 0) {					
					$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'description' => $vDescription,
													'created_date' =>now()
												]);
				}
				else	
					$vInsertID = $vTrackID;
				
				if($vInsertID) {
					$file     = request()->file('approvefiles');					
					$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();
					$filePath = "/thesis" . $id . '/user' . Auth::user()->id . "/" . $fileName;
					$file->storeAs("/thesis" . $id . '/user' . Auth::user()->id . "/", $fileName, 'attachments_folder');
					
					$vAttachID = $attachment->insert(['user_id' => Auth::user()->id,
														'item_id' => $id,
														'track_id' => $vInsertID,
														'file_name' => $file->getClientOriginalName(),
														'file_path' => $filePath,
														'created_date' =>now()
													]);
				
					return response()->json(['file_name' => $fileName, 'path' => $filePath, 'file_extension' => $file->getClientOriginalExtension(),'approve_track_id' => $vInsertID]);
				}				
			}
		}
    }
	
	/**
	 * Update Thesis Request Accepts by Supervisor
     *
     * @param  \App\Http\Requests\AllocationRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateThesisRequestAcceptStatus($id, AcceptRequest $request, Item $item, ThesisAttachments $attachment, ThesisProgressTrackings $tracking, GroupMember $groupmemberModel, ThesisRequestDetails $requsetModel,ItemAssignment $assignment,TermProgressChecklist $termprogress, ThesisProgressTimeline $progresstimeline,ThesisTimeline $timeline,PanelMembers $panelmembers)
    {		
		$vDescription   = trim($request->approvemessage);
		$vStatus 		= trim($request->accept_status);		
		$vTrackID		= trim($request->approve_track_id);
		$vDateUpdateFlg	= trim($request->update_dates_flag);
		$vPrivateMsg	= trim($request->accp_private_message) ? trim($request->accp_private_message) : 1;
		$aItemDetails 	= $item::find($id);
		
		if($vDateUpdateFlg == 1) {
			$vMessage = 'Term dates updated successfully.';
			$vSupervisor_id = trim($request->supervisor_id);
			$vCoSupervisor_id = trim($request->cosupervisor_id) ? trim($request->cosupervisor_id) : 0;
			$vTerm1Date		= $request->term1date ? Carbon::parse($request->term1date)->format('Y-m-d') : NULL ;
			$vTerm2Date		= $request->term2date ? Carbon::parse($request->term2date)->format('Y-m-d') : NULL ;
			$vTerm3Date		= $request->term3date ? Carbon::parse($request->term3date)->format('Y-m-d') : NULL ;
			
			$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['termI_completion_date' => $vTerm1Date,'termII_completion_date' => $vTerm2Date,'termIII_completion_date' => $vTerm3Date,'manager' => Auth::user()->id]);


			if($aItemDetails->assigned_to != $vSupervisor_id) {
				$item->where(['id' => $aItemDetails->id])->update(['assigned_to' => $vSupervisor_id]);
				$requsetModel->where(['id' => $aItemDetails->request_detail_id])->update(['supervisor' => $vSupervisor_id]);
				$assignment->where(['request_detail_id' => $aItemDetails->request_detail_id,'item_id' => $aItemDetails->id])->update(['user_id' => $vSupervisor_id]);
				$vMessage = 'Term dates and Assigned Supervisor updated successfully.';
			}
			if($aItemDetails->cosupervisor != $vCoSupervisor_id) {
				$item->where(['id' => $aItemDetails->id])->update(['cosupervisor' => $vCoSupervisor_id]);
			}

			if(isset($request->thesis_examine)){
				$panelmembers->where(['item_id' =>$aItemDetails->id])->update(['status' => 2]);
				for($examine_loop = 0; $examine_loop < count($request->thesis_examine); $examine_loop++){
					$vPanelID = $panelmembers->insertGetId([
										'item_id' => $aItemDetails->id,
										'user_id' => $request->thesis_examine[$examine_loop],
										'request_detail_id' => $aItemDetails->request_detail_id
									]);
				}
			}
			$aTimeLineDetails = $timeline->Active()->where(['program_id' => $aItemDetails->program_id, 'term_id' => $aItemDetails->term_id])->get();
			if(count($aTimeLineDetails) > 0){
				$aProgrssTimelineInfo = $progresstimeline->Active()->where(['timeline_id' => $aTimeLineDetails[0]->timeline_id,'item_id' => $aItemDetails->id])->get();
				if(count($aProgrssTimelineInfo) > 0){
					$progresstimeline->where([
											'timeline_id' => $aTimeLineDetails[0]->timeline_id,'item_id' => $aItemDetails->id])
						->update([
							'term1_completion' => $request->term1date ? Carbon::parse(
								$request->term1date)->format('Y-m-d') : NULL,
							't1_meeting_minutes1' => $request->term1meet1 ? Carbon::parse(
								$request->term1meet1)->format('Y-m-d') : NULL,
							't1_meeting_minutes2' => $request->term1meet2 ? Carbon::parse(
								$request->term1meet2)->format('Y-m-d') : NULL,
							't1_meeting_minutes3' => $request->term1meet3 ? Carbon::parse(
								$request->term1meet3)->format('Y-m-d') : NULL,
							'term1chapter1' => $request->term1chapter1 ? Carbon::parse(
								$request->term1chapter1)->format('Y-m-d') : NULL,
							't1_meeting_minutes4' => $request->term1meet4 ? Carbon::parse(
								$request->term1meet4)->format('Y-m-d') : NULL,
							't1_meeting_minutes5' => $request->term1meet5 ? Carbon::parse(
								$request->term1meet5)->format('Y-m-d') : NULL,
							'term1chapter2' => $request->term1chapter2 ? Carbon::parse(
								$request->term1chapter2)->format('Y-m-d') : NULL,
							'term1presentation' => $request->term1presentation ? Carbon::parse(
								$request->term1presentation)->format('Y-m-d') : NULL,
							'term2_completion' => $request->term2date ? Carbon::parse(
								$request->term2date)->format('Y-m-d') : NULL,
							't2_meeting_minutes1' => $request->term2meet1 ? Carbon::parse(
								$request->term2meet1)->format('Y-m-d') : NULL,
							't2_meeting_minutes2' => $request->term2meet2 ? Carbon::parse(
								$request->term2meet2)->format('Y-m-d') : NULL,
							't2_meeting_minutes3' => $request->term2meet3 ? Carbon::parse(
								$request->term2meet3)->format('Y-m-d') : NULL,
							'term2chapter1' => $request->term2chapter1 ? Carbon::parse(
								$request->term2chapter1)->format('Y-m-d') : NULL,
							't2_meeting_minutes4' => $request->term2meet4 ? Carbon::parse(
								$request->term2meet4)->format('Y-m-d') : NULL,
							't2_meeting_minutes5' => $request->term2meet5 ? Carbon::parse(
								$request->term2meet5)->format('Y-m-d') : NULL,
							'term2chapter2' => $request->term2chapter2 ? Carbon::parse(
								$request->term2chapter2)->format('Y-m-d') : NULL,
							'term2presentation' => $request->term2presentation ? Carbon::parse(
								$request->term2presentation)->format('Y-m-d') : NULL,
							'updated_at' => now()
					]);
				}

				$aTermIProgressChecklist = $termprogress->where(['item_id' => $aItemDetails->id,'timeline_id' => $aTimeLineDetails[0]->timeline_id,'checklist_type' => 1])->get();
				if(count($aTermIProgressChecklist) > 0){
					foreach($aTermIProgressChecklist as $checklist){
						switch($checklist->sequence){
							case 1:
								$completion_date = $request->term1meet1 ? Carbon::parse(
											$request->term1meet1)->format('Y-m-d') : NULL;
								break;
							case 2:
								$completion_date = $request->term1meet2 ? Carbon::parse(
											$request->term1meet2)->format('Y-m-d') : NULL;
								break;
							case 3:
								$completion_date = $request->term1meet3 ? Carbon::parse(
											$request->term1meet3)->format('Y-m-d') : NULL;
								break;
							case 4:
								$completion_date = $request->term1chapter1 ? Carbon::parse(
											$request->term1chapter1)->format('Y-m-d') : NULL;
								break;
							case 5:
								$completion_date = $request->term1meet4 ? Carbon::parse(
											$request->term1meet4)->format('Y-m-d') : NULL;
								break;
							case 6:
								$completion_date = $request->term1meet5 ? Carbon::parse(
											$request->term1meet5)->format('Y-m-d') : NULL;
								break;
							Case 7:
								$completion_date = $request->term1chapter2 ? Carbon::parse(
											$request->term1chapter2)->format('Y-m-d') : NULL;
								break;
							Case 8:
								$completion_date = $request->term1presentation ? Carbon::parse(
											$request->term1presentation)->format('Y-m-d') : NULL;
								break;
						}
						$termprogress->where(['document_type' => $checklist->document_type,
										'checklist_type' => 1,
										'sequence' => $checklist->sequence,
										'timeline_id' => $aTimeLineDetails[0]->timeline_id,
										'item_id' => $aItemDetails->id])
									->update([
										'completion_date' => $completion_date
									]);

					}
				}
				$aTermIIProgressChecklist = $termprogress->where(['item_id' => $aItemDetails->id,'timeline_id' => $aTimeLineDetails[0]->timeline_id,'checklist_type' => 2])->get();
				if(count($aTermIIProgressChecklist) > 0){
					foreach($aTermIIProgressChecklist as $checklist){
						switch($checklist->sequence){
							case 1:
								$completion_date = $request->term2meet1 ? Carbon::parse(
											$request->term2meet1)->format('Y-m-d') : NULL;
								break;
							case 2:
								$completion_date = $request->term2meet2 ? Carbon::parse(
											$request->term2meet2)->format('Y-m-d') : NULL;
								break;
							case 3:
								$completion_date = $request->term2meet3 ? Carbon::parse(
											$request->term2meet3)->format('Y-m-d') : NULL;
								break;
							case 4:
								$completion_date = $request->term2chapter1 ? Carbon::parse(
											$request->term2chapter1)->format('Y-m-d') : NULL;
								break;
							case 5:
								$completion_date = $request->term2meet4 ? Carbon::parse(
											$request->term2meet4)->format('Y-m-d') : NULL;
								break;
							case 6:
								$completion_date = $request->term2meet5 ? Carbon::parse(
											$request->term2meet5)->format('Y-m-d') : NULL;
								break;
							Case 7:
								$completion_date = $request->term2chapter2 ? Carbon::parse(
											$request->term2chapter2)->format('Y-m-d') : NULL;
								break;
							Case 8:
								$completion_date = $request->term2presentation ? Carbon::parse(
											$request->term2presentation)->format('Y-m-d') : NULL;
								break;
						}
						$termprogress->where(['document_type' => $checklist->document_type,
										'checklist_type' => 1,
										'sequence' => $checklist->sequence,
										'timeline_id' => $aTimeLineDetails[0]->timeline_id,
										'item_id' => $aItemDetails->id])
									->update([
										'completion_date' => $completion_date
									]);

					}
				}
			}
			

			return redirect()->route('mythesis.detail',$id)->withStatus($vMessage);
			
		}
		if((int) $request->approve_update_comments == 1) {								
			if((int)$vStatus == 1) {

				$item->where(['id' => $aItemDetails->id])->update(['request_approval_flag' => 0]);

				$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['supervisor_acceptence_status' => $vStatus,'supervisor' => Auth::user()->id]);
				$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,		
													'action_type' => 5,
													'message_type' => $vPrivateMsg,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'description' => $vDescription,
													'created_date' =>now()
												]);
				//#update message view info
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);

				$vTerm1FileTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,	
													'action_type' => 0,
													'message_type' => 1,
													'term_flag' => 1,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'description' => NULL,
													'created_date' =>now()
												]);	
				$aTermIFileArray = array(1 => "minutes1", 2 => "minutes2" , 3 => "minutes3", 4 => "chapter1report", 5 => "minutes4", 6 => "minutes5", 7 => "chapter2report", 8 => "presentationfile");			
				$aTimelineInfo = $progresstimeline->Active()->where(['item_id' => $aItemDetails->id])->get();
				if(count($aTimelineInfo) > 0) {
					$vTimelineIndex = 1;
					foreach ($aTermIFileArray as $key => $value) {
						switch($vTimelineIndex){
							case 1:
								$completion_date = $aTimelineInfo[0]->t1_meeting_minutes1;
								break;
							case 2:
								$completion_date = $aTimelineInfo[0]->t1_meeting_minutes2;
								break;
							case 3:
								$completion_date = $aTimelineInfo[0]->t1_meeting_minutes3;
								break;
							case 4:
								$completion_date = $aTimelineInfo[0]->term1chapter1;
								break;
							case 5:
								$completion_date = $aTimelineInfo[0]->t1_meeting_minutes4;
								break;
							case 6:
								$completion_date = $aTimelineInfo[0]->t1_meeting_minutes5;
								break;
							Case 7:
								$completion_date = $aTimelineInfo[0]->term1chapter2;
								break;
							Case 8:
								$completion_date = $aTimelineInfo[0]->term1presentation;
								break;
						}
						$termprogress->insert(['item_id' => $id,
										'timeline_id' => $aTimelineInfo[0]->timeline_id,
										'completion_date' => $completion_date,
										'track_id' => $vTerm1FileTrackID,
										'document_type' => $value,
										'checklist_type' => 1,
										'sequence' => $key,
										'created_date' =>now()
									]);
						$vTimelineIndex++;
					}
				}				
				
				//Send notification email to Manager
				$aSupervisorInfo = User::find(Auth::user()->id);
				
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
					$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));					
					$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis assignment request accepted";				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>Thesis assignment request accepted successfully by '.$aSupervisorInfo->name.'.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the posted message section of the system.</p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				return redirect()->route('mythesis.detail',$id)->withStatus(__('Request accepted successfully by Supervisor.'));
			}
			else if((int)$vStatus == 2) {				
				$vMessage = "Supervisor message and files posted successfully";				
				if($vTrackID == 0) {					
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'description' => $vDescription,
													'message_type' => $vPrivateMsg,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);
					$vMessage = "Supervisor message posted successfully";
				}
				//#update message view info
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
				
				//Send notification email to Manager
				$aSupervisorInfo = User::find(Auth::user()->id);
				
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
					$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));					
					$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis message posted by ".$aSupervisorInfo->name;				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>'.$aSupervisorInfo->name.' posted message about the thesis request</strong></p><p> assignment and is waiting for your action. 
									You can able to track the progress and post messages/attach supporting documents in the posted message section of the system.</p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				if($vPrivateMsg == 1) {
					//Send notification email to Student
					$aStudentInfo = User::find($aItemDetails->requested_by);
					
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
						$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					else {
						//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
						$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					
					$mail->Subject = "ADSM Thesis Manager Notification - Thesis message posted by ".$aSupervisorInfo->name;				
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$aStudentInfo->name.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>'.$aSupervisorInfo->name.' posted message about the thesis request and is waiting for your action.</strong></p>
										<p>You can able to track the progress and post messages/attach supporting documents in the posted message section of the system.</p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;
				}
				
				return redirect()->route('mythesis.detail',$id)->withStatus(__($vMessage));
			}
			else {

				if($aItemDetails->user_role_id == 4) {
					$item->where(['id' => $id])->update([
													'assigned_to' => 0, 	
													'request_approval_flag' => 1,
													'approval_status' => 0
												]);

					$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['supervisor_acceptence_status' => 0,'supervisor' => 0, 'manager_approval_status' => 0]);

					$assignment->where(['item_id' => $id,'request_detail_id' => $aItemDetails->request_detail_id ])->update(['status' => 2]);
				}
				else {
					$item->where(['id' => $id])->update([
													'assigned_to' => 0, 
													'request_approval_flag' => 1,
													'approval_status' => 0
												]);

					$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['supervisor_acceptence_status' => $vStatus,'supervisor' => Auth::user()->id]);

					$assignment->where(['item_id' => $id,'request_detail_id' => $aItemDetails->request_detail_id ])->update(['status' => 2]);
				}
				
				$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'message_type' => $vPrivateMsg,
													'action_type' => 6,
													'description' => $vDescription,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);
												
				//Send notification email to Manager
				$aSupervisorInfo = User::find(Auth::user()->id);
				
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
					$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis assignment rejected by Supervisor";				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>The Thesis request assignment rejected by '.$aSupervisorInfo->name.' and posted message about the thesis rejection.</strong> 
									</p><p>You can able to track the progress and post messages/attach supporting documents in the posted message section of the system.</p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				return redirect()->route('item.index')->withStatus(__('Request rejected successfully by Supervisor.'));
			}
			
		}
		else {
			if($request->hasFile('approvefiles')) {
				
				if($vTrackID == 0) {					
					$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'message_type' => $vPrivateMsg,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'description' => $vDescription,
													'created_date' =>now()
												]);
				}
				else	
					$vInsertID = $vTrackID;
				
				if($vInsertID) {
					$file     = request()->file('approvefiles');					
					$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();
					$filePath = "/thesis" . $id . '/user' . Auth::user()->id . "/" . $fileName;
					$file->storeAs("/thesis" . $id . '/user' . Auth::user()->id . "/", $fileName, 'attachments_folder');
					
					$vAttachID = $attachment->insert(['user_id' => Auth::user()->id,
														'item_id' => $id,
														'track_id' => $vInsertID,
														'file_name' => $file->getClientOriginalName(),
														'file_path' => $filePath,
														'created_date' =>now()
													]);
				
					return response()->json(['file_name' => $fileName, 'path' => $filePath, 'file_extension' => $file->getClientOriginalExtension(),'approve_track_id' => $vInsertID]);
				}				
			}
		}
    }
	
	
	/**
	 * Update Thesis Progress Status Update and Approve
     *
     * @param  \App\Http\Requests\AllocationRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateThesisProgressStatus($id, AcceptRequest $request, Item $item, TermProgressChecklist $checklist, ThesisProgressTrackings $tracking, ThesisRequestDetails $requsetModel, ThesisProgressTimeline $progresstimeline)
    {
		$vDescription   = trim($request->completionmessage);
		$vStatus 		= trim($request->submmission_status);		
		$vTrackID		= trim($request->statusupdate_track_id);
		$vPrivateMsg	= trim($request->progress_private_message) ? trim($request->progress_private_message) : 1;		
		$aItemDetails 	= $item::find($id);

		if((int) $request->statsupdate_comments == 1) {

			if((int)$vStatus == 0) {

				if(Auth::user()->role_id == 4){
					$vMessage = "";
					$vFileFound = 0;
					$vUploadCount = 0;
					foreach ($_FILES as $vFileField => $aFileInfo) {						
						if(!empty(request()->file($vFileField))) {
							$vFileFound = 1;
							$vUploadCount++;
						}						
					}

					if($vFileFound == 1) {

						if($request->checklist_type == 1) {
							switch($vFileField){
								case "proposalfile":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Proposal file";
									break;
								case "presentationfile":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Presentation file";
									break;								
								case "minutes1":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Minutes 1 file";
									break;
								case "minutes2":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Minutes 2 file";
									break;
								case "minutes3":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Minutes 3 file";
									break;
								case "minutes4":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Minutes 4 file";
									break;
								case "minutes5":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Minutes 5 file";
									break;
								case "otherdocumsnts":
									$vDescription = Auth::user()->name." successfully uploaded Term - I Other Document file";
									break;	
							}
							if($vUploadCount > 1) {
								$vDescription = Auth::user()->name." successfully uploaded Term - I files";
							}
						}
						else if($request->checklist_type == 2) {
							
							switch($vFileField){							
								case "presentationfile":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Presentation file";
									break;								
								case "minutes1":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 1 file";
									break;
								case "minutes2":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 2 file";
									break;
								case "minutes3":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 3 file";
									break;
								case "otherdocumsnts":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Other Document file";
									break;									
							}
							if($vUploadCount > 1) {
								$vDescription = Auth::user()->name." successfully uploaded Term - II files";
							}
							
						}
						else {
							switch($vFileField){
								case "proposalfile":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Proposal file";
									break;
								case "presentationfile":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Presentation file";
									break;								
								case "minutes1":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 1 file";
									break;
								case "minutes2":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 2 file";
									break;
								case "minutes3":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 3 file";
									break;
								case "minutes4":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 4 file";
									break;
								case "minutes5":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Minutes 5 file";
									break;
								case "otherdocumsnts":
									$vDescription = Auth::user()->name." successfully uploaded Term - II Other Document file";
									break;										
							}
							if($vUploadCount > 1) {
								$vDescription = Auth::user()->name." successfully uploaded Term - II files";
							}
						}

						$vStudent_Upload_Status = trim($request->student_upload_status);
						$aGetTrackReq = $checklist->where(['item_id' => $id,'checklist_type' => $request->checklist_type])->get(); 
						if(count($aGetTrackReq) > 0) {
							$vTrackID = $aGetTrackReq[0]->track_id;												
						}
						else {
							$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
															'item_id' => $id,	
															'term_flag' => $request->checklist_type,
															'message_type' => $vPrivateMsg,
															'request_detail_id' => $aItemDetails->request_detail_id,	
															'description' => $vDescription,
															'created_date' =>now()
														]);
						}
						
						if($request->checklist_type == 1) {
							$vMessage = "Term I - Files saved successfully.";	
							$this->uploadProgressFiles($request);						
						}
						else if($request->checklist_type == 2) {
							$vMessage = "Term II - Files saved successfully.";	
							$this->uploadProgressFiles($request);
						}
						else if($request->checklist_type == 3) {
							$vMessage = "Term II - Files saved successfully.";	
							$this->uploadProgressFiles($request);
						}
						
						//$checklist->where(['item_id'=>$id,'checklist_type' => $request->checklist_type, ['student_upload_status','!=',2]])->update(
						//								['student_upload_status' => $vStudent_Upload_Status]);	

						//#insert message view details
						$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
						app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);

						//Send Message post notification email to Manager and Supervisor
						$aSupervisorInfo = User::find($aItemDetails->assigned_to);
						
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
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {
							//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						
						$mail->Subject = "ADSM Thesis Manager Notification - ".$vDescription;				
						$mail->Body = '<!DOCTYPE html>
										<html>
										<head>
											<title>ADSM - Thesis Manager</title>
										</head>
										<body>
											<p>Dear '.$aSupervisorInfo->name.',</p>	
											<p>Thesis Title:'.$aItemDetails->name.' </p>											
											<p>Kindly note, <strong>'.$vDescription.'</strong>.</p>
											<p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
											<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
											<p>Thank you</p>
											<p>&nbsp;</p>
											<p>'.config('mail.mail_bottom').'</p>
										</body>
										</html>';
						$mail_result = $mail->Send();				
						$mail = NULL;


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
							$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {
							//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						
						$mail->Subject = "ADSM Thesis Manager Notification - ".$vDescription;				
						$mail->Body = '<!DOCTYPE html>
										<html>
										<head>
											<title>ADSM - Thesis Manager</title>
										</head>
										<body>
											<p>Dear '.config('mail.manager.name').',</p>	
											<p>Thesis Title:'.$aItemDetails->name.' </p>											
											<p>Kindly note, <strong>'.$vDescription.'</strong>.</p>
											<p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
											<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
											<p>Thank you</p>
											<p>&nbsp;</p>
											<p>'.config('mail.mail_bottom').'</p>
										</body>
										</html>';
						$mail_result = $mail->Send();				
						$mail = NULL;

					}

					return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));

				}
				else {
					$aGetTrackReq = $checklist->where(['item_id' => $id,'checklist_type' => $request->checklist_type])->get(); 
					
					if(count($aGetTrackReq) > 0) {
						$vTrackID = $aGetTrackReq[0]->track_id;												
					}
					else {
						$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $id,	
														'term_flag' => $request->checklist_type,
														'message_type' => $vPrivateMsg,
														'request_detail_id' => $aItemDetails->request_detail_id,	
														'description' => $vDescription,
														'created_date' =>now()
													]);
					}
					
					if($request->checklist_type == 1) {
						$vMessage = "Term I - Files saved successfully.";	
						$this->uploadProgressFiles($request);						
					}
					else if($request->checklist_type == 2) {
						$vMessage = "Term II - Files saved successfully.";	
						$this->uploadProgressFiles($request);
					}
					else if($request->checklist_type == 3) {
						$vMessage = "Term II - Files saved successfully.";	
						$this->uploadProgressFiles($request);
					}
					
					$checklist->where(['item_id'=>$id,'checklist_type' => $request->checklist_type])->update(
														['upload_file_status' => $vStatus]);

					if(!empty($request->action)	&& Auth::user()->role_id == 2)	
						return redirect()->route('mythesis.detail',$id."&tab=4&action=ac")->withStatus(__($vMessage));	
					else
						return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
				}
			}
			else if((int)$vStatus == 1) {				
				//###################################################### Supervisor Completion and Notification #####################################################//
				if(Auth::user()->role_id == 3) {
					if($request->checklist_type == 1) {
						$vMessage = "Term I - Completion status successfully updated";						
						$vActionType = 6;
					}
					else if($request->checklist_type == 2) {
						$vMessage = "Term II - Completion status successfully updated";						
						$vActionType = 8;
					}
					else if($request->checklist_type == 3) {
						$vMessage = "Term II - Completion status successfully updated";						
						$vActionType = 10;
					}				
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'description' => $vDescription,
													'action_type' => $vActionType, 
													'term_flag' => $request->checklist_type, 
													'message_type' => $vPrivateMsg,
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);		
					$this->uploadProgressFiles($request);
					
					$checklist->where(['item_id'=>$id,'checklist_type' => $request->checklist_type])->update(
														['upload_file_status' => $vStatus,'student_upload_status' => 2]);
					$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['completion_by_supervisor' => $request->checklist_type]);
					
					//#insert message view details
					$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
					app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
					
					//Send Term - I progress completion notification email to Manager
					$aRequestDetailsInfo = ThesisRequestDetails::find($aItemDetails->request_detail_id);					
					$aManagerInfo = User::find($aRequestDetailsInfo->manager);
					
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
						$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
						$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					else {
						//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
						$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					
					$mail->Subject = "ADSM Thesis Manager Notification - ".$vMessage." by ".Auth::user()->name;				
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$aManagerInfo->name.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>'.$vMessage.' by '.Auth::user()->name.' in the system.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>	
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;					
						
					return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));								
				} //----
				else {   //################### Manager Completion aand Approval Notification ########################################//

					//========================= Manager Completion Part ====================================//
					if(Auth::user()->role_id == 2 && $request->action == 'ac') {						
						if($request->checklist_type == 1) {
							$vMessage = "Term I - Completion status successfully updated";						
							$vActionType = 6;
						}
						else if($request->checklist_type == 2) {
							$vMessage = "Term II - Completion status successfully updated";						
							$vActionType = 8;
						}
						else if($request->checklist_type == 3) {
							$vMessage = "Term II - Completion status successfully updated";						
							$vActionType = 10;
						}				
						$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $id,
														'description' => $vDescription,
														'action_type' => $vActionType, 
														'term_flag' => $request->checklist_type, 
														'message_type' => $vPrivateMsg,
														'request_detail_id' => $aItemDetails->request_detail_id,	
														'created_date' =>now()
													]);		
						$this->uploadProgressFiles($request);
						
						$checklist->where(['item_id'=>$id,'checklist_type' => $request->checklist_type])->update(
															['upload_file_status' => $vStatus,'student_upload_status' => 2]);
						$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
														['completion_by_supervisor' => $request->checklist_type]);
						
						//#insert message view details
						$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
						app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
						
						//Send Term - I progress completion notification email to Manager
						$aRequestDetailsInfo = ThesisRequestDetails::find($aItemDetails->request_detail_id);					
						$aManagerInfo = User::find($aRequestDetailsInfo->manager);
						
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
							$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {
							//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						
						$mail->Subject = "ADSM Thesis Manager Notification - ".$vMessage." by ".Auth::user()->name;				
						$mail->Body = '<!DOCTYPE html>
										<html>
										<head>
											<title>ADSM - Thesis Manager</title>
										</head>
										<body>
											<p>Dear '.$aManagerInfo->name.',</p>	
											<p>Thesis Title:'.$aItemDetails->name.' </p>
											<p>Message:'.$vDescription.' </p>
											<p>Kindly note, <strong>'.$vMessage.' by '.Auth::user()->name.' in the system.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>	
											<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
											<p>Thank you</p>
											<p>&nbsp;</p>
											<p>'.config('mail.mail_bottom').'</p>
										</body>
										</html>';
						$mail_result = $mail->Send();				
						$mail = NULL;					
							
						return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
					} //========================= End of Manager Completion Part====================================//
					else {
						//========================= Manager Approval Part ====================================//
						if($request->checklist_type == 1) {
							$vMessage = "Term I - Completion status successfully approved";							
							$vActionType = 7;
						}
						else if($request->checklist_type == 2) {
							$vMessage = "Term II - Completion status successfully approved";						
							$vActionType = 9;
						}
						else if($request->checklist_type == 3) {
							$vMessage = "Term II - Completion status successfully approved";						
							$vActionType = 11;
						}	
														
						$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $id,
														'description' => $vDescription,
														'action_type' => $vActionType, 
														'message_type' => $vPrivateMsg,
														'term_flag' => $request->checklist_type, 
														'request_detail_id' => $aItemDetails->request_detail_id,	
														'created_date' =>now()
													]);	
				
						$this->uploadProgressFiles($request);
						
						$checklist->where(['item_id'=>$id,
											'checklist_type' => $request->checklist_type])
								  ->update(['approval_status' => $vStatus,'upload_file_status' => $vStatus]);

						if($request->checklist_type == 3) {
							$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
														['external_review_status' => $request->external_review,'defence_status' => $request->defence_status,'review_flag' => 0,'completion_by_manager' => $request->checklist_type,'completion_by_manager' => $request->checklist_type,'progress_completion' => $request->checklist_type]);
						}
						else {
							$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
														['completion_by_manager' => $request->checklist_type,'completion_by_manager' => $request->checklist_type,'progress_completion' => $request->checklist_type]);
						}
						
						//#insert message view details
						$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
						app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);

						if($request->checklist_type == 1) {
							$vTerm1FileTrackID = $tracking->insertGetId([
																'user_id' => Auth::user()->id,
																'item_id' => $id,
																'action_type' => 0,
																'message_type' => 1,
																'term_flag' => 2,
																'request_detail_id' => $aItemDetails->request_detail_id,	
																'description' => NULL,
																'created_date' =>now()
															]);

							$aTermIFileArray = array(1 => "minutes1", 2 => "minutes2" , 3 => "minutes3", 4 => "chapter1report", 5 => "minutes4", 6 => "minutes5", 7 => "chapter2report", 8 => "presentationfile");	

							$aTimelineInfo = $progresstimeline->Active()->where(['item_id' => $aItemDetails->id])->get();
							if(count($aTimelineInfo) > 0) {
								$vTimelineIndex = 1;
								foreach ($aTermIFileArray as $key => $value) {
									switch($vTimelineIndex){
										case 1:
											$completion_date = $aTimelineInfo[0]->t2_meeting_minutes1;
											break;
										case 2:
											$completion_date = $aTimelineInfo[0]->t2_meeting_minutes2;
											break;
										case 3:
											$completion_date = $aTimelineInfo[0]->t2_meeting_minutes3;
											break;
										case 4:
											$completion_date = $aTimelineInfo[0]->term2chapter1;
											break;
										case 5:
											$completion_date = $aTimelineInfo[0]->t2_meeting_minutes4;
											break;
										case 6:
											$completion_date = $aTimelineInfo[0]->t2_meeting_minutes5;
											break;
										Case 7:
											$completion_date = $aTimelineInfo[0]->term2chapter2;
											break;
										Case 8:
											$completion_date = $aTimelineInfo[0]->term2presentation;
											break;
									}
									$checklist->insert(['item_id' => $id,
												'timeline_id' => $aTimelineInfo[0]->timeline_id,
												'completion_date' => $completion_date,
												'track_id' => $vTerm1FileTrackID,
												'document_type' => $value,
												'checklist_type' => 2,
												'sequence' => $key,
												'created_date' =>now()
												]);
									$vTimelineIndex++;
								}
							}
						}
						else if($request->checklist_type == 2) {
							$requsetModel->where(['id'=>$aItemDetails->request_detail_id])
											->update(['review_flag' => 0,
													 'completion_by_manager' => 3,
													 'completion_by_supervisor' => 3,
													 'completion_by_panel' => 3,
													 'progress_completion' => 3]);					
						}
					
													
						//Send Message post notification email to Manager and Supervisor
						$aSupervisorInfo = User::find($aItemDetails->assigned_to);
						
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
							$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						else {
							//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
							$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
						}
						
						$mail->Subject = "ADSM Thesis Manager Notification - ".$vMessage." by ".Auth::user()->name;				
						$mail->Body = '<!DOCTYPE html>
										<html>
										<head>
											<title>ADSM - Thesis Manager</title>
										</head>
										<body>
											<p>Dear '.$aSupervisorInfo->name.',</p>	
											<p>Thesis Title:'.$aItemDetails->name.' </p>
											<p>Message:'.$vDescription.' </p>
											<p>Kindly note, <strong>your '.$vMessage.' by '.Auth::user()->name.'.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
											<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
											<p>Thank you</p>
											<p>&nbsp;</p>
											<p>'.config('mail.mail_bottom').'</p>
										</body>
										</html>';
						$mail_result = $mail->Send();				
						$mail = NULL;

						if($vPrivateMsg == 1) {
						
							//Send Message post notification email to student
							$aStudentInfo = User::find($aItemDetails->requested_by);
							
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
								$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
							}
							else {
								//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
								$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
							}
							
							$mail->Subject = "ADSM Thesis Manager Notification - ".$vMessage." by ".Auth::user()->name;				
							$mail->Body = '<!DOCTYPE html>
											<html>
											<head>
												<title>ADSM - Thesis Manager</title>
											</head>
											<body>
												<p>Dear '.$aStudentInfo->name.',</p>	
												<p>Thesis Title:'.$aItemDetails->name.' </p>
												<p>Message:'.$vDescription.' </p>
												<p>Kindly note, <strong>your '.$vMessage.' by '.Auth::user()->name.' and '.$aSupervisorInfo->name.'.</strong></p><p>You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
												<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
												<p>Thank you</p>
												<p>&nbsp;</p>
												<p>'.config('mail.mail_bottom').'</p>
											</body>
											</html>';
							$mail_result = $mail->Send();				
							$mail = NULL;
						}													
						return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
						//========================= Manager Approval Part End ====================================//
					}					
				}

			}
			else if((int)$vStatus == 2) {
				//##################################################### Manager Request for Changes and Notification #####################################################//
				if($request->checklist_type == 1) {
						$vMessage 	= "Term I - Completion changes requested successfully updated";	
						$vTitle 	= "Term I - Completion changes requested ";	
						$vActionType = 14;						
					}
					else if($request->checklist_type == 2) {
						$vMessage = "Term II - Completion changes request successfully updated";
						$vTitle 	= "Term II - Completion changes requested ";
						$vActionType = 15;						
					}
					else if($request->checklist_type == 3) {
						$vMessage = "Term II - Completion changes request successfully updated";	
						$vTitle 	= "Term II - Completion changes requested ";
						$vActionType = 16;						
					}	
													
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'description' => $vDescription,
													'action_type' => $vActionType, 
													'message_type' => $vPrivateMsg,
													'term_flag' => $request->checklist_type, 
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);
					$this->uploadProgressFiles($request);
					
					$checklist->where(['item_id'=>$id,'checklist_type' => $request->checklist_type])->update(
														['approval_status' => $vStatus,'upload_file_status' => 0]);	
					$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
													['completion_by_manager' => ($request->checklist_type-1)]);
													
					//#insert message view details
					$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
					app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
					
					//Send Message post notification email to Manager and Supervisor
					$aSupervisorInfo = User::find($aItemDetails->assigned_to);
					
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
						$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					else {
						//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
						$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					
					$mail->Subject = "ADSM Thesis Manager Notification - ".$vTitle." by ".Auth::user()->name;				
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$aSupervisorInfo->name.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>your '.$vMessage.' by '.Auth::user()->name.'.</strong> </p><p>You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;
													
					return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
			}
			else if((int)$vStatus == 3) {
				//##################################################### Manager Request for Changes and Notification #####################################################//
				
				if($request->external_review == 0) {
					$vMessage 	= "Term II completion external review status set to In Progress ";	
					$vTitle 	= "Term II External review In Progress";											
				}
				else if($request->external_review == 1) {
					$vMessage = "Term II External review successfully completed";
					$vTitle 	= "Term II - External review successfully completed ";					
				}
				else if($request->external_review == 2) {
					$vMessage = "Term II - External review status set to pending ";	
					$vTitle 	= "Term II - External review pending";					
				}	
												
				$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'item_id' => $id,
												'description' => $vDescription,											
												'message_type' => $vPrivateMsg,
												'request_detail_id' => $aItemDetails->request_detail_id,	
												'created_date' =>now()
											]);											
				
				$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
												['review_flag' => $vStatus,'external_review_status' => $request->external_review,'defence_status' => $request->defence_status]);
												
				//#insert message view details
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
				
				//Send Message post notification email to Manager and Supervisor
				$aSupervisorInfo = User::find($aItemDetails->assigned_to);
				
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
					$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - ".$vTitle." by ".Auth::user()->name;				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.$aSupervisorInfo->name.',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>your '.$vMessage.' by '.Auth::user()->name.'.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
												
				return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
			}
			else if((int)$vStatus == 4) {
				//##################################################### Manager Request for Changes and Notification #####################################################//
				
				if($request->defence_status == 0 && $request->external_review == 1) {
					$vMessage 	= "Term II External review completed and Defense status set to In Progress ";	
					$vTitle 	= "Term II Defense status In Progress";											
				}
				if($request->defence_status == 0 && $request->external_review == 0) {
					$vMessage 	= "Term II External review In Progress and Defense status set to In Progress ";	
					$vTitle 	= "Term II Defense status In Progress";											
				}
				else if($request->defence_status == 1) {
					$vMessage = "Term II Defense successfully completed";
					$vTitle 	= "Term II - Defense successfully completed ";					
				}
				else if($request->defence_status == 2 && $request->external_review == 1) {
					$vMessage = "Term II - Defense status set to pending ";	
					$vTitle 	= "Term II - External review completed and Defense status pending";					
				}	
												
				$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'item_id' => $id,
												'description' => $vDescription,											
												'message_type' => $vPrivateMsg,
												'request_detail_id' => $aItemDetails->request_detail_id,	
												'created_date' =>now()
											]);											
				
				$requsetModel->where(['id'=>$aItemDetails->request_detail_id])->update(
												['review_flag' => $vStatus,'defence_status' => $request->defence_status,'external_review_status' => $request->external_review]);
												
				//#insert message view details
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
				
				//Send Message post notification email to Manager and Supervisor
				$aSupervisorInfo = User::find($aItemDetails->assigned_to);
				
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
					$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				
				$mail->Subject = "ADSM Thesis Manager Notification - ".$vTitle." by ".Auth::user()->name;				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.$aSupervisorInfo->name.',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Message:'.$vDescription.' </p>
									<p>Kindly note, <strong>your '.$vMessage.' by '.Auth::user()->name.'.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
												
				return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
			}
			else if((int)$vStatus == 5) {
				//##################################################### Manager Request for Changes and Notification #####################################################//
				if($request->checklist_type == 1) {
						$vMessage 	= "Term I - Completion successfully approved for grading";	
						$vTitle 	= "Term I - Completion approved for grading ";	
						$vActionType = 19;						
					}
					else if($request->checklist_type == 2) {
						$vMessage = "Term II - Completion successfully approved for grading";
						$vTitle 	= "Term II - Completion changes requested ";
						$vActionType = 15;						
					}
					else if($request->checklist_type == 3) {
						$vMessage = "Term II - Completion successfully approved for grading";	
						$vTitle 	= "Term II - Completion approved for grading ";
						$vActionType = 20;						
					}	
													
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
													'item_id' => $id,
													'description' => $vDescription,
													'action_type' => $vActionType, 
													'message_type' => $vPrivateMsg,
													'term_flag' => $request->checklist_type, 
													'request_detail_id' => $aItemDetails->request_detail_id,	
													'created_date' =>now()
												]);
					$this->uploadProgressFiles($request);
					
					$checklist->where(['item_id'=>$id,'checklist_type' => $request->checklist_type])->update(['approval_status' => $vStatus]);	
													
					//#insert message view details
					$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
					app('App\Http\Controllers\ItemController')->addMessageViewInformation($request);
					
					//Send Message post notification email to Manager and Supervisor
					$aSupervisorInfo = User::find($aItemDetails->assigned_to);
					
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
						$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					else {
						//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
						$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
					}
					
					$mail->Subject = "ADSM Thesis Manager Notification - ".$vTitle." by ".Auth::user()->name;				
					$mail->Body = '<!DOCTYPE html>
									<html>
									<head>
										<title>ADSM - Thesis Manager</title>
									</head>
									<body>
										<p>Dear '.$aSupervisorInfo->name.',</p>	
										<p>Thesis Title:'.$aItemDetails->name.' </p>
										<p>Message:'.$vDescription.' </p>
										<p>Kindly note, <strong>your '.$vMessage.' by '.Auth::user()->name.'.</strong> </p><p>You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
										<p>Thank you</p>
										<p>&nbsp;</p>
										<p>'.config('mail.mail_bottom').'</p>
									</body>
									</html>';
					$mail_result = $mail->Send();				
					$mail = NULL;

					//Send Message notification email to Panel Members
					$aSupervisorInfo = User::find($aItemDetails->assigned_to);
													
					return redirect()->route('mythesis.detail',$id."&tab=4")->withStatus(__($vMessage));
			}
		}		
    }
	
	public function uploadProgressFiles($request)
	{		
		$aItemDetails 	= Item::find($request->id);
		$vPrivateMsg	= trim($request->progress_private_message) ? trim($request->progress_private_message) : 1;		
		$aGetTrackReq = TermProgressChecklist::where(['item_id' => $request->id, 'checklist_type' => $request->checklist_type])->get(); 
		
		if(count($aGetTrackReq) > 0) {
			$vInsertID = $aGetTrackReq[0]->track_id;									
		}
		else {
			$vInsertID = ThesisProgressTrackings::insertGetId(['user_id' => Auth::user()->id,
											'item_id' => $request->id,	
											'term_flag' => $request->checklist_type,
											'message_type' => $vPrivateMsg,
											'request_detail_id' => $aItemDetails->request_detail_id,	
											'description' => trim($request->completionmessage),
											'created_date' =>now()
										]);
		}
		
		foreach ($_FILES as $vFileField => $aFileInfo) {
			
			$vDisplaySeq = 0;
			if($request->checklist_type == 1) {
				switch($vFileField){
					case "proposalfile":
						$vDisplaySeq = 4;
						break;
					case "presentationfile":
						$vDisplaySeq = 5;
						break;
					case "chapter1report":
						$vDisplaySeq = 8;
						break;
					case "chapter2report":
						$vDisplaySeq = 9;
						break;
					case "proposalrubric":
						$vDisplaySeq = 3;
						break;
					case "presentationrubric":
						$vDisplaySeq = 8;
						break;
					case "finalreportdraft1":
						$vDisplaySeq = 9;
						break;
					case "finalreportdraft1rubric":
						$vDisplaySeq = 10;
						break;
				}
			}
			else {
				switch($vFileField){
					case "proposalfile":
						$vDisplaySeq = 4;
						break;
					case "presentationfile":
						$vDisplaySeq = 5;
						break;
					case "chapter1report":
						$vDisplaySeq = 8;
						break;
					case "chapter2report":
						$vDisplaySeq = 9;
						break;
					case "proposalrubric":
						$vDisplaySeq = 3;
						break;					
					case "finalreportdraft1":
						$vDisplaySeq = 8;
						break;	
					case "finalreportdraft1rubric":
						$vDisplaySeq = 9;
						break;
					case "presentationrubric":
						$vDisplaySeq = 10;
						break;
					case "finalreportrubric":
						$vDisplaySeq = 11;
						break;
				}
			}
			if($request->recadd == 1){
				if($vFileField == 'otherdocumsnts') {
					if(!empty(request()->file($vFileField))) {
						foreach (request()->file($vFileField) as $otherdoc) {
							if(!empty($otherdoc)) {							
								$fileName = rand(100, 99999999) ."_". $otherdoc->getClientOriginalName();					
								$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
								$otherdoc->storeAs("/thesis" .$request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
								
								$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
																	'item_id' => $request->id,
																	'track_id' => $vInsertID,
																	'document_type' => $vFileField,
																	'file_name' => $otherdoc->getClientOriginalName(),
																	'document_file_path' => $filePath,
																	'checklist_type' => $request->checklist_type,
																	'upload_file_status' => 0,
																	'created_date' =>now(),
																	'sequence' => $vDisplaySeq
																]);
							}
						}	
					}
				}
				else {
					if($request->hasFile($vFileField)) {				
						$file     = request()->file($vFileField);
						$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();					
						$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
						$file->storeAs("/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
						
						$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
															'item_id' => $request->id,
															'track_id' => $vInsertID,
															'document_type' => $vFileField,
															'file_name' => $file->getClientOriginalName(),
															'document_file_path' => $filePath,
															'checklist_type' => $request->checklist_type,
															'upload_file_status' => 0,
															'created_date' =>now(),
															'sequence' => $vDisplaySeq
														]);
					
					}
					else {
						$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
															'item_id' => $request->id,
															'track_id' => $vInsertID,
															'document_type' => $vFileField,
															'checklist_type' => $request->checklist_type,
															'upload_file_status' => 0,
															'created_date' =>now(),
															'sequence' => $vDisplaySeq
														]);
					}
				}
			}
			else {
				if($vFileField == 'otherdocumsnts') {
					if(!empty(request()->file($vFileField))) {
						foreach (request()->file($vFileField) as $otherdoc) {
							if(!empty($otherdoc)) {							
								$fileName = rand(100, 99999999) ."_". $otherdoc->getClientOriginalName();					
								$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
								$otherdoc->storeAs("/thesis" .$request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
								
								$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
																	'item_id' => $request->id,
																	'track_id' => $vInsertID,
																	'document_type' => $vFileField,
																	'file_name' => $otherdoc->getClientOriginalName(),
																	'document_file_path' => $filePath,
																	'checklist_type' => $request->checklist_type,
																	'upload_file_status' => 0,
																	'created_date' =>now(),
																	'sequence' => $vDisplaySeq
																]);
							}
						}
					}
					
				}
				else {
					if($request->manager_files == 1){
						if($vFileField == "presentationrubric" || $vFileField == "finalreportrubric") {
							if($request->hasFile($vFileField)) {				
								$file     = request()->file($vFileField);
								$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();					
								$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
								$file->storeAs("/thesis" .$request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
								
								$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
																	'item_id' => $request->id,
																	'track_id' => $vInsertID,
																	'document_type' => $vFileField,
																	'file_name' => $file->getClientOriginalName(),
																	'document_file_path' => $filePath,
																	'checklist_type' => $request->checklist_type,
																	'upload_file_status' => 0,
																	'created_date' =>now(),
																	'sequence' => $vDisplaySeq
																]);
							
							}
						}
						else if($vFileField == "finalreportdraft1") {
							if($request->hasFile($vFileField)) {				
								$file     = request()->file($vFileField);
								$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();					
								$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
								$file->storeAs("/thesis" .$request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
								
								$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
																	'item_id' => $request->id,
																	'track_id' => $vInsertID,
																	'document_type' => $vFileField,
																	'file_name' => $file->getClientOriginalName(),
																	'document_file_path' => $filePath,
																	'checklist_type' => $request->checklist_type,
																	'upload_file_status' => 0,
																	'created_date' =>now(),
																	'sequence' => $vDisplaySeq
																]);
							
							}
						}
						else if($vFileField == "finalreportdraft1rubric") {
							if($request->hasFile($vFileField)) {				
								$file     = request()->file($vFileField);
								$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();					
								$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
								$file->storeAs("/thesis" .$request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
								
								$vAttachID = TermProgressChecklist::insertGetId(['user_id' => Auth::user()->id,
																	'item_id' => $request->id,
																	'track_id' => $vInsertID,
																	'document_type' => $vFileField,
																	'file_name' => $file->getClientOriginalName(),
																	'document_file_path' => $filePath,
																	'checklist_type' => $request->checklist_type,
																	'upload_file_status' => 0,
																	'created_date' =>now(),
																	'sequence' => $vDisplaySeq
																]);
							
							}
						}
						else {
							if($request->hasFile($vFileField)) {
								$file     = request()->file($vFileField);
								$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();					
								$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
								$file->storeAs("/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
								
								TermProgressChecklist::where(['item_id'=>$request->id,'document_type' => $vFileField,'checklist_type' => $request->checklist_type,'track_id' => $vInsertID])->update(['file_name' => $file->getClientOriginalName(),'user_id' => Auth::user()->id,
																	'document_file_path' => $filePath]);
							}
						}
					}
					else {	

						if($request->hasFile($vFileField)) {
								
							$file     = request()->file($vFileField);
							$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();					
							$filePath = "/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/" . $fileName;
							$file->storeAs("/thesis" . $request->id . '/user' . Auth::user()->id . "/term/".$request->checklist_type."/", $fileName, 'attachments_folder');
							
							TermProgressChecklist::where(['item_id'=>$request->id,'document_type' => $vFileField,'checklist_type' => $request->checklist_type,'track_id' => $vInsertID])->update(['file_name' => $file->getClientOriginalName(),'user_id' => Auth::user()->id,'document_file_path' => $filePath]);
						}					
					}	
				}
			}
		}	
		return true;
	}

	/**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Itemrequest  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveTermsFileSubmission(Request $request, Item $item, TermProgressChecklist $checklist)
    {
		$vItemID = $request->item_id;
		if($vItemID > 0) {
			$checklist->where(['item_id'=>$vItemID, 'checklist_type' 
								=> $request->checklist_type,'document_type' => $request->document_type])
						->update(['student_upload_status' => 2]);

			$aItemDetails = $item::find($vItemID);	
			$vFileField = $request->document_type;
			if($request->checklist_type == 1) {
				switch($vFileField){
					case "proposalfile":
						$vDescription = Auth::user()->name." successfully approved Term - I Proposal file";
						break;
					case "presentationfile":
						$vDescription = Auth::user()->name." successfully approved Term - I Presentation file. Please work on to prepare youself for the presentation with panel members. The presentation date will be scheduled by the supervisor.";
						break;								
					case "minutes1":
						$vDescription = Auth::user()->name." successfully approved Term - I Minutes 1 file";
						break;
					case "minutes2":
						$vDescription = Auth::user()->name." successfully approved Term - I Minutes 2 file";
						break;
					case "minutes3":
						$vDescription = Auth::user()->name." successfully approved Term - I Minutes 3 file";
						break;
					case "minutes4":
						$vDescription = Auth::user()->name." successfully approved Term - I Minutes 4 file";
						break;
					case "minutes5":
						$vDescription = Auth::user()->name." successfully approved Term - I Minutes 5 file";
						break;
					case "chapter1report":
						$vDescription = Auth::user()->name." successfully approved Term - III Chapter - I report file";
						break;
					case "chapter2report":
						$vDescription = Auth::user()->name." successfully approved Term - III Chapter - II report file";
					case "otherdocumsnts":
						$vDescription = Auth::user()->name." successfully approved Term - I Other Document file";
						break;	
				}
			}
			else if($request->checklist_type == 2) {
				
				switch($vFileField){
					case "proposalfile":
						$vDescription = Auth::user()->name." successfully approved Term - II Proposal file";
						break;							
					case "presentationfile":
						$vDescription = Auth::user()->name." successfully approved Term - II Presentation file. Please work on to prepare youself for the presentation with panel members. The presentation date will be scheduled by the supervisor.";
						break;								
					case "minutes1":
						$vDescription = Auth::user()->name." successfully approved Term - II Minutes 1 file";
						break;
					case "minutes2":
						$vDescription = Auth::user()->name." successfully approved Term - II Minutes 2 file";
						break;
					case "minutes3":
						$vDescription = Auth::user()->name." successfully approved Term - II Minutes 3 file";
						break;
					case "minutes4":
						$vDescription = Auth::user()->name." successfully approved Term - II Minutes 4 file";
						break;
					case "minutes5":
						$vDescription = Auth::user()->name." successfully approved Term - II Minutes 5 file";
						break;
					case "chapter1report":
						$vDescription = Auth::user()->name." successfully approved Term - III Chapter - I report file";
						break;
					case "chapter2report":
						$vDescription = Auth::user()->name." successfully approved Term - III Chapter - II report file";
					case "otherdocumsnts":
						$vDescription = Auth::user()->name." successfully approved Term - II Other Document file";
						break;									
				}
				
			}
			else {
				switch($vFileField){
					case "proposalfile":
						$vDescription = Auth::user()->name." successfully approved Term - III Proposal file";
						break;
					case "presentationfile":
						$vDescription = Auth::user()->name." successfully approved Term - III Presentation file";
						break;								
					case "minutes1":
						$vDescription = Auth::user()->name." successfully approved Term - III Minutes 1 file";
						break;
					case "minutes2":
						$vDescription = Auth::user()->name." successfully approved Term - III Minutes 2 file";
						break;
					case "minutes3":
						$vDescription = Auth::user()->name." successfully approved Term - III Minutes 3 file";
						break;
					case "minutes4":
						$vDescription = Auth::user()->name." successfully approved Term - III Minutes 4 file";
						break;
					case "minutes5":
						$vDescription = Auth::user()->name." successfully approved Term - III Minutes 5 file";
						break;
					case "chapter1report":
						$vDescription = Auth::user()->name." successfully approved Term - III Chapter - I report file";
						break;
					case "chapter2report":
						$vDescription = Auth::user()->name." successfully approved Term - III Chapter - II report file";
						break;
					case "otherdocumsnts":
						$vDescription = Auth::user()->name." successfully approved Term - III Other Document file";
						break;										
				}
			}

			//Send Message post notification email to Manager and Supervisor
			$aStudentInfo = User::find($aItemDetails->requested_by);
			
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
				$mail->addBCC(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			else {
				//$mail->AddAddress(config('mail.manager.email'), config('mail.manager.name'));
				$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
			}
			
			$mail->Subject = "ADSM Thesis Manager Notification - ".$vDescription;				
			$mail->Body = '<!DOCTYPE html>
							<html>
							<head>
								<title>ADSM - Thesis Manager</title>
							</head>
							<body>
								<p>Dear '.$aStudentInfo->name.',</p>	
								<p>Thesis Title:'.$aItemDetails->name.' </p>											
								<p>Kindly note, <strong>'.$vDescription.'</strong>.</p>
								<p> You can able to track the progress and post messages/attach supporting documents in the post message section of the system.</p>
								<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>									
								<p>Thank you</p>
								<p>&nbsp;</p>
								<p>'.config('mail.mail_bottom').'</p>
							</body>
							</html>';
			$mail_result = $mail->Send();				
			$mail = NULL;

			return response()->json(['item_id' => $request->item_id, 'status' => 1, 'message' => 'File Submission Approved Successfully']);	
		} 
		else {
			return response()->json(['item_id' => $request->item_id, 'status' => 0, 'message' => 'File Approval Failed']);	
		}		
    }
}