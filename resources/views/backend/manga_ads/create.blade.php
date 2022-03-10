
@extends('backend.layouts.master')

@section('title')
    Add new ad
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
<script type="text/javascript">
	function getIMG(){
		document.getElementById("artical_img").src = $("#img_link").val();
	}
	function errorIMG(){
		document.getElementById('artical_img').src = 'https://developers.google.com/maps/documentation/streetview/images/error-image-generic.png?authuser=2&hl=vi';
	}	
	$("#img_link").on('change', function (e) {
	    e.preventDefault();
		getIMG();
		document.getElementById('artical_img').onerror = function(e) { e.preventDefault();errorIMG()};
	});
</script>
@endsection

@section('main__title')
    <h2>Add new ad</h2>
    <a href="{{ route('backend.manga_ad.index') }}" class="main__title-link">Ad List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="col-12">
	<form action="{{ route('backend.manga_ad.store') }}" class="form" enctype="multipart/form-data" method="POST">
		@csrf
		<div class="row">
			<div class="col-12 col-lg-6">
				<label class="profile__label">Link 
                    @error('link')
                    <span class="main__table-text--red">({{ $message }})</span>
                    @enderror
                </label>
				<input type="text" class="form__input" placeholder="Required .. (Ex: https://bit.ly/abcxyz)" name="link" value="{{ old('link') }}">
			</div>

			<div class="col-12 col-lg-6">
				<label class="profile__label">Artical 
                    @error('artical')
                    <span class="main__table-text--red">({{ $message }})</span>
                    @enderror
                </label>
				<input type="text" class="form__input" placeholder="Required .. (Ex: https://i.pinimg.com/originals/ec/13/37/ec1337bdc84a8c8cf8cf20757c0a38b4.jpg)" name="artical" id="img_link" value="{{ old('artical') }}">
			</div>

			<div class="col-12">
				<img id="artical_img" src="hi.img" alt="" style="width: 200px">
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
