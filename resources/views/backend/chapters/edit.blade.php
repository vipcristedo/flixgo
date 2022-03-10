
@extends('backend.layouts.master')

@section('title')
    Edit Chapter
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <style>
        label.error {
            display: inline-block;
            color:red;
            width: 200px;
        }
    </style>
@endsection

@section('js')
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

    <script language="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $(document).ready(function() {

            $("#form_add").validate({
                rules: {
                    title: {
                        required:true
                    },
                    order: {
                        required:true,
                        number: true
                    },
                    link: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "not empty"
                    },
                    order: {
                        required:"not empty",
                        number: "must is a number"
                    },
                    link:{
                        required: "not empty"
                    }
                }
            });
        });
    </script>
    <script type="text/javascript" src="{{ asset('backend/js/movies.js') }}"></script>
    <script type="text/javascript">
        function css(){
            $('#pictures-table_length').addClass('main__table-text');
            $('#pictures-table_paginate').addClass('paginator');
            $('#pictures-table_length label select').select2();
        }
        function dataTable(title = ''){
            var myTable = $('#pictures-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy:true,
                ajax: {
                    "url"  : '{!! route('backend.chapter.getPictures', $chapter['id']) !!}',
                    "data" : {
                        "title" : title,
                    },
                },
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
                    { data: 'picture', name: 'picture', orderable: false, searchable: false },
                    { data: 'order', name: 'order', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
            css();
        }
        dataTable();
        function dataTable2(link = ''){
            var myTable = $('#ads-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy:true,
                ajax: {
                    "url"  : '{!! route('backend.chapter.getChapterAds', $chapter['id']) !!}',
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
        dataTable2();
        function dataTable3(link = ''){
            var myTable = $('#no-manga-ads-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy:true,
                ajax: {
                    "url"  : '{!! route('backend.chapter.getAds', $chapter['id']) !!}',
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
        dataTable3();
        function submitChange1 (manga_adId){
            $.ajax({
                type: "POST",
                url: '{!! route('backend.chapter.updateManga_ad', $chapter['id']) !!}',
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

        $('#pictures-table').on('click', '.edit-pic', function(e){
            e.preventDefault();
            var picId = $(this).attr('data-id');
            $.ajax({
                type: 'GET',
                url: '/admin/chapter/'+picId+'/edit-picture',
                success: function(data) {
                    $("#form_edit img").attr('src',data.pic.link);
                    // $("#form_edit input[name=pic_id]").val(data.pic.id);
                    $("#form_edit input[name=title]").val(data.pic.title);
                    $("#form_edit input[name=order]").val(data.pic.order);
                    $("#form_edit input[name=sources]").val(data.pic.sources);

                    $("#form_edit .title").html('');
                    $("#form_edit .order").html('');
                    $("#form_edit .sources").html('');
                    $("#form_edit").attr('action','/admin/chapter/update-Picture/'+picId);

                    $("body").append("<p>"+data.pic+"</p>");
                    $("#edit-pic").click();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        })
        $('#add_ads').click(function () {
            console.log('dfghjkl')
            $.ajax({
                url:'/admin/manga_ad/store',
                type: 'POST',
                data: {
                    link: $('#link').val(),
                    artical: $('#artical').val(),
                },
                success: function(response) {
                    console.log(response);

                    if (response.success == true) {
                        dataTable3();
                        $('#modal-form-2').modal('hide');
                    }else{
                        if (response.errors.link != undefined) {
                            $('.errorLink').show().text(response.errors.name[0]);
                        }
                        if (response.errors.artical != undefined) {
                            $('.errorArtical').show().text(response.errors.name[0]);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#modal-form-2').modal('show');

                }
            })
        })

    </script>
@endsection

@section('main__title')
    <h2>Edit chapter</h2>
    {{-- <span class="main__title-stat">{{ count($playlists) }} Total</span> --}}
    <div class="main__title-wrap">
        <!-- filter sort -->
        <!-- end filter sort -->

        <!-- search -->
        <!-- end search -->
    </div>
    <a href="{{ route('backend.chapter.index') }}" class="main__title-link">Chapter List</a>
    @if($chapter['manga_id'] != NULL && Session::has('status', 'new') == true)
    <a href="{{ route('backend.manga.edit', $chapter['manga_id']) }}" class="main__title-link">Back</a>
    @endif
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <!-- profile user -->
            <div class="profile__user">
                <div class="profile__avatar">

                </div>
                <!-- red -->
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $chapter->name }}</h3>
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
                        Images
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
                        <li class="nav-item"><a class="nav-link @if(session('status')!='new') active show @endif" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Details</a></li>
                        <li class="nav-item"><a class="nav-link @if(session('status')=='new') active show @endif" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Images</a></li>
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
                <form action="{{ route('backend.chapter.destroy' , $chapter->id) }}" method="POST">
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

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade @if(\session('status')!='new') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <div class="row">
                    <!-- details form -->
                    <div class="col-12 col-lg-12">
                        <form action="{{ route('backend.chapter.update',$chapter->id)  }}" method="POST" class="profile__form">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">name
                                            @error('name')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                            @enderror
                                        </label>
                                        <input type="text" name="name" class="profile__input" placeholder="Required ... (Ex: My playlist)" value="{{ $chapter->name }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label" for="description">Description
                                            @error('description')
                                            <span class="main__table-text--red">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <textarea id="text" name="description" class="form__textarea" placeholder="Required ... (Ex: My movie - my playlist)">{{ $chapter->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 col-lg-6 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label">Release year
                                            @error('release_year')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                            @enderror
                                        </label>
                                        <input type="text" name="release_year" class="profile__input"  value="{{ $chapter->release_year }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-lg-6 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label">chap
                                            @error('chap')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                            @enderror
                                        </label>
                                        <input type="text" name="chap" class="profile__input"  value="{{ $chapter->chap }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="profile__btn" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end details form -->
                </div>
            </div>

            <!-- end content tabs -->
        </div>

        <div class="tab-pane fade @if(session('status')=='new') active show @endif" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table" id="pictures-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>TITLE</th>
                            <th>PICTURE</th>
                            <th>ORDER</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
            <div class="col-12">
                <a href="#modal-add" class="form__btn open-modal">
                    new image
                </a>
            </div>
            <a href="#modal-edit" id="edit-pic" class="open-modal"></a>
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
        </div>
        <div class="col-12" style="text-align: center;color: white">OR SECLECT</div>
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

    <!-- content tabs -->
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        </div>
    </div>
    <!-- end content tabs -->
    <!-- end users -->

    <!-- paginator -->
    <div class="col-12">
        <div class="paginator-wrap">

        </div>
    </div>
    <!-- end paginator -->
@endsection
@section('modal')
    <div id="modal-add" class="zoom-anim-dialog mfp-hide modal fade bd-example-modal-lg"  >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form_add" action="{{ route('backend.chapter.addPicture', $chapter['id']) }}" enctype="multipart/form-data" method="POST" >
                    @csrf
                    <h6 class="modal__title">Add New Picture</h6>
                    <div class="form">
                        <div class="row">
                            <div class="col-12">
                                @error('title')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Title" name="title" is="title" value="" required>
                            </div>
                            <div class="col-12">
                                @error('order')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="number" class="form__input" placeholder="Order" name="order" id="order" required  pattern="[0-9]{1,3}">
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <!-- tv series -->
                                    <div class="col-12">
                                        <div class="collapse show multi-collapse" id="youtube">
                                            <div class="row">
                                                <div class="col-12">
                                                    @error('sources')
                                                    <div class="main__table-text--red">{{ $message }}</div>
                                                    @enderror
                                                    <input type="text" class="form__input" placeholder="Sources" name="sources" id="sources" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form__img">
                                            <label for="form__img-upload">Upload cover (270 x 400)</label>
                                            <input id="form__img-upload" name="link" type="file" accept=".png, .jpg, .jpeg" required>
                                            <img id="form__img" src="#" alt=" ">
                                        </div>
                                    </div>
                                </div>
                                <!-- end tv series -->
                            </div>

                        </div>
                    </div>

                    <div class="modal__btns">
                        <button class="modal__btn modal__btn--apply" type="submit" name="submit">Apply</button>
                        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-edit" class="zoom-anim-dialog mfp-hide modal fade bd-example-modal-lg"  >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form_edit" action="" enctype="multipart/form-data" method="POST" >
                    @csrf
                    <h6 class="modal__title">Edit Picture</h6>
                    <div class="form">
                        <div class="row">

                            <div class="col-12">
                                <div class="main__table-text--red title"></div>
                                <input id="edit_title_pic" type="text" class="form__input" placeholder="Title" name="title" is="title" value="" required>
                            </div>
                            <div class="col-12">
                                <div class="main__table-text--red order"></div>
                                <input type="number" class="form__input" placeholder="Order" name="order" id="edit_order_pic" required  pattern="[0-9]{1,3}">
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <!-- tv series -->
                                    <div class="col-12">
                                        <div class="collapse show multi-collapse" id="youtube">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="main__table-text--red sources"></div>
                                                    <input type="text" class="form__input" placeholder="Sources" name="sources" id="edit_source_pic" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form__img">
                                            <label for="form__img-upload">Upload cover (270 x 400)</label>
                                            <input id="form__img-upload" name="link" type="file" accept=".png, .jpg, .jpeg">
                                            <img id="form__img" src="#" alt=" " class="edit_picture">
                                        </div>
                                    </div>
                                </div>
                                <!-- end tv series -->
                            </div>

                        </div>
                    </div>

                    <div class="modal__btns">
                        <button class="modal__btn modal__btn--apply" type="submit" name="submit" id="update_pic">Apply</button>
                        <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-form-2" class="zoom-anim-dialog mfp-hide modal">
        <form enctype="multipart/form-data" method="POST" id="form_create_ad">
            @csrf
            <input type="hidden" name="object_id" value="{{ $chapter['id'] }}">
            <input type="hidden" name="table_name" value="chapters">
            <h6 class="modal__title">Create New Ad</h6>
            <div class="form">
                <div class="row">
                    <div class="col-12">
                        @error('link')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
                        <input id="link" type="text" class="form__input" placeholder="Link* (Ex: https://bit.ly/abcxyz)" name="link" value="{{ old('link') }}">
                    </div>
                    <div class="col-12">
                        @error('artical')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
                        <input id="artical" type="text" class="form__input" placeholder="Artical* (Ex: https://i.pinimg.com//myIMG.jpg)" name="artical"  value="{{ old('artical') }}">
                    </div>
                    <div class="col-12">
                        <img id="artical_img" src="#" alt="" style="width: 100%">
                    </div>
                </div>
            </div>

            <div class="modal__btns">
                <button class="modal__btn modal__btn--apply" type="button" id="add_ads" name="submit">Apply</button>
                <button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
            </div>
        </form>
    </div>

@endsection

