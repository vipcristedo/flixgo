<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">

	<!-- CSS -->
	<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-reboot.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-grid.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/magnific-popup.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/jquery.mCustomScrollbar.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/ionicons.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/admin.css') }}">

	<!-- Favicons -->
	<link rel="icon" type="image/png" href="{{ asset('backend/icon/favicon-32x32.png') }}" sizes="32x32">
	<style type="text/css">.item-link i{ margin-right:15px; width: 14px; } #mCSB_2{width: 100%;} #mCSB_1_container{width: 100%}</style>
	<link rel="apple-touch-icon" href="{{ asset('backend/icon/favicon-32x32.png') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('backend/icon/apple-touch-icon-72x72.png') }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('backend/icon/apple-touch-icon-114x114.png') }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('backend/icon/apple-touch-icon-144x144.png') }}">
	@yield('css')

	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Dmitry Volkov">
	<title>@yield('title')</title>

</head>
<body>

	<!-- header -->
	@include('backend.layouts.header')
	<!-- end header -->

	<!-- sidebar -->
	@include('backend.layouts.sidebar')
	<!-- end sidebar -->

	<!-- main content -->
	<main class="main">
		<div class="container-fluid">
			<div class="row">
				<!-- main title -->
				<div class="col-12">
					<div class="main__title">
						@yield('main__title')
						
						{{-- @yield('main__title') --}}
					</div>
				</div>
				<!-- end main title -->

				<!-- users -->
				@yield('content')
				{{-- @yield('content') --}}
			</div>
		</div>
	</main>
	<!-- end main content -->
	@yield('modal')
	<!-- modal status -->
	<div id="modal-status" class="zoom-anim-dialog mfp-hide modal">
		<h6 class="modal__title">Status change</h6>

		<p class="modal__text">Are you sure about immediately change status?</p>

		<div class="modal__btns">
			<button class="modal__btn modal__btn--apply" type="button">Apply</button>
			<button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
		</div>
	</div>
	<!-- end modal status -->

	<!-- modal delete -->
	<div id="modal-delete" class="zoom-anim-dialog mfp-hide modal">
		<h6 class="modal__title">Item delete</h6>

		<p class="modal__text">Are you sure to permanently delete this item?</p>

		<div class="modal__btns">
			<button class="modal__btn modal__btn--apply" type="button">Delete</button>
			<button class="modal__btn modal__btn--dismiss" type="button">Dismiss</button>
		</div>
	</div>
	<!-- end modal delete -->

	<!-- JS -->
	<script src="{{ asset('backend/js/jquery-3.4.1.min.js') }}"></script>
	<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('backend/js/jquery.magnific-popup.min.js') }}"></script>
	<script src="{{ asset('backend/js/jquery.mousewheel.min.js') }}"></script>
	<script src="{{ asset('backend/js/jquery.mCustomScrollbar.min.js') }}"></script>
	<script src="{{ asset('backend/js/select2.min.js') }}"></script>
	<script src="{{ asset('backend/js/admin.js') }}"></script>
	@yield('js')
</body>
</html>