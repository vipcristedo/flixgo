
@extends('backend.layouts.master')

@section('title')
Edit option
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
<h2>Edit option</h2>
{{-- <span class="main__title-stat">{{ count($options) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.option.index') }}" class="main__title-link">Option List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <!-- red -->
            <div class="profile__meta profile__meta--green">
                <h3>{{ $option->name }}</h3>
                <span>Option ID: {{ $option->id }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <form action="{{ route('backend.option.changeStatus', $option->id) }}" method="POST">
                @csrf
                {{ method_field('PUT') }}
                <button type="submit" class="profile__action profile__action--banned">
                    @if($option->status == 1)
                    <i class="icon ion-ios-lock"></i>
                    @else
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    @endif
                </button>
            </form>
            <form action="{{ route('backend.option.destroy' , $option->id) }}" method="POST">
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
                    <form action="{{ route('backend.option.update', $option->id)  }}" method="POST" class="profile__form">
                        <div class="row">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__labele" for="name">Name</label>
                                    <input id="username" type="text" name="name" class="profile__input" placeholder="{{ $option->name }}" value="{{ $option->name }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__label">Description</label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Description">{{ $option->description }}</textarea>
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
        {{-- <span>10 from {{ count($options) }}</span> --}}
        
    </div>
</div>
<!-- end paginator -->
@endsection