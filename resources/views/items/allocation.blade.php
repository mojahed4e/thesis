@extends('layouts.app', ['activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Listing')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
		  <form method="post" enctype="multipart/form-data" name="frmRequestUpdate" id="frmRequestUpdate" action="{{ route('item.update-request', $item) }}" autocomplete="off" class="form-horizontal">
            @csrf			
            @method('post')
            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Thesis Allocation Request') }}</h4>
              </div>
              <div class="card-body ">                                               
				<div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Prefered Supervisor') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('supervisor_id') ? ' has-danger' : '' }}">
                        <select class="selectpicker col-sm-12 pl-0 pr-0" id="supervisor_id" name="supervisor_id" data-style="select-with-transition" title="" data-size="100" {{ ($item->user_role_id == 3) ? 'disabled' : '' }}>
                        <option value="">-</option>
                        @foreach ($supervisors as $supervisor)
                        @if($item->created_by == $supervisor->id)
                        	<option value="{{ $supervisor->id }}" {{ 'selected' }}>{{ $supervisor->name }}</option>
                        @else
                        	<option value="{{ $supervisor->id }}" {{ $supervisor->id == old('supervisor_id') ? 'selected' : '' }}>{{ $supervisor->name }}</option>
                        @endif
                        @endforeach
                      </select>
                      @include('alerts.feedback', ['field' => 'supervisor_id'])
                    </div>
                  </div>
                </div>
                <!--<div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Group Members') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('student_id') ? ' has-danger' : '' }}">
						<div class="dropdown show-tick">
							<select class="selectpicker col-sm-12 pl-0 pr-0" id="student_id" name="student_id[]" data-style="select-with-transition" onchange="funLimitSelection()" multiple title="-" data-size="4">
							@foreach ($students as $student)
								@if(auth()->user()->id != $student->id)
									<option value="{{ $student->id }}" {{ $student->id == old('student_id') ? 'selected' : '' }}>{{ $student->name }}&nbsp;&nbsp;(&nbsp;{{ $student->student_id }}&nbsp;)</option>
								@endif
							@endforeach
						  </select><br/>
						  <label class="form_chg">{{ __('( Maximum 2 group members allowed.)') }}</label>
						</div>
                      @include('alerts.feedback', ['field' => 'student_id'])
                    </div>
                  </div>				  
                </div>		-->		
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Request Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('description') ? ' has-danger' : '' }}">
                      <textarea name="description" id="input-description" cols="35" rows="15" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description about the Thesis request selection') }}" value="{{ old('description') }}">{{ old('description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'description'])
                    </div>
                  </div>
                </div>
				<div class="row">
				  <label class="col-sm-3 col-form-label form_chg">{{ __('Attach Files') }}</label>
				  <div class="col-sm-7">
					<div id="fileuploader" style="line-height: 25px;">Select Files</div>
				  </div>
				</div>
              </div>
              <div class="card-footer ml-auto mr-auto">
               <input type="hidden" name="update_request" id="update_request" value="0" />
				<input type="hidden" name="track_id" id="track_id" value="0" />
				<input type="hidden" name="item_id" id="item_id" value="{{$item->id}}" />
				<button type="button" onclick="funRequestValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit Request') }}</button>								
				<a href="{{ route('item.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script>
  $.fn.selectpicker.Constructor.DEFAULTS.maxOptions = 2;
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
		selector: '#input-description',
		plugins: 'preview advlist',		
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
	vPath = '{!! $upload !!}';
	extraObj = $("#fileuploader").uploadFile({
		url:vPath,
		fileName:"thesisfiles",
		method:'POST',
		dragDropStr: "",
		dragDrop: true,
		statusBarWidth:"100%",
		dragdropWidth:"50%",
		maxFileSize:1024*10000,
		uploadStr:"Select File(s)",
		autoSubmit:false,		
		dynamicFormData: function()
		{			
			var data ={
						"_token": "{{ csrf_token() }}",
						"track_id": $("#track_id").val(),
						"description": $("#input-description").val(),
						"supervisor_id": $("#supervisor_id").val(),
						"student_id": $("#student_id").val() 						
					};
			return data;        
		},
		onSuccess:function(files,data,xhr,pd)
		{
			$("#track_id").val(data['track_id']);			
		
		},
		afterUploadAll: function(obj)
		{
			swal({
				  title: 'Requested updated successfully!',
				  text: '',
				  type: 'success',
				  confirmButtonColor: '#47a44b'
			}).then ((result) =>{
				$("#update_request").val(1);
				document.frmRequestUpdate.method='post'
				document.frmRequestUpdate.submit();
			})
		}	
	})
  });
	function funRequestValidate() {
		if($('#supervisor_id').val() == "") {
			swal("", "Please select prefered supervisor", "error").then((result) => {
				$('#supervisor_id').focus();
			});				
		}
		/*else if($("#student_id").val() == "") {
			swal("", "Please select group member(s)", "error").then((result) => {
				$('#student_id').focus();
			});
		}*/
		else if($('#input-description').val() == "" && extraObj.fileCounter == 1) {
			swal("", "Please enter request description", "error").then((result) => {
				tinymce.EditorManager.get('input-description').focus();
			});
		}
		else if($('#input-description').val() != "" && extraObj.fileCounter == 1) {
			swal({
				  title: 'Request updated successfully!',
				  text: '',
				  type: 'success',
				  confirmButtonColor: '#47a44b'
			}).then ((result) =>{
				$("#update_request").val(1);
				$("#supervisor_id").removeAttr("disabled");
				document.frmRequestUpdate.method='post'
				document.frmRequestUpdate.submit();
			})				
		}
		else if(extraObj.fileCounter != 1)  {
			extraObj.startUpload();
		}
	}
</script>
@endpush