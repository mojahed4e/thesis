<div class="row">
	<div class="col-md-12">
		<div class="card">         	
			<div class="card-body">
			  <div id="accordion" role="tablist">
			    <div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<h5 class="mb-0">																
								@if($requestdetails[0]->progress_completion == 0)
									@php 
										$vExpandText = 'true'; 
									@endphp									
								@else
									@php 
									$vExpandText = 'false'; 
									@endphp									
								@endif
								@php
									$vCompleteMeet1Count = 0;
									$vCompleteMeet2Count = 0;
								@endphp								
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="{{$vExpandText}}" aria-controls="collapseOne" class="text-center font-weight-bold collapsed">
									@if(count($progressdetails) > 0)
										@if($progressdetails[0]->upload_file_status == 0 && $requestdetails[0]->progress_completion == 0 && $progressdetails[0]->approval_status == 0)
											Term - I In Progress
										@elseif( $progressdetails[0]->upload_file_status == 1 && $requestdetails[0]->progress_completion == 0 && $progressdetails[0]->approval_status != 1)
											Term - I Awating for Manager Aproval
										@elseif( $progressdetails[0]->upload_file_status == 0 && $progressdetails[0]->approval_status == 2)
											Term - I Requested Changes by the Manager
										@elseif($progressdetails[0]->upload_file_status == 1 && $progressdetails[0]->approval_status == 1)	
											Term - I Completed									
										@endif
									@else
										Term - I In Progress
									@endif								
								<i class="prog_icon material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
					@if($requestdetails[0]->progress_completion == 0)
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
					@else
						<div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
					@endif								
					@if(auth()->user()->role_id == 4)
						@include('mythesis.student.term-I_fileprogress')
					@elseif(auth()->user()->role_id == 3 )
						@include('mythesis.supervisor.term-I_fileprogress')
					@elseif(auth()->user()->role_id == 2 )
						@if(request()->get('action') == 'ac')
							@include('mythesis.supervisor.term-I_fileprogress')
						@else
							@include('mythesis.manager.term-I_fileprogress')
						@endif
					@elseif(auth()->user()->role_id == 5 )
						@include('mythesis.panel.term-I_fileprogress')
					@else
						@include('mythesis.term-I_fileprogress')
					@endif
					</div>
			    <div class="card-collapse">
					  <div class="card-header" role="tab" id="headingTwo">
							<h5 class="mb-0">
								@if($requestdetails[0]->progress_completion == 0)
									@php 
									$vExpandText = 'true'; 
									@endphp									
								@else
									@php 
									$vExpandText = 'true'; 
									@endphp									
								@endif
								<a data-toggle="collapse" href="#collapseTwo" aria-expanded="{{$vExpandText}}" aria-controls="collapseTwo" class="collapsed text-center font-weight-bold">
									@if(count($term2progressdetails) > 0)
										@if($term2progressdetails[0]->upload_file_status == 0 && $requestdetails[0]->progress_completion == 1 && $term2progressdetails[0]->approval_status == 0)
											Term - II In Progress
										@elseif( $term2progressdetails[0]->upload_file_status == 1 && $requestdetails[0]->progress_completion == 1 && $term2progressdetails[0]->approval_status != 1)
											Term - II Awating for Manager Aproval
										@elseif( $term2progressdetails[0]->upload_file_status == 0 && $term2progressdetails[0]->approval_status == 2 && $requestdetails[0]->progress_completion == 1)
											Term - II Requested Changes by the Manager
										@elseif($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status == 1)	
											Term - II Completed
										@endif
									@else
										@if($requestdetails[0]->progress_completion == 1)
											Term - II In Progress
										@else
											Term - II Pending
										@endif
									@endif
								<i class="prog_icon material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
					  @if($requestdetails[0]->progress_completion == 1)
			        <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
					  @else
					  	<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
				    @endif				   
			      @if(auth()->user()->role_id == 4)
							@include('mythesis.student.term-II_fileprogress')
						@elseif(auth()->user()->role_id == 3 )
							@include('mythesis.supervisor.term-II_fileprogress')
						@elseif(auth()->user()->role_id == 2 )
							@if(request()->get('action') == 'ac')
								@include('mythesis.supervisor.term-II_fileprogress')
							@else
								@include('mythesis.manager.term-II_fileprogress')
							@endif
						@elseif(auth()->user()->role_id == 5 )
							@include('mythesis.panel.term-II_fileprogress')
						@else
							@include('mythesis.term-II_fileprogress')
						@endif
			    </div>                          
			  </div>        
			</div>
			
		</div>
@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script src="{{ asset('material') }}/wnumb/wNumb.min.js"></script>
<script>
  $(document).ready(function() {
    $('.datetimepicker').datetimepicker({
      icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-chevron-up",
          down: "fa fa-chevron-down",
          previous: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          today: 'fa fa-screenshot',
          clear: 'fa fa-trash',
          close: 'fa fa-remove'
      },
      format: 'DD-MM-YYYY'
    });
	tinymce.init({
		selector: '#completionmessage',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});	
	vProgressPath = '{!! $successPath !!}';
	
	$("#tablink2").click( function() {
		window.setTimeout( function() {
		$('#postcomment').focus();},200);	
	});
	var tabnum =  getParameterByName('tab');
	if(tabnum == 2) {		
		$('#link1').removeClass("active");
		$('#link2').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink2').addClass("active show");	
		$('#postcomment').focus();	
	}
	else if(tabnum == 4) {
		$('#link1').removeClass("active");
		$('#link4').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink4').addClass("active show");	
		$('#postcomment').focus();	
	}
  });
  
	function funTerm1SubmmitValidate() {			
		var userRoleID = {{auth()->user()->role_id}};
		var vQryAction = '{{ request()->get('action')}}';
		var vProgressStat	= {{$progressdetails[0]->approval_status}};
		if($("#submmission_status").val() != "") {		
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3 || (userRoleID == 2 && vQryAction == 'ac')) {					
					var vMeetPrepComplete = $("#meeting1complete").val();									
					if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
						swal({
							title: "Please choose presentaion file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#proposalrubric").val() == "" && $("#proposalrubric_flag").val() == 0) {
						swal({
							title: "Please choose poposal rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {	
						if(vMeetPrepComplete == 8 && $("#chap1rubricdone").val() == 1) {
							$("#statsupdate_comments").val(1);
							document.frmCommentUpdate.method='POST';
							document.frmCommentUpdate.action=vProgressPath;
							document.frmCommentUpdate.submit();
						}
						else if($("#chap1rubricdone").val() == 0){
							swal({
								title: "Please prepare and complete the Chapter - I report rubric",
								text: '',
								type: 'error',
								confirmButtonColor: '#47a44b'
							})
							return false;
						}					
						else {
							swal({
								title: "Please complete all meeting minutes and upload Chapter - II and Presentation file",
								text: '',
								type: 'error',
								confirmButtonColor: '#47a44b'
							})
							return false;	
						}
					}
				}
				else {										
					if($("#panelmembercount").val() == 0){
						swal({
							title: "Please select presentation examiner(s) for this thesis!",
							text: '',
							type: 'error',
							confirmButtonColor: 'red'
						})
						return false;	
					}
					else if(vProgressStat != 3 && $("#panelmembercount").val() > 0){						
						swal({
							title: "Please approve term-1 completion for grading!",
							text: '',
							type: 'error',
							confirmButtonColor: 'red'
						})
						return false;
					}					
					else if($("#term1rubriccount").val() != $("#panelmembercount").val()){						
						swal({
							title: "Please wait presentation rubric creation pending by examiner(s) for this thesis!",
							text: '',
							type: 'error',
							confirmButtonColor: 'red'
						})
						return false;	
					}					
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				
			}
			else if($("#submmission_status").val() == 2) {				
				if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}				
			}
			else if($("#submmission_status").val() == 3) {
				if($("#panelmembercount").val() > 0){
					if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				else {					
					swal({
						title: "Please assign examiner(s) for this thesis on details page!",
						text: '',
						type: 'warning',
						confirmButtonColor: 'red'
					})
					return false;
				}
			}
			else {				
				$("#statsupdate_comments").val(1);
				document.frmCommentUpdate.method='POST';
				document.frmCommentUpdate.action=vProgressPath;
				document.frmCommentUpdate.submit();
			}
		}		
	}
	
	function funTerm2SubmmitValidate() {		
		var userRoleID = {{auth()->user()->role_id}};
		var vQryAction = '{{ request()->get('action')}}';
		var vProgressStat	= {{$progressdetails[0]->approval_status}};
		if($("#submmission_status").val() != "") {		
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3 || (userRoleID == 2 && vQryAction == 'ac')) {					
					var vMeetPrepComplete = $("#meeting1complete").val();										
					if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
						swal({
							title: "Please choose presentaion file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#proposalrubric").val() == "" && $("#proposalrubric_flag").val() == 0) {
						swal({
							title: "Please choose poposal rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {						
						if(vMeetPrepComplete == 8) {
							$("#statsupdate_comments").val(1);
							document.frmCommentUpdate.method='POST';
							document.frmCommentUpdate.action=vProgressPath;
							document.frmCommentUpdate.submit();
						}
						else {
							swal({
								title: "Please complete all meeting minutes and upload Chapter - II and Presentation file",
								text: '',
								type: 'error',
								confirmButtonColor: '#47a44b'
							})
							return false;	
						}
					}
				}
				else {										
					if($("#panelmembercount").val() == 0){
						swal({
							title: "Please select presentation examiner(s) for this thesis!",
							text: '',
							type: 'error',
							confirmButtonColor: 'red'
						})
						return false;	
					}
					else if(vProgressStat != 3 && $("#panelmembercount").val() > 0){						
						swal({
							title: "Please approve term-1 completion for grading!",
							text: '',
							type: 'error',
							confirmButtonColor: 'red'
						})
						return false;
					}					
					else if($("#term2rubriccount").val() != $("#panelmembercount").val()){						
						swal({
							title: "Please wait presentation rubric creation pending by examiner(s) for this thesis!",
							text: '',
							type: 'error',
							confirmButtonColor: 'red'
						})
						return false;	
					}					
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				
			}
			else if($("#submmission_status").val() == 2) {				
				if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}				
			}
			else if($("#submmission_status").val() == 3) {
				if($("#panelmembercount").val() > 0){
					if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				else {					
					swal({
						title: "Please assign examiner(s) for this thesis on details page!",
						text: '',
						type: 'warning',
						confirmButtonColor: 'red'
					})
					return false;
				}
			}
			else {				
				$("#statsupdate_comments").val(1);
				document.frmCommentUpdate.method='POST';
				document.frmCommentUpdate.action=vProgressPath;
				document.frmCommentUpdate.submit();
			}
		}	
	}

	
	function funTerm3SubmmitValidate() {						
		var userRoleID = {{auth()->user()->role_id}};
		var vQryAction = '{{ request()->get('action') }}';
		var vItemAssigned = {{ $item->assigned_to }};				
		if($("#submmission_status").val() != "") {
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3 || (userRoleID == 2 && vQryAction == 'ac')) {
					var vMeetPrepComplete = $("#meeting2complete").val();					
					if($("#proposalfile").val() == "" && $("#proposalfile_flag").val() == 0) {
						swal({
							title: "Please choose proposal file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						$("#proposalfile .ajax-upload-dragdrop").focus();
						return false;
					}
					else if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
						swal({
							title: "Please choose presentaion file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#proposalrubric").val() == "" && $("#proposalrubric_flag").val() == 0) {
						swal({
							title: "Please choose poposal rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}					
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						if(vMeetPrepComplete == 5) {
							$("#statsupdate_comments").val(1);
							document.frmCommentUpdate.method='POST';
							document.frmCommentUpdate.action=vProgressPath;
							document.frmCommentUpdate.submit();
						}
						else {
							swal({
								title: "Please complete all meeting minutes",
								text: '',
								type: 'error',
								confirmButtonColor: '#47a44b'
							})
							return false;	
						}
					}
				}
				else {
					if($('input:radio[name="external_review"]:checked').val() != 1) {
						swal("", "Please choose 'YES' to make it external review completed!", "error").then((result) => {
							$('input:radio[name="external_review"]').focus();
						});							
						return false;
					}
					else if($('input:radio[name="defence_status"]:checked').val() != 1) {
						swal("", "Please choose 'YES' to make it defense completed !", "error").then((result) => {
							$('input:radio[name="external_review"]').focus();
						});	
						return false;
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else if($('#finalreportdraft1').val() == "") {
						swal({
							title: "Please choose final report draft 1 file!",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;													
					}
					else if($('#finalreportdraft1rubric').val() == "") {
						swal({
							title: "Please choose final report draft 1 rubric file!",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;												
					}
					else if($('#presentationrubric').val() == "") {
						swal({
							title: "Please choose Presentation Rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($('#finalreportrubric').val() == "") {
						swal({
							title: "Please choose Report Rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}					
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				
			}
			else if($("#submmission_status").val() == 2) {
				if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else if($("#submmission_status").val() == 3) {								
				if($('input:radio[name="external_review"]:checked').length == 0) {
					swal("", "Please choose external review completion status!", "error").then((result) => {
					  $('input:radio[name="external_review"]').focus();
					});	
				}
				else if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else if($("#submmission_status").val() == 4) {				
				if($('input:radio[name="defence_status"]:checked').length == 0) {
					swal("", "Please choose defense completion status!", "error").then((result) => {
					  $('input:radio[name="defence_status"]').focus();
					});	
				}
				else if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else {				
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
			}
		}						
	}
	
	
	function funUpdateTermCompletiontStatus(pmValue) {		
		var optValue = $("#submmission_status").val();			
		if(pmValue == 0) {
			if(parseInt(optValue) == 1) {			
				$("#divCompletionMessage").show();
			}
			else {
				$("#divCompletionMessage").hide();			
			}
		}
		else if(pmValue == 1) {					
			if(parseInt(optValue)== 1) {				
				$("#divCompletionMessage").show();
				$("#divProposalRubric").hide();
				$("#divPresentationRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else if(parseInt(optValue)== 2) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else {
				$("#divProposalRubric").hide();
				$("#divCompletionMessage").hide();
				$("#divPresentationRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
		}
		else {						
			if(parseInt(optValue)== 1) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else if(parseInt(optValue)== 2) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else if(parseInt(optValue)== 3) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else if(parseInt(optValue)== 4) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else if(parseInt(optValue)== 5) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else {
				$("#divCompletionMessage").hide();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
		}
	}
	
	function funShowBrowseBtn(pmName,value) {		
		if(value == 1) {
			$("#"+pmName+"file").show();
			$("#"+pmName+"link").hide();
		}
		else {
			$("#"+pmName+"file").hide();
			$("#"+pmName+"link").show();
		}
		return false;
	}
	
	function deleteCallback(data) {
		$.ajax({
			type: "POST",
			url: vProgressPath,
			dataType: 'json',
			data: {
				"_token": "{{ csrf_token() }}",
				"field_name": 'otherdocumsnts',						
				"name": data,						
				"delete_flag": 1
			},
			success: function( msg ) {

			}
		});
	}

	function funApproveFileSubmission(pmdocument,pmItemID,pmChecklist) {
		var vElementName = pmdocument+'_file_approve';				
		if($("#"+vElementName).prop("checked") == true){
			var vFileApprovePath = '{{ url("/mythesis/approve-termfile") }}';		
			$.ajax({
			   type: 'POST',
			   url: vFileApprovePath,		   
			   data: {
				   "_token": "{{ csrf_token() }}",			  
					"item_id": pmItemID,
					"checklist_type": pmChecklist,
					"document_type" : pmdocument
			   },
			   success: function( msg ) {
						window.location.href = '{{ url("/mythesis/detail?") }}'+pmItemID+'&action=ac&tab=4';
			   }
		   });
		}
		else {
			swal("", "Please select the check box to approve the file!", "error").then((result) => {
			  $("#"+vElementName).focus();
			});
		}
	}

	function funAllowFilesReSubmission(pmdocument,pmItemID,pmChecklist) {
		var vElementName = pmdocument+'_file_edit';				
		if($("#"+vElementName).prop("checked") == true){
			var vFileApprovePath = '{{ url("/mythesis/approve-termfile") }}';		
			$.ajax({
			   type: 'POST',
			   url: vFileApprovePath,		   
			   data: {
				   "_token": "{{ csrf_token() }}",			  
						"item_id": pmItemID,
						"checklist_type": pmChecklist,
						"document_type" : pmdocument,
						"edit_flag": 1
				  },
			   	success: function( msg ) {
					window.location.href = '{{ url("/mythesis/detail?") }}'+pmItemID+'&action=ac&tab=4';
			   }
		  });
		}
		else {
			swal("", "Please select the check box to allow the student for file resubmission!", "error").then((result) => {
			  $("#"+vElementName).focus();
			});
		}
	}
  </script>
@endpush