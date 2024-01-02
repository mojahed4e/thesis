@if(auth()->user()->role_id == 4)
	@php
	$header = array('activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Listing'));
	$pagetitle = 'Thesis Listing';
	@endphp
@else
	@php
	$header = array('activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Management'));
	$pagetitle = 'Thesis Management';
	@endphp
@endif

@extends('layouts.app',  $header )

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
		  <form method="post" enctype="multipart/form-data" action="{{ route('item.allocation', $item) }}" autocomplete="off" class="form-horizontal">
            @csrf			
            @method('put')
            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Thesis Details') }}</h4>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
						@if($requested == 0)
							<a href="{{ route('item.allocation', [$item->id]) }}" class="btn bt_styl btn_txtbold">{{ __('Request For Allocation') }}</a>
						@endif
                  </div>
                </div>
				
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Title') }}</label>
                  <div class="col-sm-7">
					<div class="card-title" style="padding: 17px 5px 0 0;">{{ $item->name }}</div>                    
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Project By') }}</label>
                  <div class="col-sm-7">
                  <div class="card-title" style="padding: 17px 5px 0 0;">
                    @php
                      $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->created_by])->get();
                    @endphp                      
                    @if(count($aUserInfo) > 0)
                      {{ $aUserInfo[0]->name }}
                    @endif 
                  </div>                    
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Term') }}</label>
                  <div class="col-sm-7">					
					@foreach ($terms as $term)
						@if($term->id ==  $item->term_id)
						<div class="card-title" style="padding: 17px 5px 0 0;">{{ $term->name }}</div>
            @else
            <div class="card-title" style="padding: 17px 5px 0 0;">--</div>
						@endif
					@endforeach					
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Category') }}</label>
                  <div class="col-sm-7">				  
					@foreach ($categories as $category)
						@if($category->id ==  $item->category_id)
						<div class="card-title" style="padding: 17px 5px 0 0;">{{ $category->name }}</div>
						@endif
					@endforeach                    
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
					<div class="card-title" style="padding: 17px 5px 0 0;">{!! $item->description !!}</div>                       
                  </div>
                </div>                                              
              </div>
              <div class="card-footer ml-auto mr-auto">
			    @if($requested == 0)
					<a href="{{ route('item.allocation', [$item->id]) }}" class="btn bt_styl btn_txtbold">{{ __('Request For Allocation') }}</a>
				@else
					<a href="{{ route('mythesis.detail') }}" class="btn bt_styl btn_txtbold">{{ __('View My Thesis') }}</a>
				@endif
				<a href="{{ route('item.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
			  <div  class="pt-5 pb-5 text-center">				
					<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>				
				</div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
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
		selector: '#input-description',
		plugins: 'preview advlist codesamples'
		/*codesample_languages: [
			{ text: 'HTML/XML', value: 'markup' },
			{ text: 'JavaScript', value: 'javascript' },
			{ text: 'CSS', value: 'css' }			
		],
		toolbar: 'codesample'*/
	});	
  });
</script>
@endpush