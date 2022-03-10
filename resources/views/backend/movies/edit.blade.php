
@extends('backend.layouts.master')

@section('title')
Edit movie
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{asset('backend/css/jquery.tagsinput-revisited.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
<script src="{{asset('backend/js/jquery.tagsinput-revisited.js')}}"></script>
<script type="text/javascript">
    $(function(response) {
        $('#tags').tagsInput({
            'autocomplete': {
                source: [
                    @foreach($tags as $tag)
                        '{{ $tag['name'] }}',
                    @endforeach
                ]
            }
        });
    });
</script>
<script type="text/javascript">
    $('#language').select2();
</script>
<script type="text/javascript">
$('#channels').select2({
    placeholder: "Choose channel"
});
$('#channels').change(function(e){
    e.preventDefault();
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
    $('#playlists-table_length').addClass('main__table-text');
    $('#playlists-table_paginate').addClass('paginator');
    $('#playlists-table_length label select').select2();
}
function css1(){
    $('#no-movie-playlists-table_length').addClass('main__table-text');
    $('#no-movie-playlists-table_paginate').addClass('paginator');
    $('#no-movie-playlists-table_length label select').select2();
}
function css2(){
    $('#videos-table_length').addClass('main__table-text');
    $('#videos-table_paginate').addClass('paginator');
    $('#videos-table_length label select').select2();
}
function dataTable(title = ''){
    var myTable = $('#playlists-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.movie.moviePlaylists', $movie['id']) !!}',
            "data" : {
                "title" : title,
            },
        },
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
            { data: 'order', name: 'order', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
    css();
}
function dataTable1(title = ''){
    var myTable = $('#no-movie-playlists-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.movie.playlists', $movie['id']) !!}',
            "data" : {
                "title" : title,
            },
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
            { data: 'order', name: 'order', orderable: false, searchable: false },
        ]
    });
    css1();
}
function dataTable2(title = ''){
    var myTable = $('#videos-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.movie.videos', $movie['id']) !!}',
            "data" : {
                "title" : title,
            },
        },
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
            { data: 'description', name: 'description', orderable: false, searchable: false, class: 'td-with' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
    css2();
}
dataTable();
dataTable1();
dataTable2();
</script>
<script type="text/javascript">
function submitChange (playlistId){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.movie.updatePlaylist', $movie['id']) !!}',
        "data" : {
            "playlistId" : playlistId,
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
        }
    });
}
function submitChange1 (videoId){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.movie.updateVideo', $movie['id']) !!}',
        "data" : {
            "videoId" : videoId,
            _token: '{{csrf_token()}}'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            location.reload();
        },
        error: function(data) {
        }
    });
}
function detachPlaylist(playlistId){
    $.ajax({
        type: "POST",
        url: '/admin/playlist/detach/'+playlistId,
        "data" : {
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
            console.log(data)
        }
    });
}
$("#btn-create-video").click(function(e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('title', $("#frmCreateVideo input[name='title']").val());
    formData.append('channel_id', $('#frmCreateVideo .channels').val());
    formData.append('source_key', $("#frmCreateVideo input[name='source_key']").val());
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
        url: '{!! route('backend.movie.addVideo', $movie['id']) !!}',
        data:formData,
        contentType: false,
        processData: false,
        cache:false,
        success: function(data) {
            dataTable();
            $("#frmCreateVideo .modal__btn--dismiss").click();
            location.reload();
            swal("Create video completed", " ", "success");
        },
        error: function(data) {
            var errors = $.parseJSON(data.responseText);
            $("#frmCreateVideo .title").html('');
            $("#frmCreateVideo .description").html('');
            $("#frmCreateVideo .video").html('');
            $.each(errors.messages, function(key, value){
                $("#frmCreateVideo ."+key).html(value);
            });
        }
    });
});
</script>
<script type="text/javascript" name="playlist">
    $("#btn-create-playlist").click(function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('title', $("#frmCreatePlaylist input[name='title']").val());
        formData.append('description', $("#frmCreatePlaylist textarea[name='description']").val());
        formData.append('order', $("#frmCreatePlaylist input[name='order']").val());
        formData.append('movie_id', $("#frmCreatePlaylist input[name='movie_id']").val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreatePlaylist meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '{!! route('backend.playlist.store') !!}',
            data:formData,
            contentType: false,
            processData: false,
            cache:false,
            success: function(data) {
                dataTable();
                $("#frmCreatePlaylist .modal__btn--dismiss").click();
                $("#frmCreatePlaylist input[name='title']").val('');
                $("#frmCreatePlaylist textarea[name='description']").val('');
                $("#frmCreatePlaylist input[name='order']").val('');
                $("#frmEditPlaylist .title").html('');
                $("#frmEditPlaylist .description").html('');
                $("#frmEditPlaylist .order").html('');
                swal("Create advertisment completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreatePlaylist .title").html('');
                $("#frmCreatePlaylist .description").html('');
                $("#frmCreatePlaylist .order").html('');
                $.each(errors.messages, function(key, value){
                    $("#frmCreatePlaylist ."+key).html(value);
                });
            }
        });
    });

    $('#playlists-table').on('click', '.edit-playlist', function(e){
        e.preventDefault();
        var playlistId = $(this).attr('data-playlist');
        $.ajax({
            type: 'GET',
            url: '/admin/playlist/show/' + playlistId,
            success: function(data) {
                $("#frmEditPlaylist input[name=playlist_id]").val(data.playlist.id);
                $("#frmEditPlaylist input[name=title]").val(data.playlist.title);
                $("#frmEditPlaylist textarea[name=description]").val(data.playlist.description);
                $("#frmEditPlaylist input[name=order]").val(data.playlist.order);
                $("#frmEditPlaylist .title").html('');
                $("#frmEditPlaylist .description").html('');
                $("#frmEditPlaylist .order").html('');
                $("#edit-playlist").click();
            },
            error: function(data) {
                console.log(data);
            }
        });
    })

    $("#btn-update-playlist").click(function(e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreatePlaylist meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'PUT',
            url: '/admin/playlist/update/'+$("#frmEditPlaylist input[name=playlist_id]").val(),
            data: {
                'title': $("#frmEditPlaylist input[name='title']").val(),
                'description': $("#frmEditPlaylist textarea[name='description']").val(),
                'order': $("#frmEditPlaylist input[name='order']").val(),
                'movie_id': $("#frmEditPlaylist input[name='movie_id']").val(),
                '_method' : 'PUT', 
                '_token' : '{{ csrf_token() }}',
            },
            dataType: 'json',
            success: function(data) {
                dataTable();
                $("#frmEditPlaylist .modal__btn--dismiss").click();
                $("#frmEditPlaylist input[name='title']").val('');
                $("#frmEditPlaylist textarea[name='description']").val('');
                $("#frmEditPlaylist input[name='order']").val('');
                swal("Update advertisment completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmEditPlaylist .title").html('');
                $("#frmEditPlaylist .description").html('');
                $("#frmEditPlaylist .order").html('');
                $.each(errors.messages, function(key, value){
                    $("#frmEditPlaylist ."+key).html(value);
                });
            }
        });
    });

    function deletePlaylist(playlistId){
        swal({
          title: "Are you sure to delete this playlist?",
          text: "This action can not be undone",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: '/admin/playlist/delete/'+playlistId,
                    data : {'_method' : 'DELETE', '_token' : '{{ csrf_token() }}'},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        dataTable();
                        swal({
                            title : "Delete playlist completed",
                            icon : "success",
                            button : "Done",
                        });
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            }
        });
    }
</script>
@endsection

@section('main__title')
<h2>Edit movie</h2>
{{-- <span class="main__title-stat">{{ count($movies) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.movie.index') }}" class="main__title-link">Movie List</a>
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
                <h3>{{ $movie['name'] }}</h3>
                <span>Genre:
                	@foreach($genre as $value)
                	@if($movie['genre'] == $value->order )
                	{{ $value->name }}
                	@endif
                	@endforeach
                </span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(\session('status')!='new') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(session('status')=='new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                @if($movie['genre'] == 1)
                Video
                @else
                Playlists
                @endif
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
                    <li class="nav-item"><a class="nav-link @if(session('status')!='new') active show @endif" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a></li>

                    <li class="nav-item"><a class="nav-link @if(session('status')=='new') active show @endif" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                        @if($movie['genre'] == 1)
                        Video
                        @else
                        Playlists
                        @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <form action="{{ route('backend.movie.destroy' , $movie['id']) }}" method="POST">
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
            <form action="{{ route('backend.movie.update', $movie['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                @csrf
                {{ method_field('PUT') }}
                <div class="row">
                    <div class="col-12 col-md-5 form__cover">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-12">
                                <div class="form__img">
                                    <label for="form__img-upload">Upload cover (270 x 400)</label>
                                    <input id="form__img-upload" name="card_cover" type="file" accept=".png, .jpg, .jpeg">
                                    <img id="form__img" src="{{ asset($movie['card_cover']) }}" alt=" ">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-7 form__content">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                @error('name')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Name" name="name" value="{{ $movie['name'] }}">
                            </div>

                            <div class="col-12 col-lg-6">
                                @error('name_ja')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Japanese name" name="name_ja" value="{{ $movie['name_ja'] }}">
                            </div>

                            <div class="col-12">
                                @error('description')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <textarea id="text" class="form__textarea" placeholder="Description" name="description">{!! $movie['description'] !!}</textarea>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                @error('release_year')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Release year" name="release_year" value="{{ $movie['release_year'] }}">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                @error('runtime')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Running timed in minutes" name="runtime" value="{{ $movie['runtime'] }}">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                @error('quality')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <select class="js-example-basic-single" id="quality" name="quality">
                                    @foreach ($quality as $value)
                                    <option value="{{ $value->order }}" @if($movie['quality'] == $value->order ) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                @error('age')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Age" name="age" value="{{ $movie['age'] }}">
                            </div>

                            <div class="col-12 col-lg-6">
                                @error('country')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <select class="js-example-basic-multiple" id="country" name="country">
                                    @foreach ($country as $value)
                                    <option value="{{ $value->order }}" @if($movie['country'] == $value->order ) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-lg-6">
                                @error('types[]')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <select class="js-example-basic-multiple" id="types" multiple="multiple" name="types[]">
                                    @foreach ($types as $type)
                                    <option value="{{ $type['id'] }}"
                                    	@foreach($movieTypes as $movieType)
                                    	@if($movieType['id']==$type['id']) selected @endif
                                    	@endforeach>{{ $type['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                @error('tags')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input id="tags" name="tags" type="text" value="{{$movieTags}}"  class="form__input">
                            </div>
                        </div>
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
    	@if($movie['genre'] == 1)
        @if(count($movieVideos)==0)
        <div class="col-12">
			<a href="#modal-form" class="form__btn open-modal">
				new video
			</a>
		</div>
		<div class="col-12" style="text-align: center;color: white">OR SELECT</div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="videos-table">
                    <thead>
                        <tr>
                            <th>INDEX</th>
                            <th>TITLE</th>
                            <th>DESCRIPTION</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- end table -->
            @else
            @foreach($movieVideos as $video)
        <div class="col-12">
            <a href="{{ route('backend.video.edit', [$video->id, $movie['id']]) }}?links=movie" class="form__btn">
                Edit video
            </a>
        </div>
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                        <table class="main__table">
                            <tbody>
                                <tr>
                                    <td class="main__table-text">ID</td>
                                    <td>
                                        <div class="main__table-text">{{ $video->id }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Title</td>
                                    <td>
                                        <div class="main__table-text">{{ $video->title }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Descriptiom</td>
                                    <td>
                                        <div class="main__table-text">{{ $video->description }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Slug</td>
                                    <td>
                                        <div class="main__table-text">{{ $video->slug }}</div>
                                    </td>
                                </tr>
                                @foreach($video->sources()->get() as $source)
                                <tr>
                                    <td class="main__table-text">Source</td>
                                    <td>
                                        <div class="main__table-text">{{ $source->source_key }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!-- end details form -->
            </div>
        </div>
        <div class="col-12">
            <form action="{{ route('backend.video.detach', $video->id) }}" method="POST">
                @csrf
                <button type="submit" class="form__btn">Detach video</button>
            </form>
        </div>
            @endforeach
            @endif
        @else
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="playlists-table">
                    <thead>
                        <tr>
                            <th>INDEX</th>
                            <th>TITLE</th>
                            <th>ORDER</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-12">
            <a href="#modal-form-1" class="form__btn open-modal">
                new playlist
            </a>
            <a href="#modal-edit-playlist" id="edit-playlist" class="open-modal"></a>
        </div>
        <div class="col-12" style="text-align: center;color: white">OR SELECT</div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="no-movie-playlists-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>INDEX</th>
                            <th>TITLE</th>
                            <th>ORDER</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->

<!-- paginator -->
<div class="col-12">
    <div class="paginator-wrap">
        {{-- <span>10 from {{ count($movies) }}</span> --}}

    </div>
</div>
<!-- end paginator -->
@endsection

@section('modal')
<div id="modal-form" class="zoom-anim-dialog mfp-hide modal">
	<form action="{{ route('backend.movie.addVideo', $movie['id']) }}" enctype="multipart/form-data" method="POST" id="frmCreateVideo">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<h6 class="modal__title">Create New Video</h6>
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
				<div class="main__table-text--red description"></div>
				<textarea class="form__textarea" placeholder="Description" name="description">{{ old('description') }}</textarea>
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
<div id="modal-form-1" class="zoom-anim-dialog mfp-hide modal">
    <form enctype="multipart/form-data" method="POST" id="frmCreatePlaylist">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
    <h6 class="modal__title">Create New Playlist</h6>
    <div class="form">
        <div class="row">
            <div class="col-12">
                <div class="main__table-text--red title"></div>
                <input type="text" class="form__input" placeholder="Title" name="title" value="">
            </div>
            <div class="col-12">
                <div class="main__table-text--red description"></div>
                <textarea class="form__textarea" placeholder="Description" name="description"></textarea>
            </div>
            <div class="col-12">
                <div class="main__table-text--red order"></div>
                <input type="text" class="form__input" placeholder="Order" name="order" value="">
            </div>
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-create-playlist">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
<div id="modal-edit-playlist" class="zoom-anim-dialog mfp-hide modal">
    <form method="POST" id="frmEditPlaylist">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
    <input type="hidden" name="playlist_id" value="">
    <h6 class="modal__title">Edit Playlist</h6>
    <div class="form">
        <div class="row">
            <div class="col-12">
                <div class="main__table-text--red title"></div>
                <input type="text" class="form__input" placeholder="Title" name="title" value="">
            </div>
            <div class="col-12">
                <div class="main__table-text--red description"></div>
                <textarea class="form__textarea" placeholder="Description" name="description"></textarea>
            </div>
            <div class="col-12">
                <div class="main__table-text--red order"></div>
                <input type="text" class="form__input" placeholder="Order" name="order" value="">
            </div>
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-update-playlist">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
@endsection
