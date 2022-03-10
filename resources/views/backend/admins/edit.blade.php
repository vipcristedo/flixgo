
@extends('backend.layouts.master')

@section('title')
Edit admin
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
<script type="text/javascript">
$('#roleId').select2({
    placeholder: "Choose Role"
});
function css(){
    $('#roles-table_length').addClass('main__table-text');
    $('#roles-table_paginate').addClass('paginator');
    $('#roles-table_length label select').select2();
}
function dataTable(name = ''){
    var myTable = $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        destroy:true,
        ajax: {
            "url"  : '{!! route('backend.admin.roles', $admin['id']) !!}',
            "data" : {
                "name" : name,
            },
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'name', name: 'name', orderable: false, searchable: false },
            { data: 'description', name: 'description', orderable: false, searchable: false },
        ]
    });
    css();
}

dataTable();

function search (e){
    var name = $('#filter').val();
    $.ajax({
        url: '{!! route('backend.admin.roles', $admin['id']) !!}',
        data: {
            "name" : name,
        },
        success: function (response) {
            dataTable(name);
        },
        error: function (error) {
            console.log('error');
        }
    })
    $('#roles-table').dataTable
}

$('#filter').on('change',function(e){
    e.preventDefault();
    window.setTimeout( search, 200 );
})

function submitChange (roleId){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.admin.updateRole', $admin['id']) !!}',
        "data" : {
            "roleId" : roleId,
            _token: '{{csrf_token()}}'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            search();
        },
        error: function(data) {
            console.log(data)
        }
    });
}

function changeStatus (){
    $.ajax({
        type: "POST",
        url: '{!! route('backend.admin.changeStatus', $admin['id']) !!}',
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
<h2>Edit admin</h2>
{{-- <span class="main__title-stat">{{ count($admins) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.admin.index') }}" class="main__title-link">Admin List</a>
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
                <h3>{{ $admin['name'] }}<span>(Approved)</span></h3>
                <span>FlixGo ID: {{ $admin['id'] }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Profile</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Roles</a>
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
                    <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Profile</a></li>

                    <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Roles</a></li>

                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <button type="button" class="profile__action profile__action--banned" onclick="changeStatus()">
                @if($admin['is_active'] == 1)
                <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @else
                <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Show"></i>
                @endif
            </button>
            <form action="{{ route('backend.admin.destroy' , $admin['id']) }}" method="POST">
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
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-6">
                    <form action="{{ route('backend.admin.update', $admin['id'])  }}" method="POST" class="profile__form">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="profile__title">Profile details</h4>
                            </div>
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Name 
                                        @error('name')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="username" type="text" name="name" class="profile__input" placeholder="{{ $admin['name'] }}" value="{{ $admin['name'] }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="email">Email
                                        @error('email')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="email" type="text" name="email" class="profile__input" placeholder="{{ $admin['email'] }}" value="{{ $admin['email'] }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Phone 
                                        @error('phone')
                                        <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" name="phone" class="profile__input" placeholder="{{ $admin['phone'] }}" value="{{ $admin['phone'] }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Address 
                                        @error('address')
                                        <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" name="address" class="profile__input" placeholder="{{ $admin['address'] }}" value="{{ $admin['address'] }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="profile__btn" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end details form -->

                <!-- password form -->
                <div class="col-12 col-lg-6">
                    <form action="{{ route('backend.admin.changePassword', $admin['id']) }}" class="profile__form" method="POST">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-12">
                                <h4 class="profile__title">Change password</h4>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Old Password</label>
                                    @error('oldPassword')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <input id="oldpass" type="password" name="oldPassword" class="profile__input">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="newpass">New Password</label>
                                    @error('password')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <input id="newpass" type="password" name="password" class="profile__input">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="confirmpass">Confirm New Password</label>
                                    <input id="confirmpass" type="password" name="password_confirmation" class="profile__input">
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="profile__btn" type="submit">Change</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end password form -->
            </div>
        </div>  
    </div>

    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
        <div class="col-12">
            <div class="form">
                    <div class="main__title-form">
                        <input type="text" placeholder="Find role.." style="width: 100%;" id="filter">
                        <button type="button">
                            <i class="icon ion-ios-search"></i>
                        </button>
                    </div>
            </div>
        </div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table" id="roles-table" style="width: 100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>NAME</th>
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