@extends('frontend.layouts.master')
@section('header')
    @include('frontend.layouts.main_header')
@endsection
@section('header_content')
    <section class="section section--first section--bg" data-bg=""
             style="background: url(&quot;img/section/section.jpg&quot;) center center / cover no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__wrap">
                        <!-- section title -->
                        <h2 class="section__title">{{__('List Film')}}</h2>
                        <!-- end section title -->

                        <!-- breadcrumb -->
                        <ul class="breadcrumb">
                            <li class="breadcrumb__item"><a href="{{route('frontend.home')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb__item breadcrumb__item--active">{{__('List')}}</li>
                        </ul>
                        <!-- end breadcrumb -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('filter')
    <div class="filter">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('frontend.filter')}}" method="get" class="filter__content">
                        @csrf

                        <div class="filter__items">
                            <!-- filter item -->
                            <div class="filter__item" id="filter__types">
                                <span class="filter__item-label">{{__('Genre')}}:</span>
                                <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-types"
                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <input type="button" name="type"  class="show" value="{{isset($params['type']) ? __($params['type']['title']) : __('All')}}">
                                    <input type="text" name="type" class="request"  value="{{isset($params['type']) ? __($params['type']['id']) : ""}}" hidden>
                                    <span></span>
                                </div>

                                <ul class="filter__item-menu dropdown-menu scrollbar-dropdown mCustomScrollbar _mCS_1"
                                    aria-labelledby="filter-types"
                                    style="overflow: visible; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);"
                                    x-placement="bottom-start">
                                    <div id="mCSB_1" class="mCustomScrollBox mCS-custom-bar mCSB_vertical mCSB_outside"
                                         tabindex="0">
                                        <div id="mCSB_1_container" class="mCSB_container"
                                             style="position: relative; top: 0px; left: 0px;" dir="ltr">
                                            <li data-value="action/adventure" data-filter="">{{__('All')}}</li>
                                            @foreach($filters['types'] as $type)
                                                <li data-value="action/adventure" data-filter="{{$type['id']}}">{{ __($type['title'])}}</li>
                                            @endforeach
                                        </div>
                                    </div>
                                </ul>
                            </div>
                            <!-- end filter item -->

                            <!-- filter item -->
                            <div class="filter__item" id="filter__genre">
                                <span class="filter__item-label">{{__('Types')}}:</span>

                                <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-genre"
                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <input type="button" class="show" value="{{isset($params['genre']) ? __($params['genre']['name']) : __('All')}}" name="genre">
                                    <input type="text" class="request" value="{{isset($params['genre']) ? __($params['genre']['order']) : ""}}" name="genre" hidden>
                                    <span></span>
                                </div>

                                <ul class="filter__item-menu dropdown-menu scrollbar-dropdown mCustomScrollbar _mCS_1"
                                    aria-labelledby="filter-genre"
                                    style="overflow: visible; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);"
                                    x-placement="bottom-start">
                                    <div id="mCSB_1" class="mCustomScrollBox mCS-custom-bar mCSB_vertical mCSB_outside"
                                         style="max-height: 160px;" tabindex="0">
                                        <div id="mCSB_1_container" class="mCSB_container"
                                             style="position: relative; top: 0px; left: 0px;" dir="ltr">
                                                <li data-value="action/adventure" data-filter="">{{__('All')}}</li>
                                                @foreach($filters['genre'] as $genre)
                                                    <li data-value="action/adventure" data-filter="{{$genre['order']}}">{{ __($genre['name'])}}</li>
                                                @endforeach
                                        </div>
                                    </div>
                                </ul>
                            </div>
                            <!-- end filter item -->

                            <!-- filter item -->
                            <div class="filter__item" id="filter__country">
                                <span class="filter__item-label">{{__('Country')}}:</span>

                                <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-country"
                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <input type="button" class="show" value="{{isset($params['country']) ? __($params['country']['name']) : __('All')}}" name="country">
                                    <input type="text" class="request" value="{{isset($params['country']) ? __($params['country']['order']) : ""}}" name="country" hidden>
                                    <span></span>
                                </div>

                                <ul class="filter__item-menu dropdown-menu scrollbar-dropdown mCustomScrollbar _mCS_2 mCS_no_scrollbar"
                                    aria-labelledby="filter-country"
                                    style="overflow: visible; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);"
                                    x-placement="bottom-start">
                                    <div id="mCSB_2" class="mCustomScrollBox mCS-custom-bar mCSB_vertical mCSB_outside"
                                         tabindex="0" style="max-height: none;">
                                        <div id="mCSB_2_container"
                                             class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y"
                                             style="position:relative; top:0; left:0;" dir="ltr">
                                                    <li data-value="action/adventure" data-filter="">{{__('All')}}</li>
                                                    @foreach($filters['country'] as $country)
                                                        <li data-value="action/adventure" data-filter="{{$country['order']}}">{{ __($country['name'])}}</li>
                                                    @endforeach
                                        </div>
                                    </div>
                                </ul>
                            </div>
                            <!-- end filter item -->

                        </div>

                        <!-- filter btn -->
                        <button class="filter__btn" type="submit">{{__('apply filter')}}</button>
                        <!-- end filter btn -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="catalog">
        <div class="container">
            <div class="row">
            @foreach($movies as $movie)
                <!-- card -->
                    <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                        <div class="card">
                            <div class="card__cover">
                                <img src="{{asset($movie['card_cover'])}}" alt="">
                                <a href="{{route('frontend.movie',[$movie['slug'],1])}}" class="card__play">
                                    <i class="icon ion-ios-play"></i>
                                </a>
                            </div>
                            <div class="card__content">
                                <h3 class="card__title"><a href="{{route('frontend.movie',[$movie['slug'],1])}}">@if(session::get('language') =='ja'){{$movie['name_ja']}}@else{{$movie['name']}}@endif</a></h3>
                                <span class="card__category">
                                    @foreach($movie['type'] as $type)
                                        <a href="{{route('frontend.catalog',['type',$type['slug']]) }}">{{__($type['title'])}}</a>
                                    @endforeach
                                </span>
{{--                                <span class="card__rate"><i class="icon ion-ios-star"></i>{{$movie['rate']}}</span>--}}
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
            @endforeach
            <!-- end card -->



                <!-- paginator -->

                <div class="col-12">
                    {!! $movies->render('vendor.pagination.custom_view') !!}
                </div>
                <!-- end paginator -->
            </div>
        </div>
    </div>
@endsection
