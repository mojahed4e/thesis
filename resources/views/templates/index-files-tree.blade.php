@extends('layouts.app', ['activePage' => 'template-folder', 'menuParent' => 'laravel', 'titlePage' => __('View/Download Document Files')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('') }}</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <lable class="col-sm-10 form_chg" style="padding-left:50px;padding-bottom: 20px;">{{ __('Please click the folder name to view/download file(s)') }}</lable>
                </div>
                <div class="row">
                  <ul id="tree" class="collapse show">
                    @if(count($templatefolders) > 0)
                      @php   
                      $aFolder = isset($templatefolders['folder'])?$templatefolders['folder']:array();
                      $aSubFolder = isset($templatefolders['subfolders']) ? $templatefolders['subfolders'] : array();
                      $aFiles = isset($templatefolders['subfolders']['files']) ? $templatefolders['subfolders']['files'] : array();
                      @endphp
                      @for($folder_loop = 0; $folder_loop < count($templatefolders['folder']['name']); $folder_loop++)
                        <li class="nav-item" style="line-height:50px; list-style: none;"><i class="material-icons" style="color:#FFCC00;">folder</i><span class="sidebar-normal" style="vertical-align: super;">&nbsp;&nbsp;<a href="#"  onclick="funShowSubFolders({{$aFolder['id'][$folder_loop]}})" style="font-family: NewJuneRegular;font-size: 16px;color: rgba(61,68,101,1);">{{  $aFolder['name'][$folder_loop] }}</span>
                          @if(!empty($templatefolders['subfolders']['names'][$templatefolders['folder']['id'][$folder_loop]]))</a>
                          <ul class="collapse" id="subfolder{{$aFolder['id'][$folder_loop]}}">
                            @for($subfolder_loop = 0; $subfolder_loop < count($aSubFolder['names'][$aFolder['id'][$folder_loop]]); $subfolder_loop++)
                              <li class="nav-item" style="line-height:50px; list-style: none;"><i class="material-icons" style="color:#FFCC00;">folder</i><span class="sidebar-normal" style="vertical-align: super;">&nbsp;&nbsp;<a href="#" style="font-family: NewJuneRegular;font-size: 16px;color: rgba(61,68,101,1);" onclick="funShowSubFolderFiles({{$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]}})">{{ $aSubFolder['names'][$aFolder['id'][$folder_loop]][$subfolder_loop] }}</a></span>
                                
                                @if(!empty($aFiles[$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]]['files_name']))
                                  <ul class="collapse" id="files{{$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]}}">
                                   @for($files_loop =0; $files_loop < count($aFiles[$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]]['files_name']);$files_loop++ )
                                      <li class="nav-item" style="line-height:50px; list-style: none;"><i class="material-icons">article</i><span class="sidebar-normal" style="vertical-align: super;">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$aFiles[$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]]['files_id'][$files_loop],'type=templete']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;" title="Download {{ $aFiles[$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]]['files_name'][$files_loop] }} File">{{ $aFiles[$aSubFolder['id'][$aFolder['id'][$folder_loop]][$subfolder_loop]]['files_name'][$files_loop] }}&nbsp;&nbsp;<i class="material-icons" style="font-size: 2rem;">download</i></a></span></li>
                                   @endfor
                                 </ul>
                                @endif
                              </li>
                            @endfor                            
                          </ul>
                          @endif
                        </li>
                      @endfor                      
                    @endif
                  </ul>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('js')

  <script src="{{ asset('material') }}/treeexplr/jstree.min.js"></script> 
  <script>    
    function funShowSubFolders(elementID){                    
        if($('#subfolder'+elementID).css('display') != 'none'){
          $('#subfolder'+elementID).css('display','none');
          $('#subfolder'+elementID+' li ul').css('display','none');
        }
        else {
          $('#subfolder'+elementID).css('display','block'); 
        }      
    }
    function funShowSubFolderFiles(elementID){            
        if($('#files'+elementID).css('display') != 'none'){
          $('#files'+elementID).css('display','none');
        }
        else {
          $('#files'+elementID).css('display','block'); 
        }      
    }
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
      $(".folder").click(function(){
        $(this).find("ul").css("display", "block");
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