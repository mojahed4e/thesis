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

use Auth;
use App\Role;
use App\User;
use App\Term;
use App\ThesisRequestDetails;
use App\GroupMember;
use App\Program;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	
    public function __construct()
    {	
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $this->authorize('manage-users', User::class);
        return view('users.index', ['users' => $model->with(['role','term'])->get()]);
    }

    /**
     * Show the form for creating a new user
     *
     * @param  \App\Role  $model
     * @return \Illuminate\View\View
     */
    public function create(Role $model, Term $term,Program $program)
    {
        return view('users.create', ['roles' => $model->get(['id', 'name']), 'terms' => $term->get(['id', 'name']), 'programs' => $program->get(['id', 'description'])]);
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $model->create($request->merge([
            'picture' => $request->photo ? $request->photo->store('profile', 'public') : null,
			'availabe_flage' => $request->availabe_flage,
			'program_availability' => implode(",",$request->program_availability),			
            'password' => Hash::make($request->get('password'))
        ])->all());

        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }
		
    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @param  \App\Role  $model
     * @return \Illuminate\View\View
     */
    public function edit(User $user, Role $model, Term $term,Program $program)
    {
        return view('users.edit', ['user' => $user->load(['role','term']), 'roles' => $model->get(['id', 'name']), 'terms' => $term->get(['id', 'name']), 'programs' => $program->get(['id', 'description'])]);
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accessOnbehalfSomeone(Request $request, User $user)
    {
    	$vUserID = $request->login_as;

    	$request->session()->flush();
    	if(Auth::loginUsingId($vUserID)){
			return redirect()->route('item.index');
		}
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {

        $hasPassword = $request->get('password');
        $program_availability = isset($request->program_availability) ? implode(",",$request->program_availability) : NULL;
        $user->update(
            $request->merge([
                'picture' => $request->photo ? $request->photo->store('profile', 'public') : $user->picture,
				'availabe_flage' => $request->availabe_flage,
				'program_availability' => $program_availability,				
                'password' => Hash::make($request->get('password'))
            ])->except([$hasPassword ? '' : 'password'])
        );

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }
	
	
	public function syncUersFromLmsSystem(Request $request,Term $termModel, User $userModel,GroupMember $groupmemberModel, Program $program) {
		
		//$aStudentInfo['request_type'] = 'add';
		//$aStudentInfo['cohort'] = 'Spring 2023';
		//$aStudentInfo['student'][] = array('number' => '123456','email' => 'testing@adsm.ac.ae','cohort' => 'Spring 2023','name' => 'testing once','status' => 1, 'program' => 'Master of Science in Business Analytics','ay' => '2020-2021','photo' => 'http://adsm/eFiles/Photos/1072.jpg');		
		//$aStudentInfo['student'][] = array('number' => '213889','mobile' => '9715897624','email' => 'adsm-213889@adsm.ac.ae','cohort' => 'Spring 2023','name' => 'testing once','status' => 1, 'program' => 'Master of Science in Business Analytics','ay' => '2020-2021','photo' => 'http://adsm/eFiles/Photos/1072.jpg');	

		//$request = $aStudentInfo;
		$aReturnRespose = array();
		$vLoop = 0;		
		if(!empty($request['student'])) {

			foreach ($request['student'] as $aStudent) {					
				$vStudentId 	= trim($aStudent['number']);
				$vStudentEmail 	= trim($aStudent['email']);
				$vStudentMobile	= trim($aStudent['mobile']);		
				$vStudentName 	= trim($aStudent['name']);		
				$vStudentStatus	= trim($aStudent['status']);
				$vCohotName 	= trim($aStudent['cohort']);
				$vCourseTitle 	= trim($aStudent['program']);
				$vAcademicYear 	= trim($aStudent['ay']);
				$vReqType		= trim($request['request_type']);
				$vImagePath		= trim($aStudent['photo']);
				$vPassword		= 'secret';

				$aProgramInfo = $program->where('description','=',"{$vCourseTitle}")->get();				
				// add new users and cohort in the system
				if($vReqType == 'add') {
					$aReturnRespose[$vLoop]['email'] = $vStudentEmail;
					//Check cohort exists or not
					$aTermInfo = $termModel::query()->where('name', '=', "{$vCohotName}") ->get();
					if(count($aTermInfo) > 0 && !empty($aTermInfo)){
						//Check user exists or not
						$aUserInfo = $userModel::query()->where('email', '=', "{$vStudentEmail}") ->get();
						if(count($aUserInfo) > 0 && !empty($aUserInfo)){					
							if($aUserInfo[0]->term_id == $aTermInfo[0]->id) {
								//Update chort id
								$userModel->where(['id'=>$aUserInfo[0]->id])->update(['term_id' => $aTermInfo[0]->id,'updated_at' => now()]);
							}
							else {
								//Update chort id
								$userModel->where(['id'=>$aUserInfo[0]->id])->update(['term_id' => $aTermInfo[0]->id,'updated_at' => now()]);
								//# group info table
								//$groupmemberModel->where(['user_id'=>$aUserInfo[0]->id])->update(['status' => 2]);
							}
							$aReturnRespose[$vLoop]['status'] = 1;
							$aReturnRespose[$vLoop]['message'] = 'User info updated';
						}
						else {
							//insert user information
							$vUserInsertID = $userModel->insertGetId([
														'name' => $vStudentName,
														'email' => $vStudentEmail,
														'mobile_number' => $vStudentMobile,
														'student_id' => $vStudentId,
														'password' => Hash::make($vPassword),
														'course_title' => $vCourseTitle,
														'role_id' => 4,	
														'program_id' => $aProgramInfo[0]->id,
														'term_id' =>$aTermInfo[0]->id,
														'created_at' => now(),
														'status' => 1
													]);
							if($vUserInsertID) {
								$aReturnRespose[$vLoop]['status'] = 1;
								$aReturnRespose[$vLoop]['message'] = 'User info added';
								if(!empty($vImagePath)) {
									/*** DOWNLOAD FILE FROM ADSM URL ****/
									$aImageInfo = explode('/',$vImagePath);
									$vImageName = $aImageInfo[count($aImageInfo)-1];
									$username=config('items.image_auth.usernmae');
									$password=config('items.image_auth.password');
									$usernamePassword = $username . ':' . $password;
									//FILE NAME				
									$filename = $vUserInsertID.'_photo_'.rand(999, 999999999)."_".$vImageName;
									$error_msg = "";							
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, $vImagePath);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
									curl_setopt($ch, CURLOPT_USERPWD, $usernamePassword);
									curl_setopt($ch, CURLOPT_FAILONERROR, true);
									$result = curl_exec($ch);
									if (curl_errno($ch)) {
										$error_msg = curl_error($ch);
									}
									curl_close($ch);
									
									if(empty($error_msg)) {
										//PHOTO PATH
										$vPhotoPath = "/storage/app/public/profilepicture/".$filename;

										$fppath =  public_path().$vPhotoPath;
										$fp = fopen($fppath, 'w');
										fwrite($fp,$result);				
										fclose($fp);
										
										if(is_file($fppath)) {
											//Update user photo info
											$userModel->where(['id'=>$vUserInsertID])->update(['picture' => $vPhotoPath]);
										}
									}
								}
							}
							else {
								$aReturnRespose[$vLoop]['status'] = 0;
								$aReturnRespose[$vLoop]['status'] = 'user creation failed';
							}
						}								
					}
					else {				
						$vCohotInsertID = $termModel->insertGetId([
														'name' => $vCohotName,
														'description' => $vCohotName,
														'academic_year' => $vAcademicYear,
														'created_at' => now()												
													]);
						if($vCohotInsertID > 0) {
							$aUserInfo = $userModel::query()->where('email', '=', "{$vStudentEmail}") ->get();
							if(count($aUserInfo) > 0 && !empty($aUserInfo)){
								//Update chort id
								$userModel->where(['id'=>$aUserInfo[0]->id])->update(['term_id' => $vCohotInsertID,'updated_at' => now()]);
								//# group info table
								$groupmemberModel->where(['user_id'=>$aUserInfo[0]->id])->update(['status' => 2]);
								$aReturnRespose[$vLoop]['status'] = 'User info added';
							}
							else {
								//insert user information
								$vUserInsertID = $userModel->insertGetId([
															'name' => $vStudentName,
															'email' => $vStudentEmail,
															'mobile_number' => $vStudentMobile,
															'student_id' => $vStudentId,
															'password' => Hash::make($vPassword),
															'role_id' => 4,	
															'program_id' => $aProgramInfo[0]->id,
															'term_id' =>$vCohotInsertID,
															'created_at' => now(),
															'status' => 1
														]);
								if($vUserInsertID) {
									$aReturnRespose[$vLoop]['status'] = 1;
									$aReturnRespose[$vLoop]['message'] = 'User info added';
									if(!empty($vImagePath)) {
										/*** DOWNLOAD FILE FROM ADSM URL ****/
										$aImageInfo = explode('/',$vImagePath);
										$vImageName = $aImageInfo[count($aImageInfo)-1];
										$username=config('items.image_auth.usernmae');
										$password=config('items.image_auth.password');
										$usernamePassword = $username . ':' . $password;
										//FILE NAME				
										$filename = $vUserInsertID.'_photo_'.rand(999, 999999999)."_".$vImageName;
										
										$error_msg = "";
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_URL, $vImagePath);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
										curl_setopt($ch, CURLOPT_USERPWD, $usernamePassword);
										curl_setopt($ch, CURLOPT_FAILONERROR, true);
										$result = curl_exec($ch);
										if (curl_errno($ch)) {
											$error_msg = curl_error($ch);
										}
										curl_close($ch);
										
										if(empty($error_msg)) {
											//PHOTO PATH
											$vPhotoPath = "/storage/app/public/profilepicture/".$filename;

											$fppath = public_path().$vPhotoPath;
											$fp = fopen($fppath, 'w');
											fwrite($fp,$result);				
											fclose($fp);
											
											if(is_file($fppath)) {
												//Update user photo info
												$userModel->where(['id'=>$vUserInsertID])->update(['picture' => $vPhotoPath]);
											}
										}
									}
								}
								else {
									$aReturnRespose[$vLoop]['status'] = 0;
									$aReturnRespose[$vLoop]['status'] = 'user creation failed';
								}
							}
						}
						else{
							$aReturnRespose[$vLoop]['status'] = 0;
							$aReturnRespose[$vLoop]['message'] = 'Cohot create failed. please try again later';
						}
					}			
				}// update users in the system
				else {
					$aReturnRespose[$vLoop]['email'] = $vStudentEmail;
					//Check cohort exists or not
					$aTermInfo = $termModel::query()->where('name', '=', "{$vCohotName}") ->get();
					if(count($aTermInfo) > 0 && !empty($aTermInfo)){
						$aUserInfo = $userModel::query()->where('email', '=', "{$vStudentEmail}") ->get();
						if(count($aUserInfo) > 0 && !empty($aUserInfo)){
							//Update chort id
							$userModel->where(['id'=>$aUserInfo[0]->id])->update(['status' => $vStudentStatus,'term_id' => $aTermInfo[0]->id,'updated_at' => now()]);
							$aReturnRespose[$vLoop]['status'] = 1;
							$aReturnRespose[$vLoop]['message'] = 'User info updated';
						}
						else {
							$aReturnRespose[$vLoop]['message'] = 'student information not found. Please make student create request.';
							$aReturnRespose[$vLoop]['status'] = 0;
						}
						
					}
					else {
						$aReturnRespose[$vLoop]['message'] = "cohort information not found. Please make cohort creation request.";
						$aReturnRespose[$vLoop]['status'] = 0;
					}
				}
				$vLoop++;
			}
		}
		else {
			$aReturnRespose[$vLoop]['message'] = "Student information array is empty";
			$aReturnRespose[$vLoop]['status'] = 0;
		}
		return json_encode($aReturnRespose);
	}

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }
}
