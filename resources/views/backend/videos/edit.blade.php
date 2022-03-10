@extends('backend.layouts.master')

@section('title')
Edit video
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#language').select2();
    $('.channels').select2();
</script>
<script type="text/javascript" src="{{ asset('backend/js/movies.js') }}"></script>
<script>
function css(){
    $('#sources-table_length').addClass('main__table-text');
    $('#sources-table_paginate').addClass('paginator');
    $('#sources-table_length label select').select2();
}
function dataTable(source_key = ''){
    var myTable = $('#sources-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.video.sources', $video['id']) !!}',
            "data" : {
                "source_key" : source_key,
            },
        },
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'source_key', name: 'source_key', orderable: false, searchable: false, class: 'td-with' },
            { data: 'prioritize', name: 'prioritize', orderable: false, searchable: false },
            { data: 'channel_id', name: 'channel_id', orderable: false, searchable: false, class: 'td-with' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
    css();
}
dataTable();
</script>

<script type="text/javascript">
function changeChannelType(object = ''){
    if ($( object+' .channels option:selected').attr('channel_type') == 1) {
        $(object+' .youtube').show();
        $(object+' .server').hide();
    }else{
        $(object+' .youtube').hide();
        $(object+' .server').show();
    }
}
$('#frmCreateSource .channels').change(function(e){
    e.preventDefault();
    changeChannelType('#frmCreateSource');
})
$('#frmEditSource .channels').change(function(e){
    e.preventDefault();
    changeChannelType('#frmEditSource');
})
$('#sources-table').on('click', '.edit-source', function(e){
    e.preventDefault();
    var sourceId = $(this).attr('data-source');
    $.ajax({
        type: 'GET',
        url: '/admin/source/show/' + sourceId,
        success: function(data) {
            $("#frmEditSource input[name=source_key]").val(data.source.source_key);
            $("#frmEditSource input[name=source_id]").val(data.source.id);
            $("#frmEditSource input[name=prioritize]").val(data.source.prioritize);
            $("#frmEditSource .channels").val(data.source.channel_id);
            $("#frmEditSource .channels").select2();
            changeChannelType('#frmEditSource');
            $("#frmEditSource .prioritize").html('');
            $("#frmEditSource .video").html('');
            $("#edit-source").click();
        },
        error: function(data) {
            console.log(data);
        }
    });
})
function changeStatus (){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.video.changeStatus', $video['id']) !!}',
        "data" : {
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            location.reload();
        },
        error: function(data) {
            location.reload();
        }
    });
}
</script>
<script type="text/javascript">
function deleteSource(sourceId){
    swal({
      title: "Are you sure to delete this source?",
      text: "This action can not be undone",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type: "POST",
                url: '/admin/source/delete/'+sourceId,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { _token: '{{csrf_token()}}' },
                success: function(response) {
                    dataTable();
                },
                error: function(data) {
                    dataTable();
                }
            });
        }
    });
}
</script>
<script type="text/javascript">
$("#btn-create").click(function(e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('prioritize', $("#frmCreateSource input[name='prioritize']").val());
    formData.append('channel_id', $('#frmCreateSource .channels').val());
    formData.append('source_key', $("#frmCreateSource input[name='source_key']").val());
    formData.append('source_id', $("#frmCreateSource input[name='source_id']").val());
    formData.append('video_id', $("#frmCreateSource input[name='video_id']").val());
    formData.append('movie_id', $("#frmCreateSource input[name='movie_id']").val());
    formData.append('video', $("#frmCreateSource input[name='video']").prop('files')[0]);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#frmCreateSource meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '{!! route('backend.source.store') !!}',
        data:formData,
        contentType: false,
        processData: false,
        cache:false,
        success: function(data) {
            dataTable();
            $("#frmCreateSource .modal__btn--dismiss").click();
            swal("Create source completed", " ", "success");
        },
        error: function(data) {
            var errors = $.parseJSON(data.responseText);
            $("#frmCreateSource .prioritize").html('');
            $("#frmCreateSource .video").html('');
            $.each(errors.messages, function(key, value){
                $("#frmCreateSource ."+key).html(value);
            });
        }
    });
});
$("#btn-edit").click(function(e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('prioritize', $("#frmEditSource input[name='prioritize']").val());
    formData.append('channel_id', $('#frmEditSource .channels').val());
    formData.append('source_key', $("#frmEditSource input[name='source_key']").val());
    formData.append('source_id', $("#frmEditSource input[name='source_id']").val());
    formData.append('video', $("#frmEditSource input[name='video']").prop('files')[0]);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/admin/source/update/' + $("#frmEditSource input[name=source_id]").val(),
        data:formData,
        contentType: false,
        processData: false,
        cache:false,
        success: function(data) {
            dataTable();
            $("#frmEditSource .modal__btn--dismiss").click();
            swal("Update source completed", " ", "success");
        },
        error: function(data) {
            var errors = $.parseJSON(data.responseText);
            $("#frmEditSource .prioritize").html('');
            $("#frmEditSource .video").html('');
            $.each(errors.messages, function(key, value){
                $("#frmEditSource ."+key).html(value);
            });
        }
    });
});
</script>
@endsection

@section('main__title')
<h2>Edit video</h2>
{{-- <span class="main__title-stat">{{ count($videos) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.video.index') }}" class="main__title-link">Video List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <div class="profile__avatar">

            </div>
            <!-- red -->
            <div class="profile__meta profile__meta--green">
                <h3>{{ $video['title'] }}</h3>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(session('status')!='new') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(session('status')=='new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                Sources
            	</a>
            </li>
        </ul>
        <!-- end profile tabs nav -->

        <!-- profile mobile tabs nav -->
        <div class="profile__mobile-tabs" id="profile__mobile-tabs">
            <div class="profile__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <input type="button" value="Profile">
                <span></span>
            </div>

            <div class="profile__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a></li>

                    <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Sources</a></li>

                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <button type="button" class="profile__action profile__action--banned" onclick="changeStatus()">
                @if($video['status'] == 1)
                <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @else
                <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @endif
            </button>
            <form action="{{ route('backend.video.destroy' , $video['id']) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="profile__action profile__action--delete">
                    <i class="icon ion-ios-trash"></i>
                </button>
            </form>
        </div>
        <!-- end profile btns -->
    </div>
</div>
<!-- end profile -->
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade @if(session('status')!='new') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <form action="{{ route('backend.video.update', $video['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                @csrf
                {{ method_field('PUT') }}
                @if(Session::has('movieId'))
                <input type="hidden" name="movieId" value="{{ Session::get('movieId') }}">
                @endif
                @if(Session::has('playlistId'))
                <input type="hidden" name="playlistId" value="{{ Session::get('playlistId') }}">
                @endif
                {{ Session::has('playlistId') }}
                <div class="row">
                    <div class="col-12">
                        <label class="profile__label">Title
                        @error('title')
                        <span class="main__table-text--red">({{ $message }})</span>
                        @enderror</label>
                        <input type="text" class="form__input" placeholder="Required... (Ex: Ep1)" name="title" value="{{ $video['title'] }}">
                    </div>

                    <div class="col-12">
                        <label class="profile__label">Description
                        @error('description')
                        <span class="main__table-text--red">({{ $message }})</span>
                        @enderror
                        </label>
                        <textarea id="text" name="description" class="form__textarea" placeholder="Required... (Ex: My movie - my video)">{{ $video['description'] }}</textarea>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="profile__group">
                            <label class="profile__label">Tags</label>
                            @error('tags')
                            <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            <input type="text" name="tags" class="profile__input" placeholder="No required... (Ex: #myTag1 #myTag2)" value="{{ $video['tags'] }}">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="profile__label">Chap
                        @error('chap')
                        <span class="main__table-text--red">({{ $message }})</span>
                        @enderror
                        </label>
                        <input type="text" class="form__input" placeholder="Required for videos in playlist... (Ex: 1)" name="chap" value="{{ $video['chap'] }}">
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="form__btn">save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
<!-- end content tabs -->
    </div>

    <div class="tab-pane fade @if(session('status')=='new') active show @endif" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
        <div class="col-12">
            <a href="#modal-form" class="form__btn open-modal">
                new source
            </a>
        </div>
        <a href="#modal-edit" class="open-modal" id="edit-source"></a>
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="sources-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SOURCE KEY</th>
                            <th>PRIORITIZE</th>
                            <th>CHANNEL</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->
@endsection

@section('modal')
<div id="modal-form" class="zoom-anim-dialog mfp-hide modal">
	<form action="{{ route('backend.source.store') }}" enctype="multipart/form-data" method="POST" id="frmCreateSource">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="video_id" value="{{ $video['id'] }}">
    <input type="text" class="form__input" placeholder="Prioritize" name="movie_id" value="{{$video['movie_id']}}" hidden>
	<h6 class="modal__title">Add New Source</h6>
	<div class="form">
		<div class="row">
			<div class="col-12">
                <label class="profile__label">Prioritize (*)</label>
				<div class="main__table-text--red prioritize"></div>
				<input type="text" class="form__input" placeholder="Default: Lowest in descending priority" name="prioritize">
			</div>
			<div class="col-12">
                <label class="profile__label">Channel</label>
                <select class="js-example-basic-single channels" name="channel_id">
                    @foreach ($channels as $channel)
                    <option  data-toggle="collapse" data-target=".multi-collapse" value="{{ $channel->id }}" @if($channel->id == 1)selected=""@endif channel_type="{{ $channel->channel_type }}">{{ $channel->title }}</option>
                    @endforeach
                </select>
            </div>

			<div class="col-12">
                <div class="row">
                    <!-- video -->
                    <div class="col-12">
                        <div class="collapse multi-collapse server">
                            <div class="form__video" style="overflow: visible;">
                                <label id="video1" for="form__video-upload">Upload video</label>
                                <div class="main__table-text--red video"></div>
                                <input data-name="#video1" id="form__video-upload" name="video" class="form__video-upload" type="file" accept="video/mp4,video/x-m4v,video/*">
                            </div>
                        </div>
                    </div>
                    <!-- end video -->

                    <!-- tv series -->
                    <div class="col-12">
                        <div class="collapse show multi-collapse youtube">
                            <div class="row">
                                <div class="col-12">
                                    <label for="profile__label">Source key (*)</label>
                                    <div class="main__table-text--red video"></div>
                                    <input type="text" class="form__input" placeholder="Ex: f41QDkSqEQg" name="source_key">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end tv series -->
                </div>
            </div>
		</div>
	</div>

	<div class="modal__btns">
		<button class="modal__btn modal__btn--apply" type="button" id="btn-create">Apply</button>
		<button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
	</div>
	</form>
</div>
<div id="modal-edit" class="zoom-anim-dialog mfp-hide modal">
    <form method="POST" id="frmEditSource" enctype="multipart/form-data">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="video_id" value="{{ $video['id'] }}">
    <input type="hidden" name="source_id" value="">

    @if(Session::has('playlistId'))
    <input type="hidden" name="playlistId" value="{{ Session::get('playlistId') }}">
    @endif

    <h6 class="modal__title">Edit Source</h6>
    <div class="form">
        <div class="row">
            <div class="col-12">
                <label class="profile__label">Prioritize (*)</label>
                <div class="main__table-text--red prioritize"></div>
                <input type="text" class="form__input" placeholder="Default: lowest prioritize" name="prioritize">
            </div>

            <div class="col-12">
                <label class="profile__label">Channel</label>
                <select class="channels" name="channel_id">
                    @foreach ($channels as $channel)
                    <option  data-toggle="collapse" data-target=".multi-collapse" value="{{ $channel->id }}" @if($channel->id == 1)selected=""@endif channel_type="{{ $channel->channel_type }}">{{ $channel->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <div class="row">
                    <!-- video -->
                    <div class="col-12">
                        <div class="collapse multi-collapse server">
                            <div class="form__video" style="overflow: visible;">
                                <label id="video1" for="form__video-upload">Upload video</label>
                                <div class="main__table-text--red video"></div>
                                <input data-name="#video1" id="form__video-upload" name="video" class="form__video-upload" type="file" accept="video/mp4,video/x-m4v,video/*">
                            </div>
                        </div>
                    </div>
                    <!-- end video -->

                    <!-- tv series -->
                    <div class="col-12">
                        <div class="collapse show multi-collapse youtube">
                            <div class="row">
                                <div class="col-12">
                                    <label class="profile__label">Source key (*)</label>
                                    <div class="main__table-text--red video"></div>
                                    <input type="text" class="form__input" placeholder="Ex: f41QDkSqEQgs" name="source_key">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end tv series -->
                </div>
            </div>
            <input type="hidden" name="source_id">
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-edit">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
@endsection
