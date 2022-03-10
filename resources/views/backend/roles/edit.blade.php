
@extends('backend.layouts.master')

@section('title')
Edit role
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
function css(){
    $('#permissions-table_length').addClass('main__table-text');
    $('#permissions-table_paginate').addClass('paginator');
    $('#permissions-table_length label select').select2();
}
function dataTable(name = ''){
    var myTable = $('#permissions-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.role.permissions', $role['id']) !!}',
            "data" : {
                "name" : name,
            },
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'name', name: 'name', orderable: false, searchable: false },
            { data: 'display_name', name: 'display_name', orderable: false, searchable: false },
            { data: 'description', name: 'description', orderable: false, searchable: false },
        ]
    });
    css();
}

dataTable();

function search (e){
    var name = $('#filter').val();
    $.ajax({
        url: '{!! route('backend.role.permissions', $role['id']) !!}',
        data: {
            "name" : name,
        },
        success: function (response) {
            dataTable(name);
        },
        error: function (error) {
        }
    })
    $('#permissions-table').dataTable
}

$('#filter').on('change',function(e){
    e.preventDefault();
    window.setTimeout( search, 200 );
})

function submitChange (permissionId){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.role.updatePermission', $role['id']) !!}',
        "data" : {
            "permissionId" : permissionId,
            _token: '{{csrf_token()}}'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            search();
        },
        error: function(data) {
        }
    });
}
function changeStatus (){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.role.changeStatus', $role['id']) !!}',
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
@endsection

@section('main__title')
<h2>Edit role</h2>
<a href="{{ route('backend.role.index') }}" class="main__title-link">Role List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <div class="profile__avatar">
                <img src="{{ asset('backend/img/user.svg') }}" alt="">
            </div>
            <!-- red -->
            <div class="profile__meta profile__meta--green">
                <h3>{{ $role['name'] }}<span>(Approved)</span></h3>
                <span>FlixGo ID: {{ $role['id'] }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" permission="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-1" permission="tab" aria-controls="tab-1" aria-selected="true">Details</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-2" permission="tab" aria-controls="tab-2" aria-selected="false">Permissions</a>
            </li>
        </ul>
        <!-- end profile tabs nav -->

        <!-- profile mobile tabs nav -->
        <div class="profile__mobile-tabs" id="profile__mobile-tabs">
            <div class="profile__mobile-tabs-btn dropdown-toggle" permission="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <input type="button" value="Profile">
                <span></span>
            </div>

            <div class="profile__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                <ul class="nav nav-tabs" permission="tablist">
                    <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" permission="tab" aria-controls="tab-1" aria-selected="true">Details</a></li>

                    <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" permission="tab" aria-controls="tab-2" aria-selected="false">Permissions</a></li>

                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <button type="button" class="profile__action profile__action--banned" onclick="changeStatus()">
                @if($role['status'] == 1)
                <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @else
                <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @endif
            </button>
            <form action="{{ route('backend.role.destroy' , $role['id']) }}" method="POST">
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
    <div class="tab-pane fade show active" id="tab-1" permission="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.role.update', $role['id'])  }}" method="POST" class="profile__form">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="profile__title">Profile details</h4>
                            </div>
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__label" for="username">Name</label>
                                    <input id="username" type="text" name="name" class="profile__input" placeholder="{{ $role['name'] }}" value="{{ $role['name'] }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__label" for="description">Description</label>
                                    <input id="description" type="text" name="description" class="profile__input" placeholder="{{ $role['description'] }}" value="{{ $role['description'] }}">
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
    </div>

    <div class="tab-pane fade" id="tab-2" permission="tabpanel" aria-labelledby="2-tab">
        <div class="col-12">
            <div class="form">
                    <div class="main__title-form">
                        <input type="text" placeholder="Find permission.." style="width: 100%;" id="filter">
                        <button type="button">
                            <i class="icon ion-ios-search"></i>
                        </button>
                    </div>
            </div>
        </div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="permissions-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>DISPLAY_NAME</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- end table -->
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->
@endsection
