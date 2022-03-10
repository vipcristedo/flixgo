
@extends('backend.layouts.master')

@section('title')
Add new playlist
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#order').select2({
        placeholder: "Choose order"
    });
    $('#playlist_type').select2();
</script>
@endsection

@section('main__title')
<h2>Add new playlist</h2>
{{-- <span class="main__title-stat">{{ count($playlists) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.playlist.index') }}" class="main__title-link">Playlist List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.playlist.store')  }}" method="POST" class="profile__form">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Title (*)
                                        @error('title')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input type="text" name="title" class="profile__input" placeholder="Required ... (Ex: My playlist)" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Order (*)
                                        @error('order')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input type="text" name="order" class="profile__input" placeholder="Required ... (Ex: 1)" value="{{ old('order') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="description">Description (*)
                                        @error('description')
                                        <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Required ... (Ex: My movie - my playlist)">{{ old('description') }}</textarea>
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
        {{-- <span>10 from {{ count($playlists) }}</span> --}}

    </div>
</div>
<!-- end paginator -->
@endsection
