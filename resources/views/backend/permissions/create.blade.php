
@extends('backend.layouts.master')

@section('title')
Add new permission
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
@endsection

@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    $('#roles').select2({
        placeholder: "Choose roles"
    });
</script>
@endsection

@section('main__title')
<h2>Add new permission</h2>
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
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.permission.store')  }}" method="POST" class="profile__form">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Name</label>
                                    @error('title')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <input type="text" name="name" class="profile__input" placeholder="Name ...">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Display Name</label>
                                    @error('title')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <input type="text" name="display_name" class="profile__input" placeholder="Display Name ...">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Description</label>
                                    @error('description')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <textarea name="description" class="form__textarea" placeholder="Description"></textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Roles</label>
                                    @error('roles')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    <select name="roles[]" class="js-example-basic-multiple" id="roles" multiple="multiple">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                    </select>
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