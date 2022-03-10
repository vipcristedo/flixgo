<header class="header">
    <div class="header__wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header__content">
                        <!-- header logo -->
                        <a href="{{route('frontend.manga.home')}}" class="header__logo">
                            <img src="{{asset('frontend/img/LOGO.png')}}" alt="">
                        </a>
                        <!-- end header logo -->

                        <!-- header nav -->
                        <ul class="header__nav">
                            <!-- dropdown -->
                            <li class="header__nav-item">
                                <a class="dropdown-toggle header__nav-link" href="{{route('frontend.manga.home')}}">{{ __('Home') }}</a>
                            </li>
                            <!-- end dropdown -->

                            <!-- dropdown -->
                            <li class="header__nav-item">
                                <a class="dropdown-toggle header__nav-link" href="#" role="button"
                                   id="dropdownMenuCatalog" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">{{__('Genre')}}</a>

                                <ul class="dropdown-menu header__dropdown-menu" aria-labelledby="dropdownMenuCatalog">
                                    @foreach($main_menu['types_manga'] as $type)
                                        <li><a href="{{route('frontend.manga.catalog',['type',$type['slug']])}}">{{__($type['title'])}}</a></li>
                                    @endforeach
                                </ul>
                            </li>


                            <!-- end dropdown -->

                            <li class="header__nav-item">
                                <a class="dropdown-toggle header__nav-link" href="#" role="button"
                                   id="dropdownMenuCatalog" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">{{__('Country')}}</a>
                                <ul class="dropdown-menu header__dropdown-menu" aria-labelledby="dropdownMenuCatalog">
                                    @foreach($main_menu['country'] as $country)
                                        <li><a href="{{route('frontend.manga.catalog',['country',$country['order']])}}">{{__($country['name'])}}</a></li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="header__nav-item">
                                <a class="dropdown-toggle header__nav-link" href="{{route('frontend.home')}}" >{{__('Movie')}}</a>
                            </li>

                            <!-- end dropdown -->
                        </ul>
                        <!-- end header nav -->

                        <!-- header auth -->
                        <div class="header__auth">
                            <button class="header__search-btn" type="button">
                                <i class="icon ion-ios-search"></i>
                            </button>

                            <!-- dropdown -->
                            <div class="dropdown header__lang">
                                <a class="dropdown-toggle header__nav-link" href="#" role="button" id="dropdownMenuLang"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Session::has('language') ? Session::get('language') : 'en'}}</a>

                                <ul class="dropdown-menu header__dropdown-menu" aria-labelledby="dropdownMenuLang">
                                    <li><a href="{{route('frontend.language',['en'])}}">{{__('English')}}</a></li>
                                    <li><a href="{{route('frontend.language',['vi'])}}">{{__('Vietnam')}}</a></li>
                                    <li><a href="{{route('frontend.language',['ja'])}}">{{__('Japan')}}</a></li>
                                </ul>
                            </div>
                            <!-- end dropdown -->

{{--                            <a href="signin.html" class="header__sign-in">--}}
{{--                                <i class="icon ion-ios-log-in"></i>--}}
{{--                                <span>{{__('Sign in')}}</span>--}}
{{--                            </a>--}}
                        </div>
                        <!-- end header auth -->

                        <button class="header__btn" type="button">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- header search -->
    <form action="{{route('frontend.manga.search')}}" class="header__search" method="get">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header__search-content">
                        <input type="text" name="search" placeholder="{{__('Search for manga that you are looking for')}}">
                        <button type="submit">{{__('search')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end header search -->
</header>
