@extends('layouts.app', ['activePage' => 'mythesis', 'menuParent' => 'laravel', 'titlePage' => __('My Thesis')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
		  <form method="post" enctype="multipart/form-data" action="{{ route('item.detail', $item) }}" autocomplete="off" class="form-horizontal">
            @csrf			
            @method('put')
            <div class="card ">
              <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">filter_none</i>
                </div>
                <h4 class="card-title">{{ __('Thesis Allocation Request') }}</h4>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">					  
                      <a href="{{ route('item.detail', [$item->id]) }}" class="btn btn-sm btn-rose">{{ __('Back to details') }}</a>
                  </div>
                </div>
				<div class="row">
					<div class="col-md-12 text-right">
						<h1 style="text-align: center;"><span style="color: #236fa1;"><strong>COMING SOON</strong></span></h1>
					</div>
				</div>
              </div>
              <div class="card-footer ml-auto mr-auto">
				<a href="#" class="btn btn-rose">{{ __('Submit Request') }}</a>
				<a href="{{ route('item.detail', [$item->id]) }}" class="btn btn-rose">{{ __('Cancel') }}</a>
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