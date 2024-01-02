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
									$vExpand = "true";
									@endphp								
								@else
									@php
									$vExpand = "false";
									@endphp
								@endif
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="{{$vExpand}}" aria-controls="collapseOne" class="text-center font-weight-bold collapsed">														
								Coming Soon....															
								</a>
							</h5>
						</div>
						@if($requestdetails[0]->progress_completion == 0)
							<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion"></div>
						@else
							<div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion"></div>
						@endif                
					</div>
			  </div>        
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
		if($("#submmission_status").val() != "") {
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3 || (userRoleID == 2 && vQryAction == 'ac')) {
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
					else if($("#minutes1").val() == "" && $("#minutes1_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 1 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes2").val() == "" && $("#minutes2_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 2 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes3").val() == "" && $("#minutes3_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 3 file(s)",
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
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				else {
					if($('#presentationrubric').val() == "") {						
						swal("", "Please choose presentaion rubric file!", "error").then((result) => {
						  $('#presentationrubric').focus();
						});	
					}
					else if($('#finalreportdraft1').val() == "") {						
						swal("", "Please choose final report draft 1 file!", "error").then((result) => {
						  $('#finalreportdraft1').focus();
						});	
					}
					else if($('#finalreportdraft1rubric').val() == "") {						
						swal("", "Please choose final report draft 1 rubric file!", "error").then((result) => {
						  $('#finalreportdraft1rubric').focus();
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
		if($("#submmission_status").val() == 1) {
			if(userRoleID == 3 || (userRoleID == 2 && vQryAction == 'ac')) {					
				if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
					swal({
						title: "Please choose presentaion file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($("#minutes1").val() == "" && $("#minutes1_flag").val() == 0) {
					swal({
						title: "Please choose meeting minutes 1 file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($("#minutes2").val() == "" && $("#minutes2_flag").val() == 0) {
					swal({
						title: "Please choose meeting minutes 2 file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($("#minutes3").val() == "" && $("#minutes3_flag").val() == 0) {
					swal({
						title: "Please choose meeting minutes 3 file(s)",
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
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else {
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
		else {			
			$("#statsupdate_comments").val(1);
			document.frmCommentUpdate.method='POST';
			document.frmCommentUpdate.action=vProgressPath;
			document.frmCommentUpdate.submit();
		}		
	}

	
	function funTerm3SubmmitValidate() {		
		var userRoleID = {{auth()->user()->role_id}};
		var vQryAction = '{{ request()->get('action') }}';
		var vItemAssigned = {{ $item->assigned_to }};

		if($("#submmission_status").val() != "") {
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3 || (userRoleID == 2 && vQryAction == 'ac')) {
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
					else if($("#minutes1").val() == "" && $("#minutes1_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 1 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes2").val() == "" && $("#minutes2_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 2 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes3").val() == "" && $("#minutes3_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 3 file(s)",
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
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
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
				$("#divPresentationRubric").show();
				$("#divFinalReportDraft1").show();
				$("#divFinalReportDraft1Rubric").show();
			}
			else if(parseInt(optValue)== 2) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
			else {
				$("#divCompletionMessage").hide();
				$("#divPresentationRubric").hide();
				$("#divFinalReportDraft1").hide();
				$("#divFinalReportDraft1Rubric").hide();
			}
		}
		else {
			if(parseInt(optValue)== 1) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").show();
				$("#divFinalReportRubric").show();
				$("#divFinalReportDraft1").show();
				$("#divFinalReportDraft1Rubric").show();
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
  </script>
@endpush