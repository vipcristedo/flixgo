
@extends('backend.layouts.master')

@section('title')
    Add new manga
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css') }}">

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
	<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
	<script src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript">
        $('#types').select2({
            placeholder: "Choose type / types"
        });
    </script>
@endsection

@section('main__title')
    <h2>Add new manga</h2>
    <a href="{{ route('backend.manga.index') }}" class="main__title-link">Manga List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="col-12">
	<form action="{{ route('backend.manga.store') }}" class="form" enctype="multipart/form-data" method="POST">
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
						<input type="text" class="form__input" placeholder="Name" name="name" value="{{ old('name') }}">
					</div>

					<div class="col-12 col-lg-6">
						@error('author')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Author (default: Anomynous)" name="author" value="{{ old('author') }}">
					</div>

					<div class="col-12">
						@error('description')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<textarea id="text" class="form__textarea" placeholder="Description" name="description" style="height: 195px">{{ old('description') }}</textarea>
					</div>

					<div class="col-12 col-sm-3 col-lg-3">
						@error('release_year')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Realease year" name="release_year" value="{{ old('release_year') }}">
					</div>

					<div class="col-12 col-sm-3 col-lg-3">
						@error('age')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<input type="text" class="form__input" placeholder="Age" name="age" value="{{ old('age') }}">
					</div>

					<div class="col-12 col-sm-6 col-lg-6">
						@error('country')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<select class="js-example-basic-multiple" id="country" name="country">
							@foreach ($country as $value)
							<option value="{{ $value->order }}" @if( $value->order == old('country') ) selected @endif>{{ $value->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-12 col-lg-12">
						@error('types[]')
                        <div class="main__table-text--red">{{ $message }}</div>
                        @enderror
						<select class="js-example-basic-multiple" id="types" multiple="multiple" name="types[]">
							@foreach ($types as $value)
							<option value="{{ $value['id'] }}">{{ $value['title'] }}</option>
							@endforeach
						</select>
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
