
@extends('backend.layouts.master')

@section('title')
Edit type
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#table_name').select2();
</script>
@endsection

@section('main__title')
<h2>Edit type</h2>
{{-- <span class="main__title-stat">{{ count($types) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.type.index') }}" class="main__title-link">Type List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <!-- red -->
            <div class="profile__meta profile__meta--green">
                <h3>{{ $type['title'] }}</h3>
                <span>ID: {{ $type['id'] }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile btns -->
        <div class="profile__actions">
            <form action="{{ route('backend.type.destroy' , $type['id']) }}" method="POST">
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
                    <form action="{{ route('backend.type.update', $type['id'])  }}" method="POST" class="profile__form">
                        <div class="row">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__labele" for="title">Title 
                                    @error('title')
                                    <span class="alert alert-danger">({{ $message }})</span>
                                    @enderror
                                    </label>
                                    <input id="username" type="text" name="title" class="profile__input" placeholder="{{ $type['title'] }}" value="{{ $type['title'] }}">
                                </div>
                            </div>


                            <div class="col-12 col-lg-6">
                                <label class="profile__labele" for="title">Table</label>
                                <select class="js-example-basic-multiple" id="table_name" name="table_name">
                                    <option value="movies" @if($type['table_name']=='movies') selected @endif >Movies</option>
                                    <option value="mangas"  @if($type['table_name']=='mangas') selected @endif >Mangas</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Description 
                                    @error('description')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                    </label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Description">{{ $type['description'] }}</textarea>
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
        {{-- <span>10 from {{ count($types) }}</span> --}}
        
    </div>
</div>
<!-- end paginator -->
@endsection