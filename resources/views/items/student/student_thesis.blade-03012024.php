@extends('layouts.app', ['activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Listing')])

@section('content')
<link href="{{ asset('material') }}/css/student-thesis.css" rel="stylesheet" >
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" name="frmRequestUpdate" id="frmRequestUpdate" enctype="multipart/form-data" action="{{ route('item.addstudent-thesis') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')
            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Add Thesis') }}</h4>
              </div>
              <div class="card-body ">                             
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Title') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" type="text" placeholder="{{ __('Thesis Title') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'name'])
                    </div>
                  </div>
                </div>				       
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Prefered Supervisor') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('supervisor_id') ? ' has-danger' : '' }}">
                        <select class="selectpicker col-sm-12 pl-0 pr-0" id="supervisor_id" name="supervisor_id" data-style="select-with-transition" title="" data-size="100">
                        <option value="">-</option>                        
                        @foreach ($supervisors as $supervisor)
                          @php
                          $aProgramAvailability = explode(',',$supervisor->program_availability);
                          $aInprogressItems = \App\Item::select('items.*')
                                ->join('thesis_request_details', 'thesis_request_details.id','=', 'items.request_detail_id')
                                ->where([['items.status','>',0] , ['thesis_request_details.progress_completion','<',3],['thesis_request_details.supervisor','=',$supervisor->id]])->get();
                          @endphp
                          @if(in_array(auth()->user()->program_id,$aProgramAvailability) && count($aInprogressItems) < config('items.no_of_thesis.totalallotment'))
                            <option value="{{ $supervisor->id }}" {{ $supervisor->id == old('supervisor_id') ? 'selected' : '' }}>{{ $supervisor->name }}</option>
                          @endif
                        @endforeach
                      </select>
                      @include('alerts.feedback', ['field' => 'supervisor_id'])
                    </div>
                  </div>
                </div>                
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Keywords') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('tags') ? ' has-danger' : '' }}">
                      <div class="dropdown show-tick">
                        <select class="selectpicker col-sm-8 pl-0 pr-0" id="tags" name="tags[]" data-style="select-with-transition" onchange="funLimitSelection()" multiple title="-" data-size="4" data-placeholder="Select multiple options" data-live-search="true">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ $tag->id == old('tags') ? 'selected' : '' }}>
                              {!! $tag->name !!}
                            </option>
                        @endforeach
                        </select><span class="col-sm-4">&nbsp;&nbsp;<a href="javascript:void(0)" onclick="funShowKeywordPopup()" class="btn bt_styl btn_txtbold" style="font-size:12px;padding: 8px 20px">{{ __('Add New Keyword') }}</a></span><br/>
                        <label class="form_chg">{{ __('( Maximum 4 keywords allowed )') }}</label>
                      </div>
                      @include('alerts.feedback', ['field' => 'tags'])
                    </div>
                  </div>          
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word  {{ $errors->has('description') ? ' has-danger' : '' }}">
                      <textarea name="description" id="input-description" cols="35" rows="15" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description') }}">{{ old('description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'description'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Aim') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word  {{ $errors->has('aim') ? ' has-danger' : '' }}">
                      <textarea name="aim" id="input-aim" cols="35" rows="10" class="form-control{{ $errors->has('aim') ? ' is-invalid' : '' }}" placeholder="{{ __('Aim') }}" value="{{ old('aim') }}">{{ old('aim') }}</textarea>
                      @include('alerts.feedback', ['field' => 'aim'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Objectives') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word  {{ $errors->has('objectives') ? ' has-danger' : '' }}">
                      <textarea name="objectives" id="input-objectives" cols="35" rows="10" class="form-control{{ $errors->has('objectives') ? ' is-invalid' : '' }}" placeholder="{{ __('Objectives') }}" value="{{ old('objectives') }}">{{ old('objectives') }}</textarea>
                      @include('alerts.feedback', ['field' => 'objectives'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Attach Files') }}</label>
                  <div class="col-sm-7">
                    <div id="fileuploader" style="line-height: 25px;">Select Files</div>
                  </div>
                </div> 
      				@if(auth()->user()->role_id != 3)
      					@can('manage-items', App\User::class)
      					<div class="row">
      					  <label class="col-sm-3 col-form-label form_chg">{{ __('Status') }}</label>
      					  <div class="col-sm-10 checkbox-radios">
      						@foreach (config('items.statuses') as $value => $status)
      						  <div class="form-check view_word">
      							<label class="form-check-label">
      							  <input name="status" class="form-check-input" id="{{ $value }}" value="{{ $value }}" type="radio" {{ old('status') == $value ? ' checked' : '' }}> {{ $status }}
      							  <span class="circle">
      								<span class="check"></span>
      							  </span>
      							</label>
      						  </div>
      						@endforeach
      					  </div>
      					</div>					
      					<input type="hidden"  name="date" id="date"
      					placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('date', now()->format('d-m-Y')) }}"/>						
      					@endcan
      				@else
      					<input name="status" id="status" value="0" type="hidden">
      					<input type="hidden"  name="date" id="date" value="{{ old('date', now()->format('d-m-Y')) }}"/>
      				@endif               
              </div>              
              <div class="card-footer ml-auto mr-auto">
                @if(auth()->user()->manager_flag != 2)
                  <button type="button" onclick="funValidateFormInput()" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button>
                  <input type="hidden" name="update_request" id="update_request" value="0" />
                  <input type="hidden" name="track_id" id="track_id" value="0" />                  
                @endif
				        <a href="{{ route('item.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- Modal content -->
<div id="modalclient" class="modal">
  <div class="modal-content">
    <div class="modal-header">    
    <h2>Add New Keyword</h2>
    </div>
    <div class="modal-body pt-3 pb-3">
      <div class="row">
        <label class="col-sm-3 col-form-label form_chg"><span class="mark">*</span>{{ __('Keyword') }}:</label>
        <div class="col-sm-7">
          <div class="form-group view_word {{ $errors->has('txtkeyword') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('txtkeyword') ? ' is-invalid' : '' }}" name="txtkeyword" id="txtkeyword" type="text" placeholder="{{ __('Keyword') }}" value="{{ old('txtkeyword') }}" required="true" aria-required="true"/>
            @include('alerts.feedback', ['field' => 'txtkeyword'])
            <div id='frm_front_txtkeyword_errorloc' class="error_strings" style="width:450px; font-size: 14px; color: red;"></div>
          </div>
        </div>
      </div>  
      <div class="row">
        <label class="col-sm-3 col-form-label form_chg"><span class="mark">&nbsp;</span>{{ __('Short Name') }}:</label>
        <div class="col-sm-7">
          <div class="form-group view_word {{ $errors->has('txtshortname') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('txtshortname') ? ' is-invalid' : '' }}" name="txtshortname" id="txtshortname" type="text" placeholder="{{ __('Short Name') }}" value="{{ old('txtshortname') }}" required="true" aria-required="true"/>
            @include('alerts.feedback', ['field' => 'txtshortname'])
            <div id='frm_front_txtshortname_errorloc' class="error_strings" style="width:450px; font-size: 14px; color: red;"></div>
          </div>
        </div>
      </div>    
      <div class="card-footer ml-auto mr-auto text-center">
          <div id='divErrorClientMessage' class="error_strings" style="width:auto; font-size: 14px; color: red;">&nbsp;</div>         
          <button type="button" onclick="funAddNewTag()" class="btn bt_styl btn_txtbold">{{ __('Add Keyword') }}</button>
          <input type="hidden" name="update_request" id="update_request" value="0" />
          <input type="hidden" name="track_id" id="track_id" value="0" />
        <a href="javascript:void(0)" onclick="funCancelClientPopup();" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
      </div>
    </div>
    <div class="modal-footer">
    &nbsp;
    </div>
  </div> 
</div>

@endsection

@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script>
  $.fn.selectpicker.Constructor.DEFAULTS.maxOptions = 4;
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
    tinymce.init({
      selector: '#input-aim',
      plugins: 'preview advlist',   
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      }
    });
    tinymce.init({
      selector: '#input-objectives',
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
              "aim": $("#input-aim").val(),
              "objectives": $("#input-objectives").val(),              
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
            title: 'Request updated successfully!',
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
  
  function funShowKeywordPopup() {   
    var selKerWords = $('#tags').val();      
    if(selKerWords.length < 4){                    
        var modalclient = document.getElementById("modalclient");
        modalclient.style.display = "block";
        $("#txtshortname").val('');
        $("#txtkeyword").val('');
        $("#frm_front_txtshortname_errorloc").html("");
        $("#frm_front_txtkeyword_errorloc").html("");
        $("#divErrorClientMessage").html('');
    }
    else {
        swal("", "Please remove anyone of the 4 selected Keyword(s) to add a new keyword", "error").then(function() {
          $('#tags').focus();
           return false;      
        }); 
    }
   
  }

  function funAddNewTag(){
    var retrun_string = true;
    var selKerWords = $('#tags').val();     
    if($("#txtkeyword").val() == "" || $.trim($("#txtkeyword").val()) == ""){
      $("#txtkeyword").val('');
      $("#frm_front_txtkeyword_errorloc").html("Please Enter a Keyword");
      $("#txtkeyword").focus();
      retrun_string = false;
    } 
    if(retrun_string == true) {
      $("#frm_front_txtkeyword_errorloc").html("");
      $("#frm_front_txtshortname_errorloc").html("");            
      $("#divErrorMessage").html("");     
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
      });    
      var formData = {
          action_type: 'addkeyword',
          name: escape($("#txtkeyword").val()),
          program_id: parseInt({{auth()->user()->program_id}}),
          created_by: parseInt({{auth()->user()->id}}),
          shortname: escape($("#txtshortname").val()),
      };      
      $.ajax({
        url: '{!! config('app.base_url') !!}/tag/addkeyword',
        type: 'POST',
        data:  formData,
        dataType: "json",
        success: function(jsonResponse, textStatus, jqXHR)
        {
          status = jsonResponse.status;             
          var vTagOptions = $('#tags');
          if(status == 1){           
             $('#tags').append("<option value='" + jsonResponse.insertID.id+ "' selected>" + jsonResponse.insertID.name + "</option>");
            selKerWords.push(jsonResponse.insertID.id);
            $('#tags').selectpicker('val', selKerWords);
            $("#tags").selectpicker("refresh");
            var modal = document.getElementById("modalclient");
            modal.style.display = "none";
           
          }
          else {
            $("#divErrorClientMessage").html(jsonResponse.message);           
          }         
        },
         error: function(jqXHR, textStatus, errorThrown)
         {
            window.location.href = '{{ url("/item/index") }}';
         }         
      });
    }
  }

  function funCancelClientPopup() {
    var modal = document.getElementById("modalclient");
    modal.style.display = "none";
    acSearchField.val('');
    clearAutoComplete();
  }


  function funValidateFormInput() {    
    if($('#name').val() == "") {
      swal("", "Please enter thesis title", "error").then((result) => {
        $('#name').focus();                      
        return false;
      });       
    }
    else if($('#supervisor_id').val() == "") {       
      swal("", "Please select prefered supervisor", "error").then((result) => {
        $('#supervisor_id').focus();
        return false;       
      });
      
    }
    else if($('#category_id').val() == "") {
      swal("", "Please select category", "error").then((result) => {
        $('#category_id').focus();
         return false;      
      }); 
     
    }
    else if($('#input-description').val() == "" && extraObj.fileCounter == 1) {
      swal("", "Please enter thesis description", "error").then((result) => {
        tinymce.EditorManager.get('input-description').focus();
         return false;
      });
     
    }
    else if($('#input-aim').val() == "" && extraObj.fileCounter == 1) {
      swal("", "Please enter thesis aim", "error").then((result) => {
        tinymce.EditorManager.get('input-aim').focus();
         return false;
      });
     
    }
    else if($('#input-objectives').val() == "" && extraObj.fileCounter == 1) {
      swal("", "Please enter thesis objectives", "error").then((result) => {
        tinymce.EditorManager.get('input-objectives').focus();
         return false;
      });
     
    }
    else if($('#input-description').val() != "" && $('#input-aim').val() != "" && $('#input-objectives').val() != "" && extraObj.fileCounter == 1) {
      swal({
          title: 'Thesis added successfully!',
          text: '',
          type: 'success',
          confirmButtonColor: '#47a44b'
      }).then ((result) =>{
        $("#update_request").val(1);
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