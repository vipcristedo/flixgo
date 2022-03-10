
@extends('backend.layouts.master')

@section('title')
Add new type
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#table_name').select2({
        placeholder: "Choose tables"
    });
</script>
@endsection

@section('main__title')
<h2>Add new type</h2>
<a href="{{ route('backend.type.index') }}" class="main__title-link">Type List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.type.store')  }}" method="POST" class="profile__form">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Title (*)
                                        @error('title')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="username" type="text" name="title" class="profile__input" placeholder="Title ..." value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label class="profile__label">Table</label>
                                <select class="js-example-basic-multiple" id="table_name" name="table_name">
                                    <option value="movies">Movies</option>
                                    <option value="mangas">Mangas</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Description (*)
                                        @error('description')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Description">{{ old('description') }}</textarea>
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
