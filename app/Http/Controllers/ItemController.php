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
//use DB;
use App\Tag;
use App\Term;
use App\Program;
use App\Item;
use App\User;
use App\GroupMember;
use App\PanelMembers;
use App\ItemAssignment;
use App\ThesisAttachments;
use App\ThesisProgressTrackings;
use App\ThesisRequestDetails;
use App\TermProgressChecklist;
use App\MessageViewesTracking;
use App\Category;
use Carbon\Carbon;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\AllocationRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\AcceptRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Adldap\Laravel\Facades\Adldap;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Item::class);
    }

    /**
     * Display a listing of the items
     *
     * @param \App\Item  $model
     * @return \Illuminate\View\View
     */
    public function index(Item $model)
    {
    	$aStudentItem = array();	
    	$aSupervisors = array();		
    	$aCohorts = array();
		if(Auth::user()->role_id == 3) {			
			$items = $model->Status()->with(['tags', 'category','term','program'])->get();
			$reqDetails = $model->Status()->select('thesis_request_details.*')
								->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->get();
			$aSupervisors = $model->Status()->select('users.*')
								->join('users','users.id','=','items.assigned_to')->distinct()->get();

			$aCohorts = $model->Status()->select('terms.*')
								->join('terms','terms.id','=','items.term_id')->distinct()->get();

			$aProgams = $model->Status()->select('programs.*')
								->join('programs','programs.id','=','items.program_id')->distinct()->get();	

			$aAcademic_year = $model->Status()->select('terms.id','terms.academic_year')
								->join('terms','terms.id','=','items.term_id')->distinct()->get();
		}
		else if(Auth::user()->role_id == 4) {			
			$aTermItems = $model->where(function($query)
										{
											$query->where([['items.requested_by','=',0],['items.user_role_id', '<', 4],['items.program_id','=', Auth::user()->program_id]])
											->orWhere('items.requested_by','=',Auth::user()->id);
										})->where(['items.program_id' => Auth::user()->program_id,'items.status' => 1])
										->with(['tags', 'category','term'])->get();				
			$aStudentItem = $model->where(function($query)
															{
																$query->where([['items.requested_by','=',0],['items.created_by', '=', Auth::user()->id]]);
															})
										->where(['items.program_id' => Auth::user()->program_id,'items.status' => 1])->with(['tags', 'category','term'])->get();										
			$aMergeArray = array();
			if(count($aTermItems) > 0 || count($aStudentItem) > 0) {
				$aMergeArray = $aTermItems->merge($aStudentItem);
			}			
			
			if(count($aMergeArray) > 0)
				$items = $aMergeArray;//$countries->unique();	
			else
				$items = array();
			

			
			$reqDetails = $model->Status()->select('thesis_request_details.*')
								->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->get();
			/*$aStudentItem = $model->Status()->where(['items.created_by' => Auth::user()->id,'items.user_role_id' => 4])->orWhere(['items.requested_by' => Auth::user()->id])->get();*/

			$aStudentItem = $model->where(['items.created_by' => Auth::user()->id,'items.user_role_id' => 4,'items.status' => 1])->orWhere([['items.created_by','!=', Auth::user()->id],['items.requested_by','=',Auth::user()->id]])->get();

			$aCohorts = $model->Status()->select('terms.*')
								->join('terms','terms.id','=','items.term_id')->distinct()->get();
			
			$aProgams = $model->Status()->select('programs.*')
								->join('programs','programs.id','=','items.program_id')->distinct()->get();	

			$aAcademic_year = $model->Status()->select('terms.id','terms.academic_year')
								->join('terms','terms.id','=','items.term_id')->distinct()->get();
		}
		else {			
			$items = $model->Status()->with(['tags', 'category','term','program'])->get();
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
		}
		if(Auth::user()->role_id == 2)
			$vResourceFile = 'items.manager.index';	
		elseif(Auth::user()->role_id == 3)	
			$vResourceFile = 'items.supervisor.index';
		elseif(Auth::user()->role_id == 4)	
			$vResourceFile = 'items.student.index';
		elseif(Auth::user()->role_id == 5)	
			$vResourceFile = 'items.panel.index';
		else
			$vResourceFile = 'items.index.index';

		return view($vResourceFile, ['items' => $items,'requestinfo' => $reqDetails ,'supervisors' => $aSupervisors,'cohorts' => $aCohorts,'programs' => $aProgams,'academic_years' => $aAcademic_year, 'studentitem' => $aStudentItem]);

		
    }

    /**
     * Display a listing of the allotted items
     *
     * @param \App\Item  $model
     * @return \Illuminate\View\View
     */
    public function allottedItemsIndex(Item $model)
    {
    	$aStudentItem = array();	
    	$aSupervisors = array();		
    	$aCohorts = array();
					
		$aTermItems = $model->where(function($query)
									{
										$query->where([['items.requested_by','>',0],['items.program_id','=', Auth::user()->program_id]]);
									})->where(['items.program_id' => Auth::user()->program_id,'items.status' => 1])
									->with(['tags', 'category','term'])->get();
		
		if(count($aTermItems) > 0)
			$items = $aTermItems;	
		else
			$items = array();

		$reqDetails = $model->Status()->select('thesis_request_details.*')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->get();	
		$aStudentItem = $model->where(['items.created_by' => Auth::user()->id,'items.user_role_id' => 4,'items.status' => 1])->orWhere([['items.created_by','!=', Auth::user()->id],['items.requested_by','=',Auth::user()->id]])->get();	
		$aCohorts = $model->Status()->select('terms.*')
							->join('terms','terms.id','=','items.term_id')->distinct()->get();
		
		$aProgams = $model->Status()->select('programs.*')
							->join('programs','programs.id','=','items.program_id')->distinct()->get();	

		$aAcademic_year = $model->Status()->select('terms.id','terms.academic_year')
							->join('terms','terms.id','=','items.term_id')->distinct()->get();
	
		$vResourceFile = 'items.student.allotted_index';
		
		return view($vResourceFile, ['items' => $items,'requestinfo' => $reqDetails ,'supervisors' => $aSupervisors,'cohorts' => $aCohorts,'programs' => $aProgams,'academic_years' => $aAcademic_year, 'studentitem' => $aStudentItem]);

		
    }

    /**
     * Display a listing of the items
     *
     * @param \App\Item  $model
     * @return \Illuminate\View\View
     */
    public function archiveThesissListing(Item $model)
    {			
		
		$items = $model->select('items.*')->with(['tags', 'category','term'])
								->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->where(['thesis_request_details.progress_completion' => 3, 'items.status' => 2])->get();
		$reqDetails = $model->select('thesis_request_details.*')
							->join('thesis_request_details','thesis_request_details.id','=','items.request_detail_id')->where(['thesis_request_details.progress_completion' => 3, 'items.status' => 2])->get();								
	
		return view('items.archive', ['items' => $items,'requestinfo' => $reqDetails]);
    }


    /**
     * Show the form for editing the specified item
     *
     * @param  \App\Item  $item
     * @param  \App\Term   $termModel
     * @param  \App\Category $categoryModel
     * @return \Illuminate\View\View
     */
    public function vewArchiveThesisDetails(Item $item, User $userModel, TermProgressChecklist $progress, Term $termModel, Category $categoryModel, GroupMember $groupmemberModel, ItemAssignment $assignmentModel,ThesisAttachments $attachment, ThesisProgressTrackings $tracking, ThesisRequestDetails $requsetModel)
    {
		$requested = 0;
		$thesisprogress['progress'] = [];		
		$thesisprogress['attachments'] = [];		
		if(Auth::user()->role_id == 4) {
			$vReqThesis = $item->Status()->where('requested_by', '=', Auth::user()->id)->get();
			$vMemberThesis = $groupmemberModel->select('group_members.*')
												->join('items',['items.id' => 'group_members.item_id','items.request_detail_id' => 'group_members.request_detail_id'])
												->where('group_members.user_id','=',Auth::user()->id)->get();			
			if(count($vReqThesis) > 0) {			
				$thesis_id = $vReqThesis[0]->id;
				$request_detail_id = $vReqThesis[0]->request_detail_id;
				$requested = 1;
				$item = Item::find($thesis_id);
			}
			else if(count($vMemberThesis) > 0){			
				$thesis_id = $vMemberThesis[0]->item_id;
				$request_detail_id = $vMemberThesis[0]->request_detail_id;
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
				$thesis_id = $item_id[0];			
				$requested = 1;				
				$item = Item::find($thesis_id);
				if(!empty($item)) {
					if($item->request_detail_id > 0) {
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
		
        return view('items.archive_detail', [
            'item' => $item->load('tags'),
			'requested' => $requested,	
			'requestdetails' => $requsetModel->select('thesis_request_details.*')
							            ->join('items','items.request_detail_id','=','thesis_request_details.id')
							            ->where(['thesis_request_details.id'=> $request_detail_id])->get(),
			'supervisors' => $userModel->Available()->where(function($query)
															{
																$query->where(['role_id' => 2,"manager_flag" => 1])
																->orWhere('role_id','=',3);
															})->get(['id', 'name']),
			'prefsupervisor' => $userModel->select('users.id','users.name','users.email')
							            ->join('item_assignments','item_assignments.user_id','=','users.id')
							            ->where(['item_assignments.item_id' => $thesis_id, 'item_assignments.request_detail_id'=> $request_detail_id])->get(),
			'groupowner' => $userModel->select('users.id','users.name','users.email')
							            ->join('items','items.requested_by','=','users.id')
							            ->where(['items.id' => $thesis_id, 'items.request_detail_id' => $request_detail_id])->get(),
			'groupmembers' => $userModel->select('users.id','users.name','users.email')
							            ->join('group_members','group_members.user_id','=','users.id')
							            ->where(['group_members.item_id' => $thesis_id, 'group_members.request_detail_id' => $request_detail_id ])->get(),
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
							            ->where([['terms_progress_checklist.item_id','=', $thesis_id],['terms_progress_checklist.checklist_type','=', 3],['terms_progress_checklist.upload_file_status','>',-1],['terms_progress_checklist.status','=',1]])->get(),
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
     * Show the form for creating a new item
     *
     * @param  \App\Tag $tagModel
     * @param  \App\Category $categoryModel
	 * @param  \App\Term $termModel
     * @return \Illuminate\View\View
     */
    public function create(Category $categoryModel,Term $termModel, Tag $tagModel, Program $programModel, User $userModel)
    {	
        return view('items.create', [
            'categories' => $categoryModel->Active()->get(['id', 'name']),
            'programs' => $programModel->Active()->get(['id', 'name','description']),
            'tags' => $tagModel->Active()->orderBy('name','ASC')->get(['name','id']),
            'terms' => $termModel->Active()->get(['id', 'name'])
        ]);
	}


	 /**
     * Show the form for creating a new item by Student
     *
     * @param  \App\Tag $tagModel
     * @param  \App\Category $categoryModel
	 * @param  \App\Term $termModel
     * @return \Illuminate\View\View
     */
    public function editStudentThesis($id,Item $item, Tag $tag, Category $categoryModel,Term $termModel,User $userModel,ThesisRequestDetails $requsetModel, Program $programModel)
    {
    	$aItemDetails = $item::find($id);    	
		$aUserRequested = $userModel
							->join('items','items.requested_by', '=' , 'users.id')
							->where('role_id',4)->get(['users.id']);
		
		$vAssignedUsers = []; 
		$vMembersId = "";
		$aStudents =  $userModel->where(['role_id' => 4,'term_id' => Auth::user()->term_id])->get(['id', 'name','student_id']);

		return view('items.student.edit_student_thesis', [
			'item' => $aItemDetails,
			'itemtags' => DB::table('item_tag')->where(['item_id' => $aItemDetails->id,'status' => 1])->get(['tag_id']),
			'requestdetails' => $requsetModel->select('thesis_request_details.*')
							            ->join('items','items.request_detail_id','=','thesis_request_details.id')
							            ->where(['thesis_request_details.id'=> $aItemDetails->request_detail_id])->get(),			
            'categories' => $categoryModel->Active()->get(['id', 'name']),
            'tags' => $tag->Active()->orderBy('name','ASC')->get(['name','id']),
            'programs' => $programModel->Active()->get(['id', 'name','description']),
            'supervisors' => $userModel->Available()->where(function($query)
															{
																$query->where(['role_id' => 2,"manager_flag" => 1])
																->orWhere('role_id','=',3);
															})->get(['id', 'name','program_availability']),
			'students' => $aStudents,
			'upload' => route('item.update-student-thesis', ''),
			'terms' => $termModel->Active()->get(['id', 'name'])
        ]);	
    }

    

     /**
     * Show the form for creating a new item by Student
     *
     * @param  \App\Tag $tagModel
     * @param  \App\Category $categoryModel
	 * @param  \App\Term $termModel
     * @return \Illuminate\View\View
     */
    public function studentThesisCreation(Category $categoryModel,Tag $tagModel, Term $termModel,Program $programModel,User $userModel)
    {
		
		$aGroupMemebers = $userModel
							->join('group_members','group_members.user_id', '=' , 'users.id')							
							->where('role_id',4)->get(['users.id']);
		$aUserRequested = $userModel
							->join('items','items.requested_by', '=' , 'users.id')
							->where('role_id',4)->get(['users.id']);
		
		$vAssignedUsers = []; 
		$vMembersId = "";
		if(count($aGroupMemebers) > 0) {
			foreach($aGroupMemebers as $group) {
				$vAssignedUsers[] = (int) $group->id;				
			}			
		}
		if(count($aUserRequested) > 0) {
			foreach($aUserRequested as $request) {
				$vAssignedUsers[] = (int) $request->id;				
			}			
		}
		
		if(!empty($vAssignedUsers)) {			
			$aStudents = $userModel->where(['role_id' => 4,'term_id' => Auth::user()->term_id])->whereNotIn('id',$vAssignedUsers)->get(['id', 'name','student_id']);
		}
		else 
			$aStudents =  $userModel->where(['role_id' => 4,'term_id' => Auth::user()->term_id])->get(['id', 'name','student_id']);

		return view('items.student.student_thesis', [
            'categories' => $categoryModel->Active()->where(['program_id' => Auth::user()->program_id])->get(['id', 'name']),
            'tags' => $tagModel->Active()->orderBy('name','ASC')->get(['id', 'name']),
            'programs' => $programModel->Active()->get(['id', 'name','description']),
            'supervisors' => $userModel->Available()->where(function($query)
															{
																$query->where(['role_id' => 2,"manager_flag" => 1])
																->orWhere('role_id','=',3);
															})->get(['id', 'name','program_availability']),
			'students' => $aStudents,
			'upload' => route('item.addstudent-thesis', ''),
			'terms' => $termModel->Active()->get(['id', 'name'])
        ]);	
    }

    /**
     * Store a newly created item in storage
     *
     * @param  \App\Http\Requests\ItemRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ItemRequest $request, Item $model)
    {
        $item = $model->create($request->merge([            
            'show_on_homepage' => 0,
            'options' => $request->options ? $request->options : null,
			'status' => $request->status ? $request->status : 0,
			'created_by' => Auth::user()->id ? (int) Auth::user()->id : 0,
			'user_role_id' => Auth::user()->role_id ? (int) Auth::user()->role_id : 0,
            'date' => $request->date ? Carbon::parse($request->date)->format('Y-m-d') : null
        ])->all());

        //Insert Keywords details
		if(count($request->tags) > 0){
			$aTags = [];
			foreach ($request->tags as $keyword ) {					
				$tag_id = DB::table('item_tag')->insertGetId(['item_id' => $item->id,'tag_id' => $keyword]);
			}							
		}
        
        return redirect()->route('item.index')->withStatus(__('Thesis successfully created.'));
    }


    /**
     * Store a newly created item in storage
     *
     * @param  \App\Http\Requests\ItemRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeStudentThesis(Request $request, Item $model, Tag $tag,GroupMember $gmember, ItemAssignment $assignment, ThesisAttachments $attachment, ThesisProgressTrackings $tracking, ThesisRequestDetails $reqdetails)
    {       
		if($request->update_request == 2){
			//Insert request details
			$vPublish = 0;
			if(Auth::user()->role_id == 2){
				$vPublish = 1;
			}
			$vItemID = $model->insertGetId(['name' => $request->name,
											'category_id' => $request->category_id,
											'user_role_id' => Auth::user()->role_id,
											'program_id' => $request->program_id,
											'description' => $request->description,
											'aim' => $request->aim,
											'objectives' => $request->objectives,
											'created_by' => Auth::user()->id,
											'status' => $vPublish,
											'created_at' =>now()
											]);			
			//Insert Keywords details
			if(count($request->tags) > 0){
				$aTags = [];
				foreach ($request->tags as $keyword ) {					
					$tag_id = DB::table('item_tag')->insertGetId(['item_id' => $vItemID,'tag_id' => $keyword]);
				}							
			}
			if(Auth::user()->role_id != 2){ 
				//Send request notification mail to Manager
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
					$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
								
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis created by ".Auth::user()->name;
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>'.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$request->name.' </p>
									<p>Kindly note, a <strong> new thesis added successfully</strong>by '.Auth::user()->name.' in the system and is waiting for your action to publish the Thesis for students selection.</p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
			}
			$vStatusMsg = "Thesis created successfully.";
			$route = "item.index";	
			return redirect()->route($route)->withStatus(__($vStatusMsg));
		}
		$vTrackID		= trim($request->track_id);
		$vDescription   = trim($request->description);
		if($request->update_request == 1){
			
			$vGroupMembers = $request->student_id ? implode(",",$request->student_id) : NULL;
			//Insert request details
			$vItemID = $model->insertGetId(['requested_by' => Auth::user()->id,
												'name' => $request->name,
												'category_id' => $request->category_id,
												'user_role_id' => Auth::user()->role_id,
												'term_id' => Auth::user()->term_id,
												'program_id' => Auth::user()->program_id,
												'description' => $request->description,
												'aim' => $request->aim,
												'objectives' => $request->objectives,
												'created_by' => Auth::user()->id,
												'status' => 1,
												'created_at' =>now()
											]);

			//Insert request details
			$vRequestID = $reqdetails->insertGetId(['requested_by' => Auth::user()->id,
													'item_id' => $vItemID,
													'manager' => env('MANAGER_ID',2),
													'supervisor' => $request->supervisor_id,
													'group_members' => $vGroupMembers,
													'created_date' =>now()
												]);
			//Insert Keywords details
			if(count($request->tags) > 0){
				$aTags = [];
				foreach ($request->tags as $keyword ) {					
					$tag_id = DB::table('item_tag')->insertGetId(['item_id' => $vItemID,'tag_id' => $keyword]);
				}							
			}
			//Update request info to items table
			$vUpdateResp = $model->where(['id'=>$vItemID])->update(['items.requested_by' => Auth::user()->id, 'request_detail_id' => $vRequestID]);
			
			if($vUpdateResp) {			
				$vStatusMsg = "Thesis created successfully.";
				$route = "mythesis.detail";	
				if(!empty($request->student_id)) {
					$aGrpmembers = [];
					foreach ($request->student_id as $student_id ) {
						$aGrpmembers[] = ['user_id' => $student_id,
										  'request_detail_id' => $vRequestID,	
										  'item_id' => $vItemID];
					}
					//Insert group members details
					$gmember->insert($aGrpmembers);
				}
				
				//Insert prefered supervisor details
				$assignment->insert(['user_id' => $request->supervisor_id,
									 'request_detail_id' => $vRequestID,
									 'item_id' => $vItemID]);
				
				if($vTrackID == 0) {					 
					//Insert message details
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $vItemID,
														'request_detail_id' => $vRequestID,
														'action_type' => 1,
														'description' => '<p>New thesis created by '.Auth::user()->name."</p>",
														'created_date' =>now()
													]);
				}
				else {
					//Update request detail id
					$tracking->where(['id'=>$vTrackID])->update(['item_id' => $vItemID,'request_detail_id' => $vRequestID,'action_type' => 1]);
					//update item id in attachments table
					$attachment->where(['track_id' => $vTrackID])->update(['item_id' => $vItemID]);
				}
				
				$request->merge(['msg_item_id' => $vItemID,'msg_track_id' => $vTrackID]);
				$this->addMessageViewInformation($request);								
				
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
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis adeed and Requested Successfully.";
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.',</p>
									<p>Thesis Title:'.$request->name.' </p>
									<p>Kindly note, your <strong>new thesis added and a request enrolled successfully </strong>in the system. You will get notified every time an action done by the Manager or Supervisor.</p><p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>								
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				//Send request notification mail to Manager
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
					$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
								
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis requested by ".Auth::user()->name;
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>'.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$request->name.' </p>									
									<p>Kindly note, a <strong>thesis added and request successfully enrolled </strong>by '.Auth::user()->name.' in the system and waiting for your action.</p> <p>You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>	
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
			}
			else {			
				$vStatusMsg = "Thesis creation failed. Please try again later";
				$route = "item.index";					
			}
			return redirect()->route($route)->withStatus(__($vStatusMsg));
		}		
		if($request->hasFile('thesisfiles')) {
			
			if($vTrackID == 0) {
				//Insert message details
				$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'description' => '<p>New thesis created by '.Auth::user()->name."</p>",
												'created_date' =>now()
											]);
			}
			else	
				$vInsertID = $vTrackID;
			
			if($vInsertID) {
				//Upload attachments to folder
				$aFile = request()->file('thesisfiles');				
				$file     = request()->file('thesisfiles');
				$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();
				$filePath = "/thesis/user" . Auth::user()->id . "/" . $fileName;
				$file->storeAs("/thesis/user" . Auth::user()->id . "/", $fileName, 'attachments_folder');
				//Insert attachment details
				$vAttachID = $attachment->insert(['user_id' => Auth::user()->id,
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
     * Store a newly created item in storage
     *
     * @param  \App\Http\Requests\ItemRequest  $request
     * @param  \App\Item  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStudentThesis(Request $request, Item $model,GroupMember $gmember, ItemAssignment $assignment, ThesisAttachments $attachment, ThesisProgressTrackings $tracking, ThesisRequestDetails $reqdetails)
    {   
    	$vTrackID		= trim($request->track_id);
		$vDescription   = trim($request->description);
		$aItemDetails	= $model::find($request->id);
		$vPublishStat	= trim($request->status) ? trim($request->status) : 0;
		if($request->update_request == 2){
			$vUpdateResp = $model->where(['id'=>$aItemDetails->id])->update(['name' => $request->name,'description' => $request->description,'aim' => $request->aim,'objectives' => $request->objectives,'updated_at' => now(),'status' => $vPublishStat]);
			

			//Insert Keywords details
			if(count($request->tags) > 0){
				$updatetag_id = DB::table('item_tag')->where(['item_id' => $aItemDetails->id])->update(['status' => 2]);				
				foreach ($request->tags as $keyword ) {					
					$tag_id = DB::table('item_tag')->insertGetId(['item_id' => $aItemDetails->id,'tag_id' => $keyword]);
				}							
			}

			if(Auth::user()->role_id != 2){ 
				//Send request notification mail to Manager
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
					$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}		
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis created by ".Auth::user()->name;
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>'.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$request->name.' </p>
									<p>Kindly note, '.Auth::user()->name.' <strong> updated thesis information successfully</strong> by  in the system and is waiting for your action to review and publish the Thesis for students selection.</p>
										<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
			}

			$vStatusMsg = "Thesis information updated successfully.";
			$route = "item.index";
			return redirect()->route($route)->withStatus(__($vStatusMsg));
		}
		
		if($request->update_request == 1){			
			//update request details
			$vRequestID = $reqdetails->where(['id' => $aItemDetails->request_detail_id,'item_id' => $aItemDetails->id])->update(['supervisor' => $request->supervisor_id]);
			//Update request info to items table
			if($aItemDetails->created_by == Auth::user()->id && $aItemDetails->user_role_id == 4){
				$vUpdateResp = $model->where(['id'=>$aItemDetails->id])->update(['name' => $request->name,'description' => $request->description,'aim' => $request->aim,'objectives' => $request->objectives,'category_id' => $request->category_id, 'updated_at' => now(),'requested_by' => Auth::user()->id,'request_approval_flag' => 0]);

				$vPreferredSup =$assignment->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $aItemDetails->id,
														'request_detail_id' => $aItemDetails->request_detail_id
													]);
			}
			else {
				$vUpdateResp = $model->where(['id'=>$aItemDetails->id])->update(['name' => $request->name,'description' => $request->description,'category_id' => $request->category_id, 'updated_at' => now()]);
			}

			//Insert Keywords details
			if(count($request->tags) > 0){
				$updatetag_id = DB::table('item_tag')->where(['item_id' => $aItemDetails->id])->update(['status' => 2]);				
				foreach ($request->tags as $keyword ) {					
					$tag_id = DB::table('item_tag')->insertGetId(['item_id' => $aItemDetails->id,'tag_id' => $keyword]);
				}							
			}
			
			
			if($vUpdateResp) {			
				$vStatusMsg = "Prefered supervisor/thesis information updated successfully.";
				$route = "mythesis.detail";	
								
				//Insert prefered supervisor details
				$assignment->where(['request_detail_id' => $aItemDetails->request_detail_id,
									 'item_id' => $aItemDetails->id])->update(['user_id' => $request->supervisor_id,
									 ]);
				
				if($vTrackID == 0) {					 
					//Insert message details
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $aItemDetails->id,
														'request_detail_id' => $aItemDetails->request_detail_id,
														'action_type' => 0,
														'description' => '<p>Prefered supervisor/thesis information updated successfully by '.Auth::user()->name."</p>",
														'created_date' =>now()
													]);
					//update item id in attachments table
					$attachment->where(['track_id' => $aItemDetails->request_detail_id])->update(['item_id' => $aItemDetails->id]);
				}
				else {
					//Update request detail id
					$tracking->where(['id'=>$vTrackID])->update(['item_id' => $aItemDetails->id,'request_detail_id' => $aItemDetails->request_detail_id,'action_type' => 4]);
					//update item id in attachments table
					$attachment->where(['track_id' => $aItemDetails->request_detail_id])->update(['item_id' => $aItemDetails->id]);
				}
				
				$request->merge(['msg_item_id' => $aItemDetails->id,'msg_track_id' => $aItemDetails->request_detail_id]);
				$this->addMessageViewInformation($request);								
				
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
				
				$mail->Subject = "ADSM Thesis Manager Notification - Prefered supervisor/thesis information updated successfully.";
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.',</p>
									<p>Thesis Title:'.$request->name.' </p>
									<p>Kindly note, your <strong> prefered supervisor/thesis information updated successfully </strong>in the system. You will get notified when an action done by the Manager or Supervisor.</p><p>You can track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				//Send request notification mail to Manager
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
					$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
								
				
				$mail->Subject = "ADSM Thesis Manager Notification - Prefered supervisor/thesis information updated by ".Auth::user()->name;
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>'.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$request->name.' </p>									
									<p>Kindly note, a <strong>prefered supervisor/thesis information updated successfully </strong>by '.Auth::user()->name.' in the system and waiting for your action.</p> <p>You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>	
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
			}
			else {			
				$vStatusMsg = "Thesis update failed. Please try again later";
				$route = "item.index";					
			}
			return redirect()->route($route)->withStatus(__($vStatusMsg));
		}
		if($request->hasFile('thesisfiles')) {
			
			if($vTrackID == 0) {
				//Insert message details
				$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'description' => '<p>New Thesis created by '.Auth::user()->name."</p>",
												'item_id' => $request->item_id,
												'created_date' =>now()
											]);
			}
			else	
				$vInsertID = $vTrackID;
			
			if($vInsertID) {
				//Upload attachments to folder
				$aFile = request()->file('thesisfiles');				
				$file     = request()->file('thesisfiles');
				$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();
				$filePath = "/thesis/user" . Auth::user()->id . "/" . $fileName;
				$file->storeAs("/thesis/user" . Auth::user()->id . "/", $fileName, 'attachments_folder');
				//Insert attachment details
				$vAttachID = $attachment->insert(['user_id' => Auth::user()->id,
													'track_id' => $vInsertID,
													'item_id' => $request->item_id,
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
    public function updateAllocationRequest($id,AllocationRequest $request, Item $item,GroupMember $gmember, ItemAssignment $assignment, ThesisAttachments $attachment, ThesisProgressTrackings $tracking, ThesisRequestDetails $reqdetails)
    {	
		$vTrackID		= trim($request->track_id);
		$vDescription   = trim($request->description);

		if($request->update_request == 1){
			
			$aItemDetails = $item::find($id);
			$vGroupMembers = $request->student_id ? implode(",",$request->student_id) : NULL;
			if($aItemDetails->user_role_id == 4) {
				$aRequestDetailsInfo = $reqdetails->where(['item_id' => $aItemDetails->id, 'requested_by' => Auth::user()->id])->get();				
				if(count($aRequestDetailsInfo) > 0){
					$vRequestID = $aRequestDetailsInfo[0]->id;
					$reqdetails->where(['id' => $aRequestDetailsInfo[0]->id])->update(['group_members' => $vGroupMembers]);
				}
				else {
					//Insert request details
					$vRequestID = $reqdetails->insertGetId(['requested_by' => Auth::user()->id,
													'item_id' => $id,
													'manager' => env('MANAGER_ID',2),
													'supervisor' => $request->supervisor_id,
													'group_members' => $vGroupMembers,
													'created_date' =>now()
													]);	
				}				
			}
			else {
				//Insert request details
				$vRequestID = $reqdetails->insertGetId(['requested_by' => Auth::user()->id,
													'item_id' => $id,
													'manager' => env('MANAGER_ID',2),
													'supervisor' => $request->supervisor_id,
													'group_members' => $vGroupMembers,
													'created_date' =>now()
													]);	
			}		
			//Update request info to items table
			$vUpdateResp = $item->where(['id'=>$id,'items.requested_by'=>0])->update(['items.requested_by' => Auth::user()->id,'items.term_id' =>Auth::user()->term_id, 'request_detail_id' => $vRequestID]);
			
			if($vUpdateResp) {			
				$vStatusMsg = "Thesis request successfully created.";
				$route = "mythesis.detail";	
				if(!empty($request->student_id)) {
					$aGrpmembers = [];
					foreach ($request->student_id as $student_id ) {
						$aGrpmembers[] = ['user_id' => $student_id,
										  'request_detail_id' => $vRequestID,	
										  'item_id' => $id];
					}
					//Insert group members details
					$gmember->insert($aGrpmembers);
				}
				
				//Insert prefered supervisor details
				$assignment->insert(['user_id' => $request->supervisor_id,
									 'request_detail_id' => $vRequestID,
									 'item_id' => $id]);
				
				if($vTrackID == 0) {					 
					//Insert message details
					$vTrackID = $tracking->insertGetId(['user_id' => Auth::user()->id,
														'item_id' => $id,
														'request_detail_id' => $vRequestID,
														'action_type' => 1,
														'description' => trim($request->description),
														'created_date' =>now()
													]);
				}
				else {
					//Update request detail id
					$tracking->where(['id'=>$vTrackID])->update(['request_detail_id' => $vRequestID,'action_type' => 1]);
				}
				
				$request->merge(['msg_item_id' => $id,'msg_track_id' => $vTrackID]);
				$this->addMessageViewInformation($request);								
				
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
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis Requested Successfully.";
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>Dear '.Auth::user()->name.',</p>
									<p>Thesis Title:'.$aItemDetails->name.' </p>
									<p>Kindly note, <strong>your thesis request successfully enrolled in the system.</strong></p><p> You will get notified every time an action done by the Manager or Supervisor. Also track the progress and post messages/attach supporting documents in the post message section of system. </p>
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
				
				//Send request notification mail to Manager
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
					$mail->addBcc(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
				else {
					$mail->AddAddress(config('mail.dev_email.email'), config('mail.dev_email.name'));	
				}
								
				
				$mail->Subject = "ADSM Thesis Manager Notification - Thesis requested by ".Auth::user()->name;
				
				$mail->Body = '<!DOCTYPE html>
								<html>
								<head>
									<title>ADSM - Thesis Manager</title>
								</head>
								<body>
									<p>'.config('mail.manager.name').',</p>	
									<p>Thesis Title:'.$aItemDetails->name.' </p>									
									<p>Kindly note,<strong> a thesis request successfully enrolled by '.Auth::user()->name.' in the system and waiting for your action.</strong></p><p> You can able to track the progress and post messages/attach supporting documents in the post message section of system. </p>	
									<p>Please click <a href="'.url('/').'">ADSM Thesis</a> to check the Thesis</p>
									<p>Thank you</p>
									<p>&nbsp;</p>
									<p>'.config('mail.mail_bottom').'</p>
								</body>
								</html>';
				$mail_result = $mail->Send();				
				$mail = NULL;
			}
			else {			
				$vStatusMsg = "Thesis request failed. Please try again later";
				$route = "item.index";					
			}
			return redirect()->route($route)->withStatus(__($vStatusMsg));
		}
		if($request->hasFile('thesisfiles')) {
			
			if($vTrackID == 0) {
				//Insert message details
				$vInsertID = $tracking->insertGetId(['user_id' => Auth::user()->id,
												'item_id' => $id,
												'description' => trim($request->description),
												'created_date' =>now()
											]);
			}
			else	
				$vInsertID = $vTrackID;
			
			if($vInsertID) {
				//Upload attachments to folder
				$aFile = request()->file('thesisfiles');				
				$file     = request()->file('thesisfiles');
				$fileName = rand(100, 99999999) ."_". $file->getClientOriginalName();
				$filePath = "/thesis" . $id . '/user' . Auth::user()->id . "/" . $fileName;
				$file->storeAs("/thesis" . $id . '/user' . Auth::user()->id . "/", $fileName, 'attachments_folder');
				//Insert attachment details
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
	
	public function approveAllocationRequest($id,Item $item, Term $termModel, Category $categoryModel, User $userModel,ThesisAttachments $attachment, ThesisProgressTrackings $tracking, ThesisRequestDetails $requsetModel) 
	{
		$item = Item::find($id);
		$requested = 0;
		$chkrequest = Item::Status()->where('requested_by', '=', Auth::user()->id)->get();

		$aMemberItem = $item->join('group_members',['group_members.item_id' => 'items.id','group_members.request_detail_id' => 'items.request_detail_id'])
			->where(['group_members.item_id' => $id,'group_members.user_id' => Auth::user()->id,'items.id' => $id])->get();
	
		if(count($chkrequest) > 0 || count($aMemberItem) >0 ) {			
			$requested = 1;
		}
		$thesis_id = $id;	
		$request_detail_id = $item->request_detail_id;
		$thesisprogress['progress'] = [];		
		$thesisprogress['attachments'] = [];	
		
		$trackinfo = $tracking->select('thesis_progress_trackings.*','users.role_id','users.name','users.email')
							->join('users','users.id','=','thesis_progress_trackings.user_id')
							->where(['thesis_progress_trackings.item_id' => $thesis_id,'thesis_progress_trackings.request_detail_id' => $request_detail_id])->orderBy('thesis_progress_trackings.id', 'asc')->get();
							
		
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
		
        return view('items.manager.approve', [
            'item' => $item->load('tags'),
			'requested' => $requested,
			'requestdetails' => $requsetModel->select('thesis_request_details.*')
							            ->join('items','items.request_detail_id','=','thesis_request_details.id')
							            ->where(['thesis_request_details.id'=> $request_detail_id])->get(),
			'supervisors' => $userModel->Available()->where(function($query)
															{
																$query->where(['role_id' => 2,"manager_flag" => 1])
																->orWhere('role_id','=',3);
															})->get(['id', 'name']),
			'groupowner' => $userModel->select('users.id','users.name','users.email')
							            ->join('items','items.requested_by','=','users.id')
							            ->where(['items.id' => $id])										
							            ->get(),
			'groupmembers' => $userModel->select('users.id','users.name','users.email')
							            ->join('group_members',['group_members.user_id' =>'users.id'])
							            ->where(['group_members.item_id' => $id,'group_members.request_detail_id' =>$request_detail_id])										
							            ->get(),
			'prefsupervisor' => $userModel->select('users.id','users.name','users.email')
							            ->join('item_assignments','item_assignments.user_id','=','users.id')
							            ->where(['item_assignments.item_id' => $id,'item_assignments.request_detail_id' =>$request_detail_id])										
							            ->get(),
			'trackinginfo' => $thesisprogress,
			'upload' => route('mythesis.comment-update', $thesis_id),
            'terms' => $termModel->Active()->get(['id', 'name']),
			'attachupload' => route('mythesis.request-approve', $id),
            'categories' => $categoryModel->Active()->get(['id', 'name'])
        ]);		
	}
	
    /**
     * Show the form for editing the specified item
     *
     * @param  \App\Item  $item
     * @param  \App\Term   $termModel
     * @param  \App\Category $categoryModel
     * @return \Illuminate\View\View
     */
    public function edit(Request $request,Item $item, Tag $tagModel, Term $termModel, Program $programModel, Category $categoryModel)
    {
        return view('items.edit', [
            'item' => $item->load('tags'),
            'terms' => $termModel->Active()->get(['id', 'name']),
            'programs' => $programModel->Active()->get(['id', 'name','description']),
            'tags' => $tagModel->Active()->orderBy('tags.name','ASC')->get(['name','id']),
            'categories' => $categoryModel->Active()->get(['id', 'name'])
        ]);
    }
	
	/**
     * Show the form for editing the specified item
     *
     * @param  \App\Item  $item
     * @param  \App\Term   $termModel
     * @param  \App\Category $categoryModel
     * @return \Illuminate\View\View
     */
    public function detail($id,Item $item, Term $termModel, Category $categoryModel, GroupMember $groupmemberModel)
    {
		$item = Item::find($id);
		$requested = 0;
		$chkrequest = Item::Status()->where('requested_by', '=', Auth::user()->id)->get();		
		if(count($chkrequest) > 0) {			
			$requested = 1;
		}
		
		return view('items.student.detail', [
            'item' => $item->load('tags'),
			'requested' => $requested,
            'terms' => $termModel->Active()->get(['id', 'name']),			
            'categories' => $categoryModel->Active()->get(['id', 'name'])
        ]);
    }

	/**
     * Show the form for editing the specified item
     *
     * @param  \App\Item  $item
     * @param  \App\Term   $termModel
     * @param  \App\Category $categoryModel
     * @return \Illuminate\View\View
     */
    public function showThesisAllocation($id,Item $item, User $userModel, Term $termModel, Category $categoryModel)
    {
		$item = Item::find($id);
		$aGroupMemebers = $userModel
							->join('group_members','group_members.user_id', '=' , 'users.id')							
							->where('role_id',4)->get(['users.id']);
		$aUserRequested = $userModel
							->join('items','items.requested_by', '=' , 'users.id')
							->where('role_id',4)->get(['users.id']);
		
		$vAssignedUsers = []; 
		$vMembersId = "";
		if(count($aGroupMemebers) > 0) {
			foreach($aGroupMemebers as $group) {
				$vAssignedUsers[] = (int) $group->id;				
			}			
		}
		if(count($aUserRequested) > 0) {
			foreach($aUserRequested as $request) {
				$vAssignedUsers[] = (int) $request->id;				
			}			
		}
		
		if(!empty($vAssignedUsers)) {			
			$aStudents = $userModel->where(['role_id' => 4,'term_id' => Auth::user()->term_id])->whereNotIn('id',$vAssignedUsers)->get(['id', 'name','student_id']);
		}
		else 
			$aStudents =  $userModel->where(['role_id' => 4,'term_id' => Auth::user()->term_id])->get(['id', 'name','student_id']);
		
        return view('items.student.allocation', [
            'item' => $item->load('tags'),
			'supervisors' => $userModel->Available()->where(function($query)
															{
																$query->where(['role_id' => 2,"manager_flag" => 1])
																->orWhere('role_id','=',3);
															})->get(['id', 'name']),
			'students' => $aStudents,
			'upload' => route('item.update-request', $id),
            'terms' => $termModel->Active()->get(['id', 'name']),
            'categories' => $categoryModel->Active()->get(['id', 'name'])
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Itemrequest  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ItemRequest $request, Item $item)
    {
		$item->update(
            $request->merge([
                'picture' => $request->photo ? $request->photo->store('pictures', 'public') : null,
                'show_on_homepage' => $request->show_on_homepage ? 1 : 0,
				'status' => $request->status ? $request->status : 0,
                'options' => $request->options ? $request->options : null,
				'modified_by' => Auth::user()->id ? (int) Auth::user()->id : 0,
                'updated_at ' => $request->date ? Carbon::parse($request->date)->format('Y-m-d') : ""
            ])->except([$request->hasFile('photo') ? '' : 'picture'])
        );
        if($request->status == 1 && !empty($request->ref))
        	$message = ' published successfully';
        else if($request->status == 2)
        	$message = ' archived successfully';
        else
        	$message = ' updated successfully';
        if(!empty($request->ref))		
        	return redirect()->route('item.archive')->withStatus(__('Thesis '.$message));
        else
        	return redirect()->route('item.index')->withStatus(__('Thesis '.$message));
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Itemrequest  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMessageViewInformation($request)
    {	
		if(!empty($request->msg_item_id) && $request->msg_item_id > 0) {
			$aMsgRequestDeails = ThesisRequestDetails::where('item_id', '=', $request->msg_item_id)->get();
			$aMsgItemInfo = Item::find($request->msg_item_id);
			if(!empty($aMsgRequestDeails) && count($aMsgRequestDeails) > 0) {
				
				if((int) $aMsgRequestDeails[0]->manager > 0 && $aMsgRequestDeails[0]->manager != Auth::user()->id) {
					$aMsgUsers[] = ['user_id' => $aMsgRequestDeails[0]->manager,
									'item_id' => $request->msg_item_id,
									'track_id' => $request->msg_track_id,	
									'created_date' =>now()];
				}
				if((int) $aMsgRequestDeails[0]->supervisor > 0  && $aMsgRequestDeails[0]->supervisor != Auth::user()->id && $aMsgItemInfo->approval_status == 1) {
					$aMsgUsers[] = ['user_id' => $aMsgRequestDeails[0]->supervisor,
									'item_id' => $request->msg_item_id,
									'track_id' => $request->msg_track_id,	
									'created_date' =>now()];
				}
				if((int) $aMsgRequestDeails[0]->requested_by > 0 && $aMsgRequestDeails[0]->requested_by != Auth::user()->id) {
					$aMsgUsers[] = ['user_id' => $aMsgRequestDeails[0]->requested_by,
									'item_id' => $request->msg_item_id,
									'track_id' => $request->msg_track_id,	
									'created_date' =>now()];
				}				
				$aMsgGroupMemeber = GroupMember::select('group_members.*')->join('items','items.id','=','group_members.item_id')->where(['group_members.request_detail_id' => $aMsgRequestDeails[0]->id,'group_members.item_id' => $request->msg_item_id])->get();
				if(!empty($aMsgGroupMemeber) && count($aMsgGroupMemeber) > 0)  {
					for($mem_loop = 0;  $mem_loop < count($aMsgGroupMemeber); $mem_loop ++) {
						if($aMsgGroupMemeber[$mem_loop]->user_id != Auth::user()->id) {
							$aMsgUsers[] = ['user_id' => $aMsgGroupMemeber[$mem_loop]->user_id,
										'item_id' => $request->msg_item_id,
										'track_id' => $request->msg_track_id,	
										'created_date' =>now()];
						}
					}
				}
				//Insert message view members details
				if(!empty($aMsgUsers)){
					MessageViewesTracking::insert($aMsgUsers);
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
    public function updateMessageViewInformation(Request $request, MessageViewesTracking $messageview)
    {
		
		if(!empty($request->msg_item_id) && $request->msg_item_id > 0) {
			$messageview->where(['item_id'=>$request->msg_item_id,'user_id'=>Auth::user()->id])->update(
													['view_flag' => 1]);
		}
		return response()->json(['item_id' => $request->msg_item_id, 'status' => 1, 'message' => 'User View Status Updated Successfully...']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item)
    {
        //$item->delete();
		$item->update(
            [           
                'status' => 3
			]
        );
        return redirect()->route('item.index')->withStatus(__('Thesis successfully deleted.'));
    }
}