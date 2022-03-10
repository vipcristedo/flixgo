
@extends('backend.layouts.master')

@section('title')
Edit manga
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
    $('#types').select2();
    function dataTable(name = ''){
        var myTable = $('#chapters-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url"  : '{!! route('backend.manga.mangaChapters', $manga['id']) !!}',
                "data" : {
                    "name" : name,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false, class: 'td-with' },
                { data: 'chap', name: 'chap', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        $('#chapters-table_length').addClass('main__table-text');
        $('#chapters-table_paginate').addClass('paginator');
        $('#chapters-table_length label select').select2();
    }
    function dataTable1(name = ''){
        var myTable = $('#no-manga-chapters-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url"  : '{!! route('backend.manga.chapters', $manga['id']) !!}',
                "data" : {
                    "name" : name,
                },
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false, class: 'td-with' },
                { data: 'chap', name: 'chap', orderable: false, searchable: false },
            ]
        });
        $('#no-manga-chapters-table_length').addClass('main__table-text');
        $('#no-manga-chapters-table_paginate').addClass('paginator');
        $('#no-manga-chapters-table_length label select').select2();
    }
    function dataTable2(link = ''){
        var myTable = $('#ads-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url"  : '{!! route('backend.manga.mangaManga_ads', $manga['id']) !!}',
                "data" : {
                    "link" : link,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'link', name: 'link', orderable: false, searchable: false, class: 'td-with' },
                { data: 'artical', name: 'artical', orderable: false, searchable: false, class: 'td-with' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        $('#ads-table_length').addClass('main__table-text');
        $('#ads-table_paginate').addClass('paginator');
        $('#ads-table_length label select').select2();
    }
    function dataTable3(link = ''){
        var myTable = $('#no-manga-ads-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url"  : '{!! route('backend.manga.manga_ads', $manga['id']) !!}',
                "data" : {
                    "link" : link,
                },
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'link', name: 'link', orderable: false, searchable: false, class: 'td-with' },
                { data: 'artical', name: 'artical', orderable: false, searchable: false, class: 'td-with' },
            ]
        });
        $('#no-manga-ads-table_length').addClass('main__table-text');
        $('#no-manga-ads-table_paginate').addClass('paginator');
        $('#no-manga-ads-table_length label select').select2();
    }
    dataTable();
    dataTable1();
    dataTable2();
    dataTable3();
</script>
<script type="text/javascript">
    function submitChange (chapterId){
        $.ajax({
            type: "POST",
            url: '{!! route('backend.manga.updateChapter', $manga['id']) !!}',
            "data" : {
                "chapterId" : chapterId,
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
    function submitChange1 (manga_adId){
        $.ajax({
            type: "POST",
            url: '{!! route('backend.manga.updateManga_ad', $manga['id']) !!}',
            "data" : {
                "manga_adId" : manga_adId,
                _token: '{{csrf_token()}}'
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                dataTable2();
                dataTable3();
            },
            error: function(data) {
            }
        });
    }
    function detachChapter(chapterId){
        $.ajax({
            type: "POST",
            url: '/admin/chapter/detach/'+chapterId,
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
    function detachManga_ad(manga_adId){
        $.ajax({
            type: "POST",
            url: '/admin/manga_ad/detach/'+manga_adId,
            "data" : {
                _token: '{{csrf_token()}}'
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                dataTable2();
                dataTable3();
            },
            error: function(data) {
                console.log(data)
            }
        });
    }
</script>
<script type="text/javascript">
    function getIMG(frm){
        $(frm+" .artical_img").attr("src", $(frm+" input[name='artical']").val());
    }
    function errorIMG(){
        $(".artical_img").attr("src",'https://developers.google.com/maps/documentation/streetview/images/error-image-generic.png?authuser=2&hl=vi');
    }   
    $("#frmCreateManga_ad input[name='artical']").on('change', function (e) {
        var frm = "#frmCreateManga_ad";
        e.preventDefault();
        getIMG(frm);
        $(frm+" .artical_img").on("error", function(e){
            e.preventDefault();
            errorIMG();
        });
    });
    $("#frmEditManga_ad input[name='artical']").on('change', function (e) {
        var frm = "#frmEditManga_ad";
        e.preventDefault();
        getIMG(frm);
        $(frm+" .artical_img").on("error", function(e){
            e.preventDefault();
            errorIMG();
        });
    });
</script>
<script type="text/javascript" name="manga_ad">
    $("#btn-create-manga_ad").click(function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('link', $("#frmCreateManga_ad input[name='link']").val());
        formData.append('artical', $("#frmCreateManga_ad input[name='artical']").val());
        formData.append('object_id', $("#frmCreateManga_ad input[name='object_id']").val());
        formData.append('table_name', $("#frmCreateManga_ad input[name='table_name']").val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreateManga_ad meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '{!! route('backend.manga_ad.store') !!}',
            data:formData,
            contentType: false,
            processData: false,
            cache:false,
            success: function(data) {
                dataTable();
                $("#frmCreateManga_ad .modal__btn--dismiss").click();
                dataTable2();
                $("#frmCreateManga_ad input[name='link']").val('');
                $("#frmCreateManga_ad input[name='artical']").val('');
                $("#frmCreateManga_ad .artical_img").attr("src", "#");
                $("#frmEditManga_ad .link").html('');
                $("#frmEditManga_ad .artical").html('');
                swal("Create advertisment completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreateManga_ad .link").html('');
                $("#frmCreateManga_ad .artical").html('');
                $.each(errors.messages, function(key, value){
                    $("#frmCreateManga_ad ."+key).html(value);
                });
            }
        });
    });

    $('#ads-table').on('click', '.edit-manga_ad', function(e){
        e.preventDefault();
        var manga_adId = $(this).attr('data-manga_ad');
        $.ajax({
            type: 'GET',
            url: '/admin/manga_ad/show/' + manga_adId,
            success: function(data) {
                $("#frmEditManga_ad input[name=manga_ad_id]").val(data.manga_ad.id);
                $("#frmEditManga_ad input[name=link]").val(data.manga_ad.link);
                $("#frmEditManga_ad input[name=artical]").val(data.manga_ad.artical);
                $("#frmEditManga_ad .artical_img").attr("src", data.manga_ad.artical);
                $("#frmEditManga_ad .link").html('');
                $("#frmEditManga_ad .artical").html('');
                $("#edit-manga_ad").click();
            },
            error: function(data) {
                console.log(data);
            }
        });
    })

    $("#btn-update-manga_ad").click(function(e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreateManga_ad meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'PUT',
            url: '/admin/manga_ad/update/'+$("#frmEditManga_ad input[name=manga_ad_id]").val(),
            data: {
                'link': $("#frmEditManga_ad input[name='link']").val(),
                'artical': $("#frmEditManga_ad input[name='artical']").val(),
                'table_name': $("#frmEditManga_ad input[name='table_name']").val(),
                'object_id': $("#frmEditManga_ad input[name='object_id']").val(),
                '_method' : 'PUT', 
                '_token' : '{{ csrf_token() }}',
            },
            dataType: 'json',
            success: function(data) {
                dataTable();
                $("#frmEditManga_ad .modal__btn--dismiss").click();
                dataTable2();
                $("#frmEditManga_ad input[name='link']").val('');
                $("#frmEditManga_ad input[name='artical']").val('');
                $("#frmEditManga_ad .artical_img").attr("src", "#");
                swal("Update advertisment completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmEditManga_ad .link").html('');
                $("#frmEditManga_ad .artical").html('');
                $.each(errors.messages, function(key, value){
                    $("#frmEditManga_ad ."+key).html(value);
                });
            }
        });
    });

    function deleteManga_ad(manga_adId){
        swal({
          title: "Are you sure to delete this advertisment?",
          text: "This action can not be undone",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: '/admin/manga_ad/delete/'+manga_adId,
                    data : {'_method' : 'DELETE', '_token' : '{{ csrf_token() }}'},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        dataTable2();
                        swal({
                            title : "Delete advertisment completed",
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
<script type="text/javascript" name="chapter">
    $("#btn-create-chapter").click(function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('name', $("#frmCreateChapter input[name='name']").val());
        formData.append('description', $("#frmCreateChapter textarea[name='description']").val());
        formData.append('release_year', $("#frmCreateChapter input[name='release_year']").val());
        formData.append('chap', $("#frmCreateChapter input[name='chap']").val());
        formData.append('manga_id', $("#frmCreateChapter input[name='manga_id']").val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreateChapter meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '{!! route('backend.chapter.store') !!}',
            data:formData,
            contentType: false,
            processData: false,
            cache:false,
            success: function(data) {
                dataTable();
                $("#frmCreateChapter .modal__btn--dismiss").click();
                dataTable2();
                $("#frmCreateChapter input[name='name']").val('');
                $("#frmCreateChapter textarea[name='description']").val('');
                $("#frmCreateChapter input[name='release_year']").val('');
                $("#frmCreateChapter input[name='chap']").val('');                
                $("#frmCreateChapter .name").html('');
                $("#frmCreateChapter .description").html('');
                $("#frmCreateChapter .release_year").html('');
                $("#frmCreateChapter .chap").html('');
                swal("Create chapter completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreateChapter .name").html('');
                $("#frmCreateChapter .description").html('');
                $("#frmCreateChapter .release_year").html('');
                $("#frmCreateChapter .chap").html('');
                $.each(errors.messages, function(key, value){
                    $("#frmCreateChapter ."+key).html(value);
                });
            }
        });
    });
    $('#chapters-table').on('click', '.edit-chapter', function(e){
        e.preventDefault();
        var chapterId = $(this).attr('data-chapter');
        $.ajax({
            type: 'GET',
            url: '/admin/chapter/show/' + chapterId,
            success: function(data) {
                $("#frmEditChapter input[name=chapter_id]").val(data.chapter.id);
                $("#frmEditChapter input[name=name]").val(data.chapter.name);
                $("#frmEditChapter .name").html('');
                $("#frmEditChapter textarea[name=description]").val(data.chapter.description);
                $("#frmEditChapter .description").html('');
                $("#frmEditChapter input[name=release_year]").val(data.chapter.release_year);
                $("#frmEditChapter .release_year").html('');
                $("#frmEditChapter input[name=chap]").val(data.chapter.chap);
                $("#frmEditChapter .chap").html('');
                $("#edit-chapter").click();
            },
            error: function(data) {
                console.log(data);
            }
        });
    })

    $("#btn-update-chapter").click(function(e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreateManga_ad meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/chapter/update/'+$("#frmEditChapter input[name=chapter_id]").val(),
            data: {
                'name': $("#frmEditChapter input[name='name']").val(),
                'description': $("#frmEditChapter textarea[name='description']").val(),
                'manga_id': $("#frmEditChapter input[name='manga_id']").val(),
                'release_year': $("#frmEditChapter input[name='release_year']").val(),
                'chap': $("#frmEditChapter input[name='chap']").val(),
                '_token' : '{{ csrf_token() }}',
            },
            dataType: 'json',
            success: function(data) {
                dataTable();
                $("#frmEditChapter .modal__btn--dismiss").click();
                dataTable2();
                $("#frmEditChapter input[name='name']").val('');
                $("#frmEditChapter textarea[name='description']").val('');
                $("#frmEditChapter input[name='release_year']").val('');
                $("#frmEditChapter input[name='chap']").val('');
                swal("Update chapter completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmEditChapter .name").html('');
                $("#frmEditChapter .description").html('');
                $("#frmEditChapter .chap").html('');
                $("#frmEditChapter .release_year").html('');
                $.each(errors.messages, function(key, value){
                    $("#frmEditChapter ."+key).html(value);
                });
            }
        });
    });

    function deleteChapter(chapterId){
        swal({
          title: "Are you sure to delete this chapter?",
          text: "This action can not be undone",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: '/admin/chapter/delete/'+chapterId,
                    data : {'_method' : 'DELETE', '_token' : '{{ csrf_token() }}'},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        dataTable();
                        swal({
                            title : "Delete advertisment completed",
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
<h2>Edit manga</h2>
{{-- <span class="main__title-stat">{{ count($mangas) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.manga.index') }}" class="main__title-link">Manga List</a>
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
                <h3>{{ $manga['name'] }}</h3>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(session('status')!='new' && session('status')!='tab-3') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(session('status')=='new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                Chapters
            	</a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(session('status')=='tab-3') active show @endif" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Ads</a>
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
                    <li class="nav-item"><a class="nav-link @if(session('status')!='new' && session('status')!='tab-3') active show @endif" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a></li>

                    <li class="nav-item"><a class="nav-link @if(session('status')=='new') active show @endif" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                        Chapters
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(session('status')=='tab-3') active show @endif" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Ads</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <form action="{{ route('backend.manga.destroy' , $manga['id']) }}" method="POST">
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
    <div class="tab-pane fade @if(session('status')!='new' && session('status')!='tab-3') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <form action="{{ route('backend.manga.update', $manga['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                @csrf
                {{ method_field('PUT') }}
                <div class="row">
                    <div class="col-12 col-md-5 form__cover">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-12">
                                <div class="form__img">
                                    <label for="form__img-upload">Upload cover (270 x 400)</label>
                                    <input id="form__img-upload" name="card_cover" type="file" accept=".png, .jpg, .jpeg">
                                    <img id="form__img" src="{{ asset($manga['card_cover']) }}" alt=" ">
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
                                <input type="text" class="form__input" placeholder="Name" name="name" value="{{ $manga['name'] }}">
                            </div>

                            <div class="col-12 col-lg-6">
                                @error('author')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Author (default: Anomynous)" name="author" value="{{ $manga['author'] }}">
                            </div>

                            <div class="col-12">
                                @error('description')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <textarea class="form__textarea" placeholder="Description" name="description" style="height: 195px">{!! $manga['description'] !!}</textarea>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                @error('release_year')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Release year" name="release_year" value="{{ $manga['release_year'] }}">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                @error('age')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Age" name="age" value="{{ $manga['age'] }}">
                            </div>

                            <div class="col-12 col-lg-6">
                                @error('country')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <select class="js-example-basic-multiple" id="country" name="country">
                                    @foreach ($country as $value)
                                    <option value="{{ $value->order }}" @if($manga['country'] == $value->order ) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-lg-12">
                                @error('types[]')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <select class="js-example-basic-multiple" id="types" multiple="multiple" name="types[]">
                                    @foreach ($types as $type)
                                    <option value="{{ $type['id'] }}"
                                    	@foreach($mangaTypes as $mangaType)
                                    	@if($mangaType['id']==$type['id']) selected @endif
                                    	@endforeach>{{ $type['title'] }}</option>
                                    @endforeach
                                </select>
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
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="chapters-table">
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
            <a href="#modal-form-1" class="form__btn open-modal">
                new chapter
            </a>
            <a href="#modal-edit-chapter" class="open-modal" id="edit-chapter"></a>
        </div>
        <div class="col-12" style="text-align: center;color: white">OR SELECT</div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="no-manga-chapters-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>INDEX</th>
                            <th>TITLE</th>
                            <th>CHAP</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade @if(session('status')=='tab-3') active show @endif" id="tab-3" role="tabpanel" aria-labelledby="3-tab">
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="ads-table">
                    <thead>
                        <tr>
                            <th>INDEX</th>
                            <th>LINK</th>
                            <th>ARTICAL</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-12">
            <a href="#modal-form-2" class="form__btn open-modal">
                new advertisment
            </a>
            <a href="#modal-edit-manga_ad" class="open-modal" id="edit-manga_ad"></a>
        </div>
        <div class="col-12" style="text-align: center;color: white">OR SELECT</div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="no-manga-ads-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>INDEX</th>
                            <th>LINK</th>
                            <th>ARTICAL</th>
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
<div id="modal-form-1" class="zoom-anim-dialog mfp-hide modal">
    <form action="{{ route('backend.chapter.store') }}" enctype="multipart/form-data" method="POST" id="frmCreateChapter">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="manga_id" value="{{ $manga['id'] }}">
    <h6 class="modal__title">Create New Chapter</h6>
    <div class="form">
        <div class="row">
            <div class="col-12">
                <div class="main__table-text--red name"></div>
                <input type="text" class="form__input" placeholder="Name(*)" name="name" value="{{ old('name') }}">
            </div>
            <div class="col-12">
                <div class="main__table-text--red description"></div>
                <textarea class="form__textarea" placeholder="Description(*)" name="description">{{ old('description') }}</textarea>
            </div>
            <div class="col-12 col-lg-6">
                <div class="main__table-text--red release_year"></div>
                <input type="text" class="form__input" placeholder="Release year(*)" name="release_year" value="{{ old('release_year') }}">
            </div>
            <div class="col-12 col-lg-6">
                <div class="main__table-text--red chap"></div>
                <input type="text" class="form__input" placeholder="Chap(*)" name="chap" value="{{ old('chap') }}">
            </div>
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-create-chapter">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
<div id="modal-edit-chapter" class="zoom-anim-dialog mfp-hide modal">
    <form enctype="multipart/form-data" method="POST" id="frmEditChapter">
    <h6 class="modal__title">Edit Chapter</h6>
    <input type="hidden" name="chapter_id" value="">
    <input type="hidden" name="manga_id" value="{{ $manga['id'] }}">
    <div class="form">
        <div class="row">
            <div class="col-12">
                <div class="main__table-text--red name"></div>
                <input type="text" class="form__input" placeholder="Name(*)" name="name" value="">
            </div>
            <div class="col-12">
                <div class="main__table-text--red description"></div>
                <textarea class="form__textarea" placeholder="Description(*)" name="description"></textarea>
            </div>
            <div class="col-12 col-lg-6">
                <div class="main__table-text--red release_year"></div>
                <input type="text" class="form__input" placeholder="Release year(*)" name="release_year" value="">
            </div>
            <div class="col-12 col-lg-6">
                <div class="main__table-text--red chap"></div>
                <input type="text" class="form__input" placeholder="Chap(*)" name="chap" value="">
            </div>
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-update-chapter">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
<div id="modal-form-2" class="zoom-anim-dialog mfp-hide modal">
    <form action="{{ route('backend.manga_ad.store') }}" enctype="multipart/form-data" method="POST" id="frmCreateManga_ad">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="object_id" value="{{ $manga['id'] }}">
    <input type="hidden" name="table_name" value="mangas">
    <h6 class="modal__title">Create New Advertisment</h6>
    <div class="form">
        <div class="row">
            <div class="col-12">
                <div class="main__table-text--red link"></div>
                <input type="text" class="form__input" placeholder="Link* (Ex: https://bit.ly/abcxyz)" name="link" value="{{ old('link') }}">
            </div>
            <div class="col-12">
                <div class="main__table-text--red artical"></div>
                <input type="text" class="form__input" placeholder="Artical* (Ex: https://i.pinimg.com//myIMG.jpg)" name="artical" class="img_link" value="{{ old('artical') }}">
            </div>
            <div class="col-12">
                <img id="artical_img" src="#" alt="" style="width: 100%" class="artical_img">
            </div>
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-create-manga_ad">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
<div id="modal-edit-manga_ad" class="zoom-anim-dialog mfp-hide modal">
    <form enctype="multipart/form-data" method="POST" id="frmEditManga_ad">
    <h6 class="modal__title">Edit Advertisment</h6>
    <input type="hidden" name="manga_ad_id" value="">
    <input type="hidden" name="table_name" value="mangas">
    <input type="hidden" name="object_id" value="{{ $manga['id'] }}">
    <div class="form">
        <div class="row">
            <div class="col-12">
                <div class="main__table-text--red link"></div>
                <input type="text" class="form__input" placeholder="Link* (Ex: https://bit.ly/abcxyz)" name="link" value="{{ old('link') }}">
            </div>
            <div class="col-12">
                <div class="main__table-text--red artical"></div>
                <input type="text" class="form__input" placeholder="Artical* (Ex: https://i.pinimg.com//myIMG.jpg)" name="artical" class="img_link" value="{{ old('artical') }}">
            </div>
            <div class="col-12">
                <img id="artical_img" src="#" alt="" style="width: 100%" class="artical_img">
            </div>
        </div>
    </div>

    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button" id="btn-update-manga_ad">Apply</button>
        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
    </div>
    </form>
</div>
@endsection
