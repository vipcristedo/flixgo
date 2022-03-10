
@extends('backend.layouts.master')

@section('title')
Edit tag
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
<h2>Edit tag</h2>
{{-- <span class="main__title-stat">{{ count($tags) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.tag.index') }}" class="main__title-link">Tag List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <!-- red -->
            <div class="profile__meta profile__meta--green">
                <h3>{{ $tag->name }}</h3>
                <span>ID: {{ $tag->id }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile btns -->
        <div class="profile__actions">
            <form action="{{ route('backend.tag.destroy' , $tag->id) }}" method="POST">
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
                    <form action="{{ route('backend.tag.update', $tag->id)  }}" method="POST" class="profile__form">
                        <div class="row">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__labele" for="name">Name</label>
                                    <input id="username" type="text" name="name" class="profile__input" placeholder="{{ $tag->name }}" value="{{ $tag->name }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                @error('slug')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="profile__group">
                                    <label class="profile__label">Slug</label>
                                    <textarea id="text" name="slug" class="form__textarea" placeholder="Slug">{{ $tag->slug }}</textarea>
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
        {{-- <span>10 from {{ count($tags) }}</span> --}}
        
    </div>
</div>
<!-- end paginator -->
@endsection