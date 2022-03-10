
@extends('backend.layouts.master')

@section('title')
Add new admin
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<style type="text/css">
.form-control {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
</style>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#roles').select2({
        placeholder: "Choose roles"
    });
</script>
@endsection

@section('main__title')
<h2>Add new admin</h2>
<a href="{{ route('backend.admin.index') }}" class="main__title-link">Admin List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.admin.store')  }}" method="POST" class="profile__form">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Name 
                                        @error('name')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    
                                    <input id="username" type="text" name="name" class="profile__input" placeholder="Required ... (Ex: Sang)" value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="email">Email
                                        @error('email')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>

                                    <input id="email" type="text" name="email" class="profile__input" placeholder="Email ... (Ex: sang@gmail.com)" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Password 
                                        @error('password')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    
                                    <input type="password" name="password" class="profile__input">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="newpass">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="profile__input">
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Phone 
                                        @error('phone')
                                        <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    
                                    <input type="text" name="phone" class="profile__input" placeholder="Required ... (Ex: 984701585)" value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Address 
                                        @error('address')
                                        <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    
                                    <input type="text" name="address" class="profile__input" placeholder="Reqired ... (Ex: Hanoi Capital)" value="{{ old('address') }}">
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

@endsection