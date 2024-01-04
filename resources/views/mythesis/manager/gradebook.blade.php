@php
$header = array('activePage' => 'grade-book', 'menuParent' => 'laravel', 'titlePage' => __('Grade Book'));
$pagetitle = 'Grade Book';
@endphp
@extends('layouts.app',  $header )
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __($pagetitle) }}</h4>
              </div>
              <div class="card-body">	 
              	<form method="post" enctype="multipart/form-data" name="frmCommentUpdate" id="frmCommentUpdate" action="" autocomplete="off" class="form-horizontal">
              	<input type="hidden" name="rubricterm" id="rubricterm" value="">
								<input type="hidden" name="rubrictype" id="rubrictype" value="">
								<input type="hidden" name="item_id" id="item_id" value=""> 
              	@csrf					
								@method('post')   							         		  
                @can('create', App\Item::class)                	
                	@if(auth()->user()->manager_flag != 2)
                		<div class="row">
                					<div class="col-1 form_chg text-right pt-2">														
															<p>{{ __('Filters') }}:</p>														
													</div>											
													<div class="col-2 form_chg text-left">
															<span>
																<select class="form-control selectpicker" data-style="btn btn-link" id="selCohort" onchange="funFilterTableData(this.value,0)">
														      <option value="0">Show All</option>
														      @if(count($cohorts) > 0)
															      @foreach ($cohorts as $cohort)
						                         	<option value="{{ $cohort->name }}" {{ $cohort->id == old('selCohort') ? 'selected' : '' }}>{{ $cohort->name }}</option>
						                        @endforeach
						                      @endif
												    		</select>
												    	</span>
													</div>
													<div class="col-2 form_chg text-left">
															<span>
																<select class="form-control selectpicker" data-style="btn btn-link" id="selProgram" onchange="funFilterTableData(this.value,1)">
														      <option value="0">Show All</option>
														      @if(count($programs) > 0)
															      @foreach ($programs as $program)
						                         	<option value="{{ $program->name }}" {{ $program->id == old('selProgram') ? 'selected' : '' }}>{{ $program->name }}</option>
						                        @endforeach
						                      @endif
												    		</select>
												    	</span>
													</div>
													<div class="col-2 form_chg text-left">
														<span>
															<a href="javascript:void(0)" onclick="funResetFilters()" class="btn bt_styl btn_txtbold" style="width:140px; height:35px;font-size: 12px;">{{ __('Reset Filters') }}</a>
											    	</span>
												</div>													
										</div>	                 
	                @endif
                @endcan                 
                <div class="table-responsive">
                  <table id="gradedatatables" class="table table-striped table-no-bordered table-hover" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Cohort') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Program') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Student ID') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Student') }}<br/>{{ __('Name') }}
                      </th>					  					
                      <th class="view_word" style="font-weight:bold;">
	                        {{ __('Supervisor') }}<br/>{{ __('Name') }}
	                    </th>			                   	
	                    <th class="view_word" style="font-weight:bold; background-color: #a8b3af;">
                        {{ __('Term - I') }}<br/>{{ __('Chapter - I') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #a8b3af;">
                        {{ __('Term - I') }}<br/>{{ __('Chapter - II') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #a8b3af;">
                        {{ __('Term - I') }}<br/>{{ __('Presentation') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #a8b3af;">
                        {{ __('Term - I') }}<br/>{{ __('Overall Score') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #a8b3af;">
                        {{ __('Term - I') }}<br/>{{ __('Points Grad') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #a8b3af;">
                        {{ __('Term - I') }}<br/>{{ __('Letter Grade') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #9d9e9e;">
                        {{ __('Term - II') }}<br/>{{ __('Final Report') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #9d9e9e;">
                        {{ __('Term - II') }}<br/>{{ __('Presentation') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #9d9e9e;">
                        {{ __('Term - II') }}<br/>{{ __('Overall Score') }}
                      </th>                      
                      <th class="view_word" style="font-weight:bold; background-color: #9d9e9e;">
                        {{ __('Term - II') }}<br/>{{ __('Points Grade') }}
                      </th>
                      <th class="view_word" style="font-weight:bold; background-color: #9d9e9e;">
                        {{ __('Term - II') }}<br/>{{ __('Letter Grade') }}
                      </th>
                    </thead>                     
                    <tbody class="cht_text">
                    	@if(count($gradebookset) > 0)                    	
                    		@for($cohort_loop = 0; $cohort_loop < count($cohorts); $cohort_loop++)
                    			@if(!empty($gradebookset[$cohorts[$cohort_loop]->id]))
                    				@for($suer_loop = 0; $suer_loop < count($gradebookset[$cohorts[$cohort_loop]->id]['user_data']); $suer_loop++)
                    					<tr>
                    							<td>
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->term_name ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->term_name : '--'}}
                    							</td>
                    							<td>
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->program_name ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->program_name : '--'}}
                    							</td>
                    							<td>
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id : '--'}}
                    							</td>
                    							<td>
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_name ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_name : '--'}}
                    							</td>
                    							<td>
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->supervisor_name ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->supervisor_name : '--'}}
                    							</td>
                    							<td style="background-color:#d7f7f6;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['chapter1_score'] ? 
                    								number_format((float)$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['chapter1_score'], 2, ".", "") : '--'}}
                    							</td>
                    							<td style="background-color:#d7f7f6;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['chapter2_overallscore'] ? 
                    								number_format((float)$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['chapter2_overallscore'], 2, ".", "") : '--'}}
                    							</td>
                    							<td style="background-color:#d7f7f6;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['presentation_overallscore'] ? 
                    								number_format((float)$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['presentation_overallscore'], 2, ".", "") : '--'}}
                    							</td>
                    							<td style="background-color:#c5f7be; font-weight: bold;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['overallaggregate_score'] ? 
                    								number_format((float)$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['overallaggregate_score'], 2, ".", "") : '--'}}
                    							</td>
                    							<td style="background-color:#c5f7be;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['points_grade'] ? 
                    								number_format((float)$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['points_grade'], 2, ".", "") : '--'}}
                    							</td>
                    							<td style="background-color:#c5f7be; font-weight: bold;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['letter_grade'] ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['letter_grade'] : '--'}}
                    							</td>
                    							<td style="background-color:#d7f7f6;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_final_overallscore'] ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_final_overallscore'] : '--'}}
                    							</td>
                    							<td style="background-color:#d7f7f6;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_presentation_overallscore'] ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_presentation_overallscore'] : '--'}}
                    							</td>
                    							<td style="background-color:#c5f7be; font-weight: bold;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_overallaggregate_score'] ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_overallaggregate_score'] : '--'}}
                    							</td>
                    							<td style="background-color:#c5f7be;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_points_grade'] ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_points_grade'] : '--'}}
                    							</td>
                    							<td style="background-color:#c5f7be; font-weight: bold;">
                    								{{ $gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_letter_grade'] ? 
                    								$gradebookset[$cohorts[$cohort_loop]->id][$gradebookset[$cohorts[$cohort_loop]->id]['user_data'][$suer_loop]->student_id]['term2_letter_grade'] : '--'}}
                    							</td>
                    					</tr>
                    				@endfor
                    			@endif
                    		@endfor
                    	@endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection
<!--
<style type="text/css">
table {
  table-layout:fixed;
}
table td {
  word-wrap: break-word;
  max-width: 50px;
}
#datatables td {
  white-space:initial;
}
</style>
-->
@push('js')  
  <script>
  	var table = "";
    $(document).ready(function() {    	
    vTargets = [5,6,7,8,9,10,11,12,13,14,15];
		$('#gradedatatables').fadeIn(1100);
    table = $('#gradedatatables').removeAttr('width').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 100, -1],
          [10, 25, 100, "All"]
        ],
        responsive: false,
    		fixedColumns: false,
    		bAutoWidth: true,
        order: [[3, 'asc']],
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search grades",
        },
        "columnDefs": [
          { "orderable": false, "targets": vTargets },	
          //{ "width": 400, "targets":0},	 
        ],        
      });
   })

    function funFilterTableData(pmValue,pmID) {      	   
    	if(pmValue != 0){
    		$('#gradedatatables')
        .DataTable()
        .column(pmID)
        .search(
            pmValue            
        )
        .draw();
    	}
    	else {    		
    		$('#gradedatatables')
    		.DataTable()
        .column(pmID)
        .search("")
        .draw();
    	}
    }

    function funResetFilters() {    	
    	$('#selCohort').val(0).trigger( "change" );
    	$('#selProgram').val(0).trigger( "change" );    	
    	$('input.form-control.form-control-sm').val('');
    	$('#gradedatatables').DataTable().search("").draw();
    }
  </script>
@endpush