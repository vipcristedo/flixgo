
@extends('backend.layouts.master')

@section('title')
Permission Detail
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
@endsection

@section('main__title')
<h2>Permission Detail</h2>
{{-- <span class="main__title-stat">{{ count($permissions) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.permission.index') }}" class="main__title-link">Permission List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <div class="profile__meta profile__meta--green">
                <h3>{{ $permission->name }}</h3>
                <span>ID: {{ $permission->id }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <form action="{{ route('backend.permission.changeStatus', $permission->id) }}" method="POST">
                @csrf
                <button type="submit" class="profile__action profile__action--banned">
                    @if($permission->status == 1)
                    <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hidden"></i>
                    @else
                    <i class="fa fa-unlock-alt" data-toggle="tooltip" title="Activate"></i>
                    @endif
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
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                        <table class="main__table">
                            <tbody>
                                <tr>
                                    <td class="main__table-text">ID</td>
                                    <td>
                                        <div class="main__table-text">{{ $permission->id }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Name</td>
                                    <td>
                                        <div class="main__table-text">{{ $permission->name }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Display name</td>
                                    <td>
                                        <div class="main__table-text">{{ $permission->display_name }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Status</td>
                                    <td>
                                        @if($permission->is_active == 1)
                                        <div class="main__table-text main__table-text--green">
                                        Active
                                        </div>
                                        @else
                                        <div class="main__table-text main__table-text--red">
                                        Hidden
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Description</td>
                                    <td>
                                        <div class="main__table-text">{{ $permission->description }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Created At</td>
                                    <td>
                                        <div class="main__table-text">{{ $permission->created_at }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!-- end details form -->
            </div>
        </div>  
    </div>

    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
        <div class="col-12">
            <div class="profile__content">
            <form action="{{ route('backend.permission.updateRole', $permission->id) }}" method="POST" style="width:100%;">
                @csrf
                {{ method_field('PUT') }}
                <div class="form-group col-6" style="display: inline-block;">
                <select name="roleId" id="roleId">
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>   
                </div>
                <input type="hidden" name="id" value="{{ $permission->id }}">
                <button class="main__title-link"  style="float:right" type="submit">add new Role</button>
            </form>
            </div>
            
        </div>
        <!-- table -->
        <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($permissionRoles as $key => $role)
                        <tr>
                            <td>
                                <div class="main__table-text">{{ $role->id }}</div>
                            </td>
                            <td>
                                <div class="main__table-text">{{ $role->name }}</div>
                            </td>
                            <td>
                                <div class="main__table-text">{{ $role->description }}</div>
                            </td>
                            <td>
                                @if($role->status == 1)
                                <div class="main__table-text main__table-text--green">
                                Active
                                </div>
                                @else
                                <div class="main__table-text main__table-text--red">
                                Hidden
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="main__table-btns">
                                    <a href="#modal-view" class="main__table-btn main__table-btn--view open-modal">
                                        <i class="icon ion-ios-eye"></i>
                                    </a>
                                    <form action="{{ route('backend.permission.updateRole', $permission->id) }}" method="POST">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <input type="hidden" name="roleId" value="{{ $role->id }}">
                                        <button class="main__table-btn main__table-btn--delete" type="submit"><i class="icon ion-ios-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end table -->

        <!-- paginator -->
        <div class="col-12">
            <div class="paginator-wrap">
                {!! $links !!}
            </div>
        </div>
        
        <!-- end paginator -->
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->

<!-- paginator -->
<div class="col-12">
    <div class="paginator-wrap">
        {{-- <span>10 from {{ count($permissions) }}</span> --}}
        
    </div>
</div>
<!-- end paginator -->
@endsection