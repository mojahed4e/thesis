@extends('layouts.app', ['activePage' => 'template-folder', 'menuParent' => 'laravel', 'titlePage' => __('Manage Document Files')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Document Files') }}</h4>
              </div>
              <div class="card-body">
                  <form action="{{ route('templates.delete-folder-files',['type=ffd']) }}" method="post" name="frmDeleteFolder" id="frmDeleteFolder">
                  <input type="hidden" name="folder_id" id="folder_id" value="">
                  <input type="hidden" name="subfolder_id" id="subfolder_id" value="">
                  <input type="hidden" name="file_id" id="file_id" value="">
                  @csrf
                  @method('post')                
                    <div class="row">
                      <div class="col-12 text-right">                       
                        <a href="{{ route('templates.view-folders-files',['folder_id='.request()->get('folder_id').'&type=sf']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">arrow_back_ios</i>&nbsp;&nbsp;{{ __('Back To Sub Folder Listing') }}</a>
                        @can('create', App\Category::class)
                          @if(auth()->user()->manager_flag != 2)
                            <a href="{{ route('templates.create-folder-files',['folder_id='.request()->get('folder_id').'&subfolder_id='.request()->get('subfolder_id').'&type=uf']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">folder</i>&nbsp;&nbsp;{{ __('Add New File') }}</a>
                          @endif
                        @endcan
                      </div>
                    </div>
                  
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover datatable-rose" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('S.No') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('File Name') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('File Description') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Download') }}
                      </th>                                            
                      @can('manage-special-items', App\User::class)
                        <th class="view_word text-right" style="font-weight:bold;">
                          {{ __('Actions') }}
                        </th>
                      @endcan
                    </thead>
                    <tbody class="cht_text">
                      @php
                      $vSeqNo = 1;
                      @endphp
                      @foreach($templatefiles as $files)
                        <tr>
                          <td>
                            {{ $vSeqNo }}
                          </td>
                          <td>
                            {{ $files->file_name }}
                          </td>
                          <td>
                            {{ $files->file_description }}
                          </td>
                          <td>                            
                        <a href="{{ route('download.viewfile', [$files->file_id,'type=templete']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;"><i class="material-icons" style="font-size: 2rem;">download</i></a>
                          </td>                          
                          @can('manage-special-items', App\User::class)
                            <td class="td-actions text-right">                              
                                @can('manage-special-items', App\User::class)
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('templates.create-folder-files', ['folder_id='.$files->folder_id.'&file_id='.$files->file_id.'&type=ef']) }}" data-original-title="" title="">
                                    <div class="icon_siz"><i class="far fa-edit"></i></div>
                                    <div class="ripple-container"></div>
                                  </a>
                                @endcan
                                 @if (auth()->user()->role_id < 3 && auth()->user()->manager_flag != 2)
                                  <button type="button" class="btn btn-link" data-original-title="" title="" onclick="funDeleteFile({{$files->folder_id}},{{$files->subfolder_id}},{{$files->file_id}})">,
                                      <i class="material-icons" style="font-size: 2rem;">cancel</i>
                                      <div class="ripple-container"></div>
                                  </button>
                                @endif
                              </form>
                            </td>
                          @endcan
                        </tr>
                        @php
                          $vSeqNo++;
                        @endphp
                      @endforeach
                    </tbody>
                  </table>
                </div>
                </form>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('js')
  <script>
    $(document).ready(function() {
      $('#datatables').fadeIn(1100);
      $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [15, 25, 50, -1],
          [15, 25, 50, "All"]
        ],
        responsive: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search Files",
        },
        "columnDefs": [
          { "orderable": false, "targets": 2 },
        ],
      });
    });
    function funDeleteFile(pmFolderID,pmSubFolderID,pmFileID) {
      swal({
        title: 'Are you sure to delete this file?',
        text: "You won't be able to revert this back!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#47a44b',
        cancelButtonColor: '#ea2c6d',       
        confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {        
          swal({
            title: 'Deleted!',
            text: 'Your have deleted the file successfully.',
            type: 'success',
            confirmButtonColor: '#47a44b'
          }).then ((result) =>{
            document.frmDeleteFolder.method='POST';
            $("#folder_id").val(pmFolderID);
            $("#subfolder_id").val(pmSubFolderID);
            $("#file_id").val(pmFileID);
            document.frmDeleteFolder.submit();
          })        
        }
      })
    }
  </script>
@endpush