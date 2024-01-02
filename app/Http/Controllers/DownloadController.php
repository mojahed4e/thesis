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

use App\Role;
use App\User;
use App\Term;
use App\Program;
use App\Item;
use App\ThesisAttachments;
use App\TermProgressChecklist;
use App\DocumentTemplateFolders;
use App\DocumentTemplateSubfolders;
use App\DocumentTemplateFiles;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as Store;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File as FileSys;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }    /**
     * Download to view the uploaded file
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function download(Request $request,$Id, Item $item, User $user, ThesisAttachments $attachment,TermProgressChecklist $progress, DocumentTemplateFiles $templatefiles)
    {
		if($request->type == 'term') {

			$attachInfo = $progress->select('terms_progress_checklist.*')
									 ->where(['terms_progress_checklist.id' => $Id])->get();
			$aItemInfo = $item->select('items.*')->where(['items.id' => $attachInfo[0]->item_id])->get();
			$aStudentInfo = $user::find($aItemInfo[0]->requested_by);
			$myFile = storage_path('app/attachments').$attachInfo[0]->document_file_path;
			$newName = (string)$aStudentInfo->student_id."_".$attachInfo[0]->document_type."_".date("d-m-Y").".".pathinfo($myFile, PATHINFO_EXTENSION);
			if(count($attachInfo) > 0) {
				$mimetype =  \Illuminate\Http\Testing\MimeType::from( $myFile );				
				$headers = ['Content-Description: File Transfer'];
				$headers = ["Content-type: {$mimetype}"];
				$headers = ['Content-Disposition: inline; filename="'.basename($myFile).'"'];
				$headers = ['Expires: 0'];;
				$headers = ['Cache-Control: must-revalidate'];
				$headers = ['Pragma: public'];
				$headers = ['Content-Length: ' . filesize($myFile)];	
							
				return response()->download($myFile,$newName,$headers);
			}
		}
		else if($request->type == 'templete') {
			$attachInfo = $templatefiles->select('document_template_files.file_name','document_template_files.file_path')
									 ->where(['document_template_files.file_id' => $Id])->get();
			if(count($attachInfo) > 0) {
		
				$myFile = storage_path('app/').$attachInfo[0]->file_path;
				$mimetype =  \Illuminate\Http\Testing\MimeType::from( $myFile );
					
				$headers = ['Content-Description: File Transfer'];
				$headers = ["Content-type: {$mimetype}"];
				$headers = ['Content-Disposition: inline; filename="'.basename($myFile).'"'];
				$headers = ['Expires: 0'];;
				$headers = ['Cache-Control: must-revalidate'];
				$headers = ['Pragma: public'];
				$headers = ['Content-Length: ' . filesize($myFile)];
			
				$newName = basename($myFile);
				return response()->file($myFile, $headers,'inline');
			}
		}
		else {
			$attachInfo = $attachment->select('thesis_attachments.*')
									 ->where(['thesis_attachments.id' => $Id])->get();
			$aItemInfo = $item->select('items.*')->where(['items.id' => $attachInfo[0]->item_id])->get();
			$aStudentInfo = $user::find($aItemInfo[0]->requested_by);

			if(count($attachInfo) > 0) {
		
				$myFile = storage_path('app/attachments').$attachInfo[0]->file_path;
				$mimetype =  \Illuminate\Http\Testing\MimeType::from( $myFile );
					
				$headers = ['Content-Description: File Transfer'];
				$headers = ["Content-type: {$mimetype}"];
				$headers = ['Content-Disposition: inline; filename="'.basename($myFile).'"'];
				$headers = ['Expires: 0'];;
				$headers = ['Cache-Control: must-revalidate'];
				$headers = ['Pragma: public'];
				$headers = ['Content-Length: ' . filesize($myFile)];
				
				$newName = (string)$aStudentInfo->student_id."_".$attachInfo[0]->file_name."_".date("d-m-Y").".".pathinfo($myFile, PATHINFO_EXTENSION);

				return response()->download($myFile, $newName, $headers);
			}
		}
	}

	/**
     * View the template document folders and files
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function viewDocumentFoldersFiles(Request $request, Term $terms, DocumentTemplateFolders $docfolder, DocumentTemplateSubfolders $subfolder,DocumentTemplateFiles $docfiles)
    {
    	if(Auth::user()->role_id == 4) {
    		$templatefolders = $docfolder->where([['status','!=',2],['term_id','=',Auth::user()->term_id]])->get();
    		$aTemplatesDocs = array();
    		$folderIndex = 0;    		
    		if(count($templatefolders) > 0){
    			for($folder_loop = 0; $folder_loop < count($templatefolders); $folder_loop++) {
    				$aTemplatesDocs['folder']['name'][$folderIndex] = $templatefolders[$folder_loop]->folder_name;
    				$aTemplatesDocs['folder']['id'][$folderIndex] = $templatefolders[$folder_loop]->folder_id;
    				$subfolderIndex = 0;
    				$aSubFolders = $subfolder->where([['status', '!=', 2],['folder_id','=',$templatefolders[$folder_loop]->folder_id]])->get();   
    				
    				$aSubFoldersDetails = array(); 				
    				if(count($aSubFolders) > 0){
    					for($subfolder_loop = 0; $subfolder_loop < count($aSubFolders); $subfolder_loop++) {
    						$aTemplatesDocs['subfolders']['names'][$templatefolders[$folder_loop]->folder_id][$subfolder_loop] = $aSubFolders[$subfolder_loop]->subfolder_name; 
    						$aTemplatesDocs['subfolders']['id'][$templatefolders[$folder_loop]->folder_id][$subfolder_loop] = $aSubFolders[$subfolder_loop]->subfolder_id;    						
    						$aFiles = $docfiles->where([['status','!=',2],['subfolder_id','=',$aSubFolders[$subfolder_loop]->subfolder_id]])->get();

    						$aFilesDetails = array();    						
    						if(count($aFiles) > 0){
    							for($files_loop = 0; $files_loop < count($aFiles); $files_loop++) {
    								$aFilesDetails['files_name'][$files_loop] = $aFiles[$files_loop]->file_name;
    								$aFilesDetails['files_id'][$files_loop] = $aFiles[$files_loop]->file_id;
    							}
    							$aTemplatesDocs['subfolders']['files'][$aSubFolders[$subfolder_loop]->subfolder_id] = $aFilesDetails;
    						}    						
    						//if(!empty($aFilesDetails)) {
		    					
		    				//}
		    				$subfolderIndex++;
    					}
    				}
    				if(!empty($aSubFoldersDetails)) {
    					$aTemplatesDocs['subfolders']['names'] = $aSubFoldersDetails;	
    				}    				
    				$folderIndex++;
    			}
    		}
    		return view('templates.index-files-tree', ['templatefolders' => $aTemplatesDocs]);
    	}
    	else if(Auth::user()->role_id == 3){
    		$templatefolders = $docfolder->select('document_template_folders.*','terms.name as term_name')
    								->join('terms','terms.id','=','document_template_folders.term_id')
    								->where([['document_template_folders.status','!=',2]])->get();    		
    		$aTemplatesDocs = array();
    		$folderIndex = 0;    		
    		if(count($templatefolders) > 0){
    			for($folder_loop = 0; $folder_loop < count($templatefolders); $folder_loop++) {
    				$aTemplatesDocs['folder']['name'][$folderIndex] = $templatefolders[$folder_loop]->folder_name." ( ".$templatefolders[$folder_loop]->term_name." ) ";
    				$aTemplatesDocs['folder']['id'][$folderIndex] = $templatefolders[$folder_loop]->folder_id;
    				$subfolderIndex = 0;
    				$aSubFolders = $subfolder->where([['status', '!=', 2],['folder_id','=',$templatefolders[$folder_loop]->folder_id]])->get();   
    				
    				$aSubFoldersDetails = array(); 				
    				if(count($aSubFolders) > 0){
    					for($subfolder_loop = 0; $subfolder_loop < count($aSubFolders); $subfolder_loop++) {
    						$aTemplatesDocs['subfolders']['names'][$templatefolders[$folder_loop]->folder_id][$subfolder_loop] = $aSubFolders[$subfolder_loop]->subfolder_name; 
    						$aTemplatesDocs['subfolders']['id'][$templatefolders[$folder_loop]->folder_id][$subfolder_loop] = $aSubFolders[$subfolder_loop]->subfolder_id;    						
    						$aFiles = $docfiles->where([['status','!=',2],['subfolder_id','=',$aSubFolders[$subfolder_loop]->subfolder_id]])->get();

    						$aFilesDetails = array();    						
    						if(count($aFiles) > 0){
    							for($files_loop = 0; $files_loop < count($aFiles); $files_loop++) {
    								$aFilesDetails['files_name'][$files_loop] = $aFiles[$files_loop]->file_name;
    								$aFilesDetails['files_id'][$files_loop] = $aFiles[$files_loop]->file_id;
    							}
    							$aTemplatesDocs['subfolders']['files'][$aSubFolders[$subfolder_loop]->subfolder_id] = $aFilesDetails;
    						}
		    				$subfolderIndex++;
    					}
    				}
    				if(!empty($aSubFoldersDetails)) {
    					$aTemplatesDocs['subfolders']['names'] = $aSubFoldersDetails;	
    				}    				
    				$folderIndex++;
    			}
    		}
    		return view('templates.index-files-tree', ['templatefolders' => $aTemplatesDocs]);
    	}
    	else {
	    	if($request->type == 'vf'){
	    		$templatefiles = $docfiles->where([['status','!=',2],['folder_id','=',$request->folder_id],['subfolder_id','=',$request->subfolder_id]])->get();
				return view('templates.index-files', ['templatefiles' => $templatefiles]);
	    	}
	    	if($request->type == 'sf'){
	    		$aSubfolders = $subfolder->select('document_template_subfolders.*','document_template_folders.folder_id','document_template_folders.folder_name')
	    							->join('document_template_folders','document_template_folders.folder_id','=','document_template_subfolders.folder_id')
	    							->where([['document_template_subfolders.status','!=',2],['document_template_subfolders.folder_id','=',$request->folder_id]])->get();
				return view('templates.index-subfolder', ['subfolders' => $aSubfolders,'folder_id' => $request->folder_id]);
	    	}
	    	else {
	    		$templatefolders = $docfolder->select('document_template_folders.*','terms.name as cohort',
	    								'programs.name as program')
	    								->join('programs','programs.id','=','document_template_folders.program_id')
	    								->join('terms','terms.id','=','document_template_folders.term_id')
	    								->where('document_template_folders.status','!=',2)->get();
	    		$aTerms = $terms->where('status','!=',2)->get();
				return view('templates.index', ['templatefolders' => $templatefolders,'cohorts' => $aTerms]);
	    	}
	    }		
	}

	/**
     * Create/Upload template document folders and files
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function DocumentFoldersFilesCreate(Request $request, Term $terms, Program $programs, DocumentTemplateFolders $docfolder, DocumentTemplateSubfolders $subfolder, DocumentTemplateFiles $docfiles)
    {
		if($request->type == 'fc'){
			$templatefolders = $docfolder->where('status','!=',2)->get();
			$aTerms = $terms->where('status','!=',2)->get();
			$aPrograms = $programs->where('status','!=',2)->get();
			return view('templates.create-folder', ['templatefolders' => $templatefolders,'cohorts' => $aTerms, 'programs' =>$aPrograms ]);
		}
		else if($request->type == 'sfc'){
			$aTerms = $terms->where('status','!=',2)->get();
			$aPrograms = $programs->where('status','!=',2)->get();
			$aSubfolders = $docfolder->select('document_template_folders.*')
	    							->where([['document_template_folders.status','!=',2],['document_template_folders.folder_id','=',$request->folder_id]])->get();
			return view('templates.create-subfolder', ['subfolders' => $aSubfolders,'cohorts' => $aTerms,'programs' => $aPrograms,'folder_id' => $request->folder_id]);
		}
		else if($request->type == 'sfe'){
			$aSubfolders = $subfolder->select('document_template_subfolders.*','document_template_folders.folder_id','document_template_folders.folder_name')
									->join('document_template_folders','document_template_folders.folder_id','=','document_template_subfolders.folder_id')
	    							->where([['document_template_subfolders.status','!=',2],['document_template_subfolders.folder_id','=',$request->folder_id],['document_template_subfolders.subfolder_id','=',$request->subfolder_id]])->get();
			$aTerms = $terms->where('status','!=',2)->get();
			$aPrograms = $programs->where('status','!=',2)->get();
			$templatefolders = $docfolder->where('status','!=',2)->get();
			return view('templates.edit-subfolder', ['subfolders' => $aSubfolders,'templatefolders' => $templatefolders,'cohorts' => $aTerms, 'programs' =>$aPrograms]);
		}
		else if($request->type == 'fe'){
			$templatefolders = $docfolder::where('folder_id','=',$request->folder_id)->get();
			$aTerms = $terms->where('status','!=',2)->get();
			return view('templates.edit-folder', ['templatefolder' => $templatefolders,'cohorts' => $aTerms]);
		}
		else if($request->type == 'uf'){
			$aTerms = $terms->where('status','!=',2)->get();
			$aPrograms = $programs->where('status','!=',2)->get();
			$templatefolders = $docfolder->where('status','!=',2)->get();
			$aSubfolders = $subfolder->select('document_template_subfolders.*','document_template_folders.folder_id','document_template_folders.folder_name')
									->join('document_template_folders','document_template_folders.folder_id','=','document_template_subfolders.folder_id')
	    							->where([['document_template_subfolders.status','!=',2],['document_template_subfolders.folder_id','=','document_template_folders.folder_id']])->get();			
			$templatefiles = $docfiles::where(['folder_id' => $request->folder_id,['status','!=',2]])->get();
			return view('templates.create-file', ['templatefiles' => $templatefiles,'folder_id' => $request->folder_id,'subfolders' => $aSubfolders,'templatefolders' => $templatefolders,'cohorts' => $aTerms, 'programs' =>$aPrograms]);
		}
		else if($request->type == 'ef'){
			$templatefiles = $docfiles::where(['file_id' => $request->file_id,['status','!=',2]])->get();
			return view('templates.edit-file', ['templatefiles' => $templatefiles,'folder_id' => $request->folder_id]);
		}
	}			 

	/**
     * Create/Upload template document folders and files
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function DocumentFoldersFilesStore(Request $request, Program $programs, Term $terms,DocumentTemplateFolders $docfolder, DocumentTemplateSubfolders $subfolder, DocumentTemplateFiles $docfiles)
    {
		if($request->type == 'fc'){
			if(count($request->sle_program_selected) > 0) {
				for($prog_loop = 0; $prog_loop < count($request->sle_program_selected); $prog_loop++) {
					if($request->sle_program_selected[$prog_loop] > 0) {
						$aProgramDet = $programs::find($request->sle_program_selected[$prog_loop]);
						if(count($request->sle_cohort_selected) > 0) {
							for($term_loop = 0; $term_loop < count($request->sle_cohort_selected); $term_loop++) {
								if($request->sle_cohort_selected[$term_loop] > 0) {
									$aTermsDet = $terms::find($request->sle_cohort_selected[$term_loop]);
									$vFolderID = $docfolder->insertGetId([
													'term_id' => trim($request->sle_cohort_selected[$term_loop]),
													'program_id' => trim($request->sle_program_selected[$prog_loop]),
													'folder_name' => trim($request->folder_name),
													'folder_description' => trim($request->folder_description),
													'status' => 1,
													'created_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
										            'created_at' => now()
													]);

									if($vFolderID){									
										$vFolderName = str_replace(' ','-',trim($request->folder_name));
										$vFolderPath = "/templates/".str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(' ','-',trim($aTermsDet->name))."/".$vFolderName;
										 if(!Store::exists($vFolderPath)){
									        Store::makeDirectory($vFolderPath, 0777, true, true);
									    }
									}
								}
								$aTermsDet = "";
							}
						}
					}
				}
			}			
	        return redirect()->route('templates.view-folders-files')->withStatus(__('Template folder created successfully.'));		
		}
		else if($request->type == 'sfc'){
			if(count($request->sle_folder_selected) > 0) {
				for($folder_loop = 0; $folder_loop < count($request->sle_folder_selected); $folder_loop++) {
					if($request->sle_folder_selected[$folder_loop] > 0) {
						$aFolderDet = $docfolder->where('folder_id','=',$request->sle_folder_selected[$folder_loop])->get();
						$aProgramDet = $programs::find($aFolderDet[0]->program_id);
						$aTermsDet = $terms::find($aFolderDet[0]->term_id);
						$vFolderID = $subfolder->insertGetId([
										'folder_id' => trim($request->sle_folder_selected[$folder_loop]),
										'subfolder_name' => trim($request->subfolder_name),
										'subfolder_description' => trim($request->subfolder_description),
										'status' => 1,
										'created_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
							            'created_at' => now()
										]);

						if($vFolderID){
							$vFolderName = str_replace(' ','-',trim($aFolderDet[0]->folder_name));
							$vSubFolderName = str_replace(' ','-',trim($request->subfolder_name));
							$vFolderPath = "/templates/".str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(' ','-',trim($aTermsDet->name))."/".$vFolderName."/".$vSubFolderName;
							 if(!Store::exists($vFolderPath)){
						        Store::makeDirectory($vFolderPath, 0777, true, true);
						    }
						}
					}
				}
			}
	        
	        return redirect()->route('templates.view-folders-files',['folder_id='.$request->sle_folder_selected[0]."&type=sf"])->withStatus(__('Template sub folder created successfully.'));		
		}
		else if($request->type == 'sfe'){
			$aFolderDet = $docfolder->where('folder_id','=',$request->folder_id)->get();
			$aSubFolderDet = $subfolder->where(['folder_id' => $request->folder_id, 'subfolder_id' => $request->subfolder_id])->get();
			$aTermsDet = $terms::find($aFolderDet[0]->term_id);
			$aProgramDet = $programs::find($aFolderDet[0]->program_id);
			$vFolderID = $subfolder->where(['folder_id' => $request->folder_id,'subfolder_id' => $request->subfolder_id])->update([
							'subfolder_name' => trim($request->subfolder_name),
							'subfolder_description' => trim($request->subfolder_description),
							'created_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
				            'updated_at' => now()
							]);

			
			$vFolderName = str_replace(' ','-',trim($aFolderDet[0]->folder_name));
			$vSubFolderName = str_replace(' ','-',trim($aSubFolderDet[0]->subfolder_name));
			$vOldName 	 = "/templates/".str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(' ','-',trim($aTermsDet->name))."/".$vFolderName."/".$vSubFolderName."/";
			$vNewName	 = "/templates/".str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(' ','-',trim($aTermsDet->name))."/".$vFolderName."/".str_replace(' ','-',trim(trim($request->folder_name)))."/";
			if(Store::exists($vOldName) && $vOldName !== $vNewName ){
		        Store::rename($vOldName, $vNewName);
		    }
				        
	        return redirect()->route('templates.view-folders-files',['folder_id='.$aFolderDet[0]->folder_id."&type=sf"])->withStatus(__('Template sub folder updated successfully.'));	
		}
		else if($request->type == 'fe'){
			$aFolderDet = $docfolder->where('folder_id','=',$request->folder_id)->get();
			$aTermsDet = $terms::find($aFolderDet[0]->term_id);
			$aProgramDet = $programs::find($aFolderDet[0]->program_id);
			$vFolderID = $docfolder->where(['folder_id' => $request->folder_id])->update([
							'folder_name' => trim($request->folder_name),
							'term_id' => trim($request->term_id),
							'folder_description' => trim($request->folder_description),
							'created_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
				            'updated_at' => now()
							]);

			
			$vFolderName = str_replace(' ','-',trim($aFolderDet[0]->folder_name));
			$vOldName 	 = "/templates/".str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(' ','-',trim($aTermsDet->name))."/".$vFolderName."/";
			$vNewName	 = "/templates/".str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(' ','-',trim($aTermsDet->name))."/".str_replace(' ','-',trim(trim($request->folder_name)))."/";
			if(Store::exists($vOldName) && $vOldName !== $vNewName ){
		        Store::rename($vOldName, $vNewName);
		    }
				        
	        return redirect()->route('templates.view-folders-files')->withStatus(__('Template folder updated successfully.'));
		}
		else if($request->type == 'uf'){

			if(isset($request->sle_folder_selected)){
				if(count($request->sle_folder_selected) > 0){
					for($subfolder_loop = 0; $subfolder_loop < count($request->sle_folder_selected); $subfolder_loop++)
					{
						$vSubFolderID = $request->sle_folder_selected[$subfolder_loop];
						if($vSubFolderID > 0) {
							$fileInForm = "templatefile";
							$aSubFolderDet = $subfolder->where(['subfolder_id' => $vSubFolderID])->get();
							$aFolderDet = $docfolder->where('folder_id','=',$aSubFolderDet[0]->folder_id)->get();		
							$aTermsDet = $terms::find($aFolderDet[0]->term_id);
							$aProgramDet = $programs::find($aFolderDet[0]->program_id);
							if ($request->hasFile($fileInForm)) {
						        $file = $request->file($fileInForm);		        
						        if ($file->isValid()) {
						            // Filename is hashed filename + part of timestamp
						            $hashedName = hash_file('md5', $file->path());
						            $timestamp = rand(999, 999999999);
						            $vFilename = '/templates/'.str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(" ","-",$aTermsDet->name)."/".str_replace(" ","-",$aFolderDet[0]->folder_name)."/".str_replace(" ","-",$aSubFolderDet[0]->subfolder_name)."/".$hashedName . $timestamp . '.' . $file->getClientOriginalExtension();
						            Store::disk('local')->put($vFilename, file_get_contents($file));

						            $vFileID = $docfiles->insertGetId([
						            		'folder_id' => $aFolderDet[0]->folder_id,
						            		'subfolder_id' => $vSubFolderID,
											'file_name' => trim($request->file_name),
											'file_description' => trim($request->file_description),
											'file_path' => $vFilename,
											'status' => 1,							
											'uploaded_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
								            'created_at' => now()
											]);
						        }
						    }
						}
					}
					return redirect()->route('templates.view-folders-files',['folder_id='.$aFolderDet[0]->folder_id.'&subfolder_id='.$vSubFolderID.'&type=vf'])->withStatus(__('Template file upload failed. Please try again later.'));
				}
			}
			else {
				$fileInForm = "templatefile";
				$aSubFolderDet = $subfolder->where(['subfolder_id' => $request->subfolder_id])->get();
				$aFolderDet = $docfolder->where('folder_id','=',$aSubFolderDet[0]->folder_id)->get();		
				$aTermsDet = $terms::find($aFolderDet[0]->term_id);
				$aProgramDet = $programs::find($aFolderDet[0]->program_id);

				if ($request->hasFile($fileInForm)) {
			        $file = $request->file($fileInForm);		        
			        if ($file->isValid()) {
			            // Filename is hashed filename + part of timestamp
			            $hashedName = hash_file('md5', $file->path());
			            $timestamp = rand(999, 999999999);
			            $vFilename = '/templates/'.str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(" ","-",$aTermsDet->name)."/".str_replace(" ","-",$aFolderDet[0]->folder_name)."/".str_replace(" ","-",$aSubFolderDet[0]->subfolder_name)."/".$hashedName . $timestamp . '.' . $file->getClientOriginalExtension();
			            Store::disk('local')->put($vFilename, file_get_contents($file));

			            $vFileID = $docfiles->insertGetId([
			            		'folder_id' => $aFolderDet[0]->folder_id,
			            		'subfolder_id' => $request->subfolder_id,
								'file_name' => trim($request->file_name),
								'file_description' => trim($request->file_description),
								'file_path' => $vFilename,
								'status' => 1,							
								'uploaded_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
					            'created_at' => now()
								]);
			            return redirect()->route('templates.view-folders-files',['folder_id='.$aFolderDet[0]->folder_id.'&subfolder_id='.$request->subfolder_id.'&type=vf'])->withStatus(__('Template file updated successfully.'));
			        }
			    }
			    else {
			    	return redirect()->route('templates.view-folders-files',['folder_id='.$aFolderDet[0]->folder_id.'&subfolder_id='.$request->subfolder_id.'&type=vf'])->withStatus(__('Template file upload failed. Please try again later.'));
			    }
			}
		}
		else if($request->type == 'ef'){

			$fileInForm = "templatefile";
			$aFolderDet = $docfolder->where('folder_id','=',$request->folder_id)->get();
			$aSubFolderDet = $subfolder->where(['subfolder_id' => $request->subfolder_id,'folder_id' => $request->folder_id])->get();
			$aTermsDet = $terms::find($aFolderDet[0]->term_id);
			$aProgramDet = $programs::find($aFolderDet[0]->program_id);

			if ($request->hasFile($fileInForm)) {
		        $file = $request->file($fileInForm);		        
		        if ($file->isValid()) {
		            // Filename is hashed filename + part of timestamp
		            $hashedName = hash_file('md5', $file->path());
		            $timestamp = rand(999, 999999999);
		            $vFilename = '/templates/'.str_replace(' ','-',trim($aProgramDet->name))."/".str_replace(" ","-",$aTermsDet->name)."/".str_replace(" ","-",$aFolderDet[0]->folder_name)."/".str_replace(" ","-",$aSubFolderDet[0]->subfolder_name)."/".$hashedName . $timestamp . '.' . $file->getClientOriginalExtension();
		            Store::disk('local')->put($vFilename, file_get_contents($file));

		            $vFileID = $docfiles->where(['folder_id' => $request->folder_id,'subfolder_id' => $request->subfolder_id,'file_id' => $request->file_id])->update([
							'file_name' => trim($request->file_name),
							'file_description' => trim($request->file_description),
							'file_path' => $vFilename,
							'uploaded_by' => Auth::user()->id ? (int) Auth::user()->id : 0,	
				            'updated_at' => now()
							]);
		            return redirect()->route('templates.view-folders-files',['folder_id='.$request->folder_id.'&subfolder_id='.$request->subfolder_id.'&type=vf'])->withStatus(__('Template file updated successfully.'));
		        }
		    }
		    else {
		    	return redirect()->route('templates.view-folders-files',['folder_id='.$request->folder_id.'&subfolder_id='.$request->subfolder_id.'&type=vf'])->withStatus(__('Template file upload failed. Please try again later.'));
		    }
		}
	}

		/**
     * Create/Upload template document folders and files
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function DocumentFoldersFilesDelete(Request $request, DocumentTemplateFolders $docfolder, DocumentTemplateSubfolders $subfolder,  DocumentTemplateFiles $docfiles)
    {
		if($request->type == 'fd'){
			$docfolder->where('folder_id','=',$request->folder_id)->update(['status' => 2
				]);        
	        return redirect()->route('templates.view-folders-files')->withStatus(__('Template folder deleted successfully.'));		
		}
		else if($request->type == 'sfd'){
			$subfolder->where([['folder_id','=',$request->folder_id],['subfolder_id','=',$request->subfolder_id]])->update(['status' => 2
				]);        
	        return redirect()->route('templates.view-folders-files',['folder_id='.$request->folder_id.'&type=sf'])->withStatus(__('Template sub folder deleted successfully.'));		
		}
		else if($request->type == 'ffd'){
			$docfiles->where(['file_id' => $request->file_id,'subfolder_id' => $request->subfolder_id])->update(['status' => 2
				]);        
	        return redirect()->route('templates.view-folders-files',['folder_id='.$request->folder_id.'&subfolder_id='.$request->subfolder_id.'&type=vf'])->withStatus(__('Template file deleted successfully.'));		
		}
		else {
	    	return redirect()->route('templates.view-folders-files',['folder_id='.$request->folder_id])->withStatus(__('Template folder/file delete failed. Please try again later.'));
	    }
	}
}