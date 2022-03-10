
@extends('backend.layouts.master')

@section('title')
    Add new movie
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{asset('backend/css/jquery.tagsinput-revisited.css')}}">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
            margin: 0;
        }

        body {
            min-height: 100%;
            font-family: sans-serif;
            padding: 20px;
            margin: 0;
        }

        label {
            display: block;
            padding: 20px 0 5px 0;
        }
    </style>
@endsection

@section('js')
	<script src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
    <script src="{{asset('backend/js/jquery.tagsinput-revisited.js')}}"></script>
	<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $('#types').select2({
            placeholder: "Choose type / types (*)"
        });
        $(function(response) {
            $('#tags').tagsInput({
                'autocomplete': {
                    source: [
                        @foreach($tags as $tag)
                            '{{$tag['name']}}',
                        @endforeach
                    ]
                }
            });
        });
    </script>
@endsection

@section('main__title')
    <h2>Add new movie</h2>
    {{-- <span class="main__title-stat">{{ count($movies) }} Total</span> --}}
    <div class="main__title-wrap">
        <!-- filter sort -->
        <!-- end filter sort -->

        <!-- search -->
        <!-- end search -->
    </div>
    <a href="{{ route('backend.movie.index') }}" class="main__title-link">Movie List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="col-12">
	<form action="{{ route('backend.movie.store') }}" class="form" enctype="multipart/form-data" method="POST">
		@csrf
		<div class="row">
			<div class="col-12 col-md-5 form__cover">
				<div class="row">
					<div class="col-12 col-sm-6 col-md-12">
						<div class="form__img">
							<label for="form__img-upload">Upload cover (270 x 400)</label>
							<input id="form__img-upload" name="card_cover" type="file" accept=".png, .jpg, .jpeg">
							<img id="form__img" src="#" alt=" ">
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-7 form__content">
				<div class="row">
					<div class="col-12 col-lg-6">
						@error('name')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Name(*)" name="name" value="{{ old('name') }}">
					</div>

					<div class="col-12 col-lg-6">
						@error('name_ja')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Janpanese name(*)" name="name_ja" value="{{ old('name_ja') }}">
					</div>

					<div class="col-12">
						@error('description')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<textarea id="text" class="form__textarea" placeholder="Description (*)" name="description">{{ old('description') }}</textarea>
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						@error('release_year')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Release year (*)" name="release_year" value="{{ old('release_year') }}">
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						@error('runtime')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Runtime (minutes) (*)" name="runtime" value="{{ old('runtime') }}">
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						@error('quality')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<select class="js-example-basic-single" id="quality" name="quality">
							@foreach ($quality as $value)
							<option value="{{ $value->order }}" @if( $value->order == old('quality') ) selected @endif>{{ $value->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						@error('age')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Age (*)" name="age" value="{{ old('age') }}">
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						@error('country')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<select class="js-example-basic-multiple" id="country" name="country">
							@foreach ($country as $value)
							<option value="{{ $value->order }}" @if( $value->order == old('country') ) selected @endif>{{ $value->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						@error('genre')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<select class="js-example-basic-multiple" id="genre" name="genre">
							@foreach ($genre as $value)
							<option value="{{ $value->order }}" @if( $value->order == old('genre') ) selected @endif>{{ $value->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-12 col-lg-6">
						@error('types[]')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<select class="js-example-basic-multiple" id="types" multiple="multiple" name="types[]">
							@foreach ($types as $value)
							<option value="{{ $value['id'] }}">{{ $value['title'] }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-12">
						@error('tags')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
                        <input id="tags" name="tags" type="text" value="{{ old('tags') }}" class="form__input">
                    </div>
				</div>
			</div>
			<div class="col-12">
				<div class="row">
					<!-- tv series -->
					<div class="col-12">
						<button type="submit" class="form__btn">publish</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- end content tabs -->
<!-- end users -->
@endsection
