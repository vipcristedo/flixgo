
@extends('backend.layouts.master')

@section('title')
Edit playlist
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
$('#channels').select2({
    placeholder: "Choose roles"
});
$('#channels').change(function(e){
    e.preventDefault();
    $('body').append('<p>'+$('#channels option:selected').attr('channel_type')+'</p>');
    if ($('#channels option:selected').attr('channel_type') == 1) {
        $('#youtube').show();
        $('#server').hide();
    }else{
        $('#youtube').hide();
        $('#server').show();
    }
})
</script>
<script type="text/javascript" src="{{ asset('backend/js/movies.js') }}"></script>
<script>
function css(){
    $('#videos-table_length').addClass('main__table-text');
    $('#videos-table_paginate').addClass('paginator');
    $('#videos-table_length label select').select2();
}
function css1(){
    $('#no-playlist-videos-table_length').addClass('main__table-text');
    $('#no-playlist-videos-table_paginate').addClass('paginator');
    $('#no-playlist-videos-table_length label select').select2();
}
function dataTable(title = ''){
    var myTable = $('#videos-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.playlist.playlistVideos', $playlist['id']) !!}',
            "data" : {
                "title" : title,
            },
        },
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
            { data: 'chap', name: 'chap', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
    css();
}
function dataTable1(title = ''){
    var myTable = $('#no-playlist-videos-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.playlist.videos', $playlist['id']) !!}',
            "data" : {
                "title" : title,
            },
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
            { data: 'description', name: 'description', orderable: false, searchable: false, class: 'td-with' },
        ]
    });
    css1();
}
dataTable();
dataTable1();
function submitChange (videoId){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.playlist.updateVideo', $playlist['id']) !!}',
        "data" : {
            "videoId" : videoId,
            _token: '{{csrf_token()}}'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            dataTable();
            dataTable1();
        },
        error: function(data) {
            //
        }
    });
}
function changeStatus (){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.playlist.changeStatus', $playlist['id']) !!}',
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
$("#btn-create-video").click(function(e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('title', $("#frmCreateVideo input[name='title']").val());
    formData.append('channel_id', $('#frmCreateVideo .channels').val());
    formData.append('source_key', $("#frmCreateVideo input[name='source_key']").val());
    formData.append('chap', $("#frmCreateVideo input[name='chap']").val());
    formData.append('description', $("#frmCreateVideo textarea[name='description']").val());
    formData.append('tags', $("#frmCreateVideo input[name='tags']").val());
    formData.append('video', $("#frmCreateVideo input[name='video']").prop('files')[0]);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#frmCreateVideo meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '{!! route('backend.playlist.addVideo', $playlist['id']) !!}',
        data:formData,
        contentType: false,
        processData: false,
        cache:false,
        success: function(data) {
            dataTable();
            $("#frmCreateVideo .modal__btn--dismiss").click();
            dataTable();
            swal("Create video completed", " ", "success");
            $("#frmCreateVideo input[name='title']").val('');
            $("#frmCreateVideo input[name='source_key']").val('');
            $("#frmCreateVideo input[name='chap']").val('');
            $("#frmCreateVideo textarea[name='description']").val('');
            $("#frmCreateVideo input[name='tags']").val('');
            $("#frmCreateVideo input[name='video']").val('Upload video');
            $("#video1").html('');
        },
        error: function(data) {
            var errors = $.parseJSON(data.responseText);
            $("#frmCreateVideo .title").html('');
            $("#frmCreateVideo .description").html('');
            $("#frmCreateVideo .video").html('');
            $("#frmCreateVideo .chap").html('');
            $.each(errors.messages, function(key, value){
                $("#frmCreateVideo ."+key).html(value);
            });
        }
    });
});
</script>
@endsection

@section('main__title')
<h2>Edit playlist</h2>
<a href="{{ route('backend.playlist.index') }}" class="main__title-link">Playlist List</a>
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
                <h3>{{ $playlist['title'] }}</h3>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(session('status')!='new') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a>
            </li>

            <li class="nav-item ">
                <a class="nav-link @if(session('status')=='new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                Videos
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
                    <li class="nav-item"><a class="nav-link @if(session('status')!='new') active show @endif" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Profile</a></li>

                    <li class="nav-item"><a class="nav-link @if(session('status')=='new') active show @endif" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Roles</a></li>

                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <button type="button" class="profile__action profile__action--banned" onclick="changeStatus()">
                @if($playlist['status'] == 1)
                <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @else
                <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @endif
            </button>
            <form action="{{ route('backend.playlist.destroy' , $playlist['id']) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="profile__action profile__action--delete">
                    <i class="icon ion-ios-trash" data-toggle="tooltip" title="Delete"></i>
                </button>
            </form>
        </div>
        <!-- end profile btns -->
    </div>
</div>
<!-- end profile -->
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade @if(\session('status')!='new') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <form action="{{ route('backend.playlist.update', $playlist['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                @csrf
                {{ method_field('PUT') }}
                @if(Session::has('movieId'))
                <input type="hidden" name="movieId" value="{{ Session::get('movieId') }}">
                @endif
                <div class="row">
                    <div class="col-12">
                        @error('title')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
                        <label class="profile__labele" for="title">Title</label>
                        <input type="text" class="form__input" placeholder="Title" name="title" value="{{ $playlist['title'] }}">
                    </div>

                    <div class="col-12">
                        @error('description')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
                        <label class="profile__labele" for="description">Description</label>
                        <textarea id="text" class="form__textarea" placeholder="Description" name="description">{!! $playlist['description'] !!}</textarea>
                    </div>
                    <div class="col-12">
                        @error('order')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
                        <label class="profile__labele" for="order">Order</label>
                        <input type="text" class="form__input" placeholder="Order" name="order" value="{{ $playlist['order'] }}">
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
            <div class="main__table-wrap">
                <table class="main__table" id="videos-table">
                    <thead>
                        <tr>
                            <th>INDEX</th>
                            <th>TITLE</th>
                            <th>CHAP</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-12">
            <a href="#modal-form" class="form__btn open-modal">
                new video
            </a>
        </div>
        <div class="col-12" style="text-align: center;color: white">OR SELECT</div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="no-playlist-videos-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>INDEX</th>
                            <th>TITLE</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->

<!-- paginator -->
<div class="col-12">
    <div class="paginator-wrap">
        {{-- <span>10 from {{ count($playlists) }}</span> --}}

    </div>
</div>
<!-- end paginator -->
@endsection

@section('modal')
<div id="modal-form" class="zoom-anim-dialog mfp-hide modal">
	<form action="{{ route('backend.playlist.addVideo', $playlist['id']) }}" enctype="multipart/form-data" method="POST" id="frmCreateVideo">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<h6 class="modal__title">Add New Video</h6>
	<div class="form">
		<div class="row">
			<div class="col-12">
				<div class="main__table-text--red title"></div>
				<input type="text" class="form__input" placeholder="Title" name="title" value="{{ old('title') }}">
			</div>
            <div class="col-12">
                <div class="main__table-text--red tags"></div>
                <input type="text" class="form__input" placeholder="Tags" name="tags" value="{{ old('tags') }}">
            </div>
            <div class="col-12">
                <div class="main__table-text--red chap"></div>
                <input type="text" class="form__input" placeholder="Chap" name="chap" value="{{ old('chap') }}">
            </div>
			<div class="col-12">
				<div class="main__table-text--red description"></div>
				<textarea id="text" class="form__textarea" placeholder="Description" name="description">{{ old('description') }}</textarea>
			</div>
			
            <div class="col-12">
                <label class="profile__label">Channel</label>
                <select class="js-example-basic-single channels" id="channels" name="channel_id">
                    @foreach ($channels as $channel)
                    <option value="{{ $channel['id'] }}" @if($channel['id'] == old('channel_id'))selected=""@endif channel_type="{{ $channel['channel_type'] }}">{{ $channel['title'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <div class="row">
                    <!-- video -->
                    <div class="col-12">
                        <div class="collapse multi-collapse" id="server">
                            <div class="form__video" style="overflow: visible;">
                                <label id="video1" class="profile__label" for="form__video-upload">Upload video</label>
                                <div class="main__table-text--red video"></div>
                                <input data-name="#video1" id="form__video-upload" name="video" class="form__video-upload" type="file" accept="video/mp4,video/x-m4v,video/*">
                            </div>
                        </div>
                    </div>
                    <!-- end video -->

                    <!-- tv series -->
                    <div class="col-12">
                        <div class="collapse show multi-collapse" id="youtube">
                            <div class="row">
                                <div class="col-12">
                                    <label id="video1" class="profile__label">Source key</label>
                                    <div class="main__table-text--red video"></div>
                                    <input type="text" class="form__input" placeholder="Source key (Ex: f41QDkSqEQg)" name="source_key">
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
		<button class="modal__btn modal__btn--apply" type="button" id="btn-create-video">Apply</button>
		<button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
	</div>
	</form>
</div>
@endsection
