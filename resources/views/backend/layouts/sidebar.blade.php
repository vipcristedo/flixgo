<div class="sidebar ">
		<!-- sidebar logo -->
		<a href="{{route('backend.home')}}" class="sidebar__logo">
			<img src="{{ asset('backend/img/LOGO.png') }}" alt="">
		</a>
		<!-- end sidebar logo -->

		<!-- sidebar user -->
		<div class="sidebar__user">
			<div class="sidebar__user-img">
				<img src="{{ asset('backend/img/user.svg') }}" alt="">
			</div>

			<div class="sidebar__user-title">
				<span>Admin</span>
				<p>{{Auth::user()->name}}</p>
			</div>

			<a class="sidebar__user-btn btn" href="{{route('logout')}}" type="button">
				<i class="icon ion-ios-log-out"></i>
			</a>
		</div>
		<!-- end sidebar user -->

		<!-- sidebar nav -->
		<ul class="sidebar__nav scroll-y">
			<li class="sidebar__nav-item">
				<a href="{{ route('backend.home') }}" class="sidebar__nav-link"><i class="icon ion-ios-keypad"></i> Dashboard</a>
			</li>

            <li class="sidebar__nav-item" style="color:rgba(255,255,255,0.75);">Admin</li>
			<li class="sidebar__nav-item">
				<a href="{{ route('backend.admin.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-contacts"></i>admin List</a>
			</li>

            <li class="sidebar__nav-item">
                <a href="{{ route('backend.role.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-people"></i>Role List</a>
            </li>

			<li class="sidebar__nav-item">
				<a href="{{ route('backend.permission.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-md-person"></i>Permission List</a>
			</li>

            <li class="sidebar__nav-item" style="color:rgba(255,255,255,0.75);">Movie</li>
            <li class="sidebar__nav-item">
                <a href="{{ route('backend.channel.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-logo-youtube"></i>channel List</a>
            </li>
            
            <li class="sidebar__nav-item">
                <a href="{{ route('backend.movie.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-film"></i>Movie List</a>
            </li>

            <li class="sidebar__nav-item">
                <a href="{{ route('backend.playlist.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-play"></i>playlist List</a>
            </li>

            <li class="sidebar__nav-item">
                <a href="{{ route('backend.video.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-videocam"></i>Video List</a>
            </li>

            <li class="sidebar__nav-item" style="color:rgba(255,255,255,0.75);">Manga</li>
            <li class="sidebar__nav-item">
                <a href="{{ route('backend.manga.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-book"></i>Manga List</a>
            </li>

            <li class="sidebar__nav-item">
                <a href="{{ route('backend.chapter.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-ios-bookmark"></i>Chapter List</a>
            </li>

            <li class="sidebar__nav-item">
                <a href="{{ route('backend.manga_ad.index') }}" class="sidebar__nav-link item-link"><i class="icon ion-logo-usd"></i>Ad List</a>
            </li>

            <li class="sidebar__nav-item">
                <a href="{{ route('backend.type.index') }}" class="sidebar__nav-link"><i class="icon ion-ios-keypad"></i>type List</a>
            </li>

			{{-- <li class="sidebar__nav-item">
				<a href="{{ route('backend.user.index') }}" class="sidebar__nav-link"><i class="icon ion-ios-film"></i>user List</a>
			</li> --}}
			<!-- end dropdown -->
		</ul>
		<!-- end sidebar nav -->

		<!-- sidebar copyright -->
		<div class="sidebar__copyright">{{ env('FOOTER', 'ZENT VN') }}</div>
		<!-- end sidebar copyright -->
	</div>
