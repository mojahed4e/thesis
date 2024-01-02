@php
	$header = array('activePage' => 'item-allotted', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Archive'));
	$pagetitle = 'Thesis Archive';
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
              	@php								
								$group_member = 0;									
								@endphp	
                @if(auth()->user()->role_id == 4)                	
                	@if(count($studentitem) == 0)
	                  <div class="row">
	                    <div class="col-12 text-right">
	                      <a href="{{ route('item.student-thesis') }}" class="btn bt_styl btn_txtbold"><i class="fas fa-sliders-h pr-2"></i>{{ __('Add Thesis') }}</a>
	                    </div>
	                  </div>
	                @endif
                @endif
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" style="display:none">
                    <thead class="text-primary">                    	
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Thesis Title') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Project By') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Supervisor') }}
                      </th>					  					                      			  
	                    <th class="view_word" style="font-weight:bold;">
                        {{ __('Cohort') }}
                      </th>
					  					<th class="view_word" style="font-weight:bold;">
                        {{ __('Keywords') }}
                      </th>                    				      
                    </thead>
                    <tbody class="cht_text">                    	
											@foreach($items as $item)						
                        <tr>                        	
                          <td>
                            {{ $item->name }}
                          </td>
                          <td>
                          @php
			                      $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->created_by])->get();
			                    @endphp                      
			                    @if(count($aUserInfo) > 0)
			                      {{ $aUserInfo[0]->name }}
			                    @endif
                          </td>
                          <td>
                          @php
                          	$tagSeq = 0;
			                      $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->assigned_to])->get();
			                    @endphp                      
			                    @if(count($aUserInfo) > 0)
			                      {{ $aUserInfo[0]->name }}
			                    @else
                            	--
			                    @endif
                          </td>						  						                          					 
	                        <td>
	                        	@if(!empty($item->term))
                            	{{ $item->term->name }}
                            @else
                            	--
                            @endif
                          </td>
                          <td>
                          @php
                          	$itemtags = Illuminate\Support\Facades\DB::table('item_tag')->select('tags.*')
            															->join('tags','tags.id','=','item_tag.tag_id')
            															->where(['item_id' => $item->id,'item_tag.status' => 1])->get();
                      			$tagSeq = 0;
                      		@endphp
													@if(count($itemtags) > 0) 
														@foreach($itemtags as $keyword)                    	
                        		    @if($tagSeq == 0)
	                                {{ $keyword->name }}
	                            @else
                            		,<br/>{{ $keyword->name }}
                            	@endif
                          		@php
                          			$tagSeq++;
                          		@endphp
                          	@endforeach
                          @endif 
                          </td>			  
                        </tr>
                      @endforeach
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
    	vUserRoleID = {{ auth()->user()->role_id}};
    	if(vUserRoleID	== 4)
    		vTargets = 4;
    	else
    		vTargets = 6;
		$('#datatables').fadeIn(1100);
    table = $('#datatables').removeAttr('width').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [20, 40, 100, -1],
          [20, 40, 100, "All"]
        ],
    responsive: true,
		fixedColumns: false,
		bAutoWidth: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search thesis",
        },
        "columnDefs": [
          { "orderable": false, "targets": vTargets },	
          //{ "width": 400, "targets":0},	 
        ],        
      });



   })

    function funFilterTableData(pmValue,pmID) {       
    	if(pmValue != 0){
    		$('#datatables')
        .DataTable()
        .column(pmID)
        .search(
            pmValue            
        )
        .draw();
    	}
    	else {    		
    		$('#datatables')
    		.DataTable()
        .column(pmID)
        .search("")
        .draw();
    	}
    }

    function funResetFilters() {    	
    	$('#selCohort').val(0).trigger( "change" );
    	$('#selProgram').val(0).trigger( "change" );
    	$('#selSupervisor').val(0).trigger( "change" );
    	$('input.form-control.form-control-sm').val('');
    	$('#datatables').DataTable().search("").draw();
    }
  </script>
@endpush