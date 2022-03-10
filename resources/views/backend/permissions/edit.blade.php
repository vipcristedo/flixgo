
@extends('backend.layouts.master')

@section('title')
Edit permission
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    $('#roleId').select2();
</script>
@endsection

@section('main__title')
<h2>Edit permission</h2>
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
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Detail</a>
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
                    <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Detail</a></li>

                    <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Roles</a></li>

                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <form action="{{ route('backend.permission.changeStatus', $permission->id) }}" method="POST">
                @csrf
                {{ method_field('PUT') }}
                <button type="submit" class="profile__action profile__action--banned">
                    @if($permission->status == 1)
                    <i class="icon ion-ios-lock"></i>
                    @else
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    @endif
                </button>
            </form>
            <form action="{{ route('backend.permission.destroy' , $permission->id) }}" method="POST">
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
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.permission.update', $permission->id)  }}" method="POST" class="profile__form">
                        <div class="row">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__label" for="username">Name</label>
                                    <input id="username" type="text" name="name" class="profile__input" placeholder="{{ $permission->name }}" value="{{ $permission->name }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__label" for="username">Display Name</label>
                                    <input id="username" type="text" name="display_name" class="profile__input" placeholder="{{ $permission->display_name }}" value="{{ $permission->display_name }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Description</label>
                                    @error('description')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Description">{{ $permission->description }}</textarea>
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

    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
        <div class="col-12">
            <div class="profile__content">
            <form action="{{ route('backend.permission.updateRole', $permission->id) }}" method="POST" style="width:100%;">
                @csrf
                {{ method_field('PUT') }}
                <div class="form-group col-6" style="display: inline-block;">
                <select class="js-example-basic-single" id="roleId" name="roleId">
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