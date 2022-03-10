@extends('frontend.layouts.master')
@section('header')
    @include('frontend.layouts.main_header')
@endsection
@section('header_content')
    <section class="home home--bg">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="home__title">{!! __('nominations film') !!}</h1>

                    <button class="home__nav home__nav--prev" type="button">
                        <i class="icon ion-ios-arrow-round-back"></i>
                    </button>
                    <button class="home__nav home__nav--next" type="button">
                        <i class="icon ion-ios-arrow-round-forward"></i>
                    </button>
                </div>

                <div class="col-12">
                    <div class="owl-carousel home__carousel">
                        @foreach($video_nominations as $video_nomination)
                            <div class="item">
                                <!-- card -->
                                <div class="card card--big">
                                    <div class="card__cover">
                                        <img
                                            src="{{asset($video_nomination['card_cover'])}}"
                                            alt="">
                                        <a href="{{route('frontend.movie',[$video_nomination['slug'],1])}}"
                                           class="card__play">
                                            <i class="icon ion-ios-play"></i>
                                        </a>
                                    </div>
                                    <div class="card__content">
                                        <h3 class="card__title"><a
                                                href="{{route('frontend.movie',[$video_nomination['slug'],1])}}">@if(\Session::get('language') =='ja'){{$video_nomination['name_ja']}}@else{{$video_nomination['name']}}@endif</a>
                                        </h3>
                                        <span class="card__category">
                                                    @foreach($video_nomination['types'] as $type)
                                                <a href="{{route('frontend.catalog',['type',$type['slug']])}}">{{__($type['title'])}}</a>
                                            @endforeach
									                </span>
{{--                                        <span class="card__rate"><i class="icon ion-ios-star"></i>{{$video_nomination['rate']}}</span>--}}
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- content title -->
                        <h2 class="content__title">{{__('New Film')}}</h2>
                        <!-- end content title -->

                        <!-- content tabs nav -->
                        <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab"
                                   aria-controls="tab-1" aria-selected="true">{{__('New Releases')}}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-4" role="tab" aria-controls="tab-4"
                                   aria-selected="false">{{__('Cartoons')}}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2"
                                   aria-selected="false">{{__('Movies')}}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3"
                                   aria-selected="false">{{__('TV Series')}}</a>
                            </li>


                        </ul>
                        <!-- end content tabs nav -->

                        <!-- content mobile tabs nav -->
                        <div class="content__mobile-tabs" id="content__mobile-tabs">
                            <div class="content__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs"
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <input type="button" value="New items">
                                <span></span>
                            </div>

                            <div class="content__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" data-value="new releases"><a class="nav-link active" id="1-tab"
                                                                                      data-toggle="tab" href="#tab-1"
                                                                                      role="tab" aria-controls="tab-1"
                                                                                      aria-selected="true">{{__('New Releases')}}</a></li>

                                    <li class="nav-item" data-value="cartoons"><a class="nav-link" id="4-tab"
                                                                                  data-toggle="tab" href="#tab-4"
                                                                                  role="tab" aria-controls="tab-4"
                                                                                  aria-selected="false">{{__('Cartoons')}}</a>
                                    </li>

                                    <li class="nav-item" data-value="movies"><a class="nav-link" id="2-tab"
                                                                                data-toggle="tab" href="#tab-2"
                                                                                role="tab" aria-controls="tab-2"
                                                                                aria-selected="false">{{__('Movies')}}</a></li>

                                    <li class="nav-item" data-value="tv series"><a class="nav-link" id="3-tab"
                                                                                   data-toggle="tab" href="#tab-3"
                                                                                   role="tab" aria-controls="tab-3"
                                                                                   aria-selected="false">{{__('TV Series')}}</a>
                                    </li>


                                </ul>
                            </div>
                        </div>
                        <!-- end content mobile tabs nav -->
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- content tabs -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                    <div class="row">
                        <!-- card -->
                        @foreach($news as $new)
                            <div class="col-6 col-sm-12 col-lg-6">
                                <div class="card card--list">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="card__cover">
                                                <img src="{{asset($new['card_cover'])}}"
                                                     alt="">
                                                <a href="{{route('frontend.movie',[$new['slug'],1])}}" class="card__play">
                                                    <i class="icon ion-ios-play"></i>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-8">
                                            <div class="card__content">
                                                <h3 class="card__title"><a
                                                        href="{{route('frontend.movie',[$new['slug'],1])}}">@if(\Session::get('language') =='ja'){{$new['name_ja']}}@else{{$new['name']}}@endif</a>
                                                </h3>
                                                <span class="card__category">
                                                @foreach($new['types'] as $type)
                                                        <a href="{{route('frontend.catalog',['type',$type['slug']])}}">{{__($type['title'])}}</a>
                                                @endforeach
											    </span>

                                                <div class="card__wrap">
{{--                                                    <span class="card__rate"><i class="icon ion-ios-star"></i>{{$new['rate']}}</span>--}}
                                                    <ul class="card__list">
                                                        <li>{{$new['quality']}}</li>
                                                        <li>{{$new['age']}}+</li>
                                                    </ul>
                                                </div>

                                                <div class="card__description">
                                                    <p>{{$new['description']}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- end card -->
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
                    <div class="row">
                        <!-- card -->
                        @foreach($movies as $movie)
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                                <div class="card">
                                    <div class="card__cover">
                                        <img src="{{asset($movie['card_cover'])}}" alt="">
                                        <a href="{{route('frontend.movie',[$movie['slug'],1])}}" class="card__play">
                                            <i class="icon ion-ios-play"></i>
                                        </a>
                                    </div>
                                    <div class="card__content">
                                        <h3 class="card__title"><a
                                                href="{{route('frontend.movie',[$movie['slug'],1])}}">@if(\Session::get('language') =='ja'){{$movie['name_ja']}}@else{{$movie['name']}}@endif</a>
                                        </h3>
                                        <span class="card__category">
                                        @foreach($movie['types'] as $type)
                                                <a href="{{route('frontend.catalog',['type',$type['slug']])}}">{{__($type['title'])}}</a>
                                            @endforeach
									</span>
{{--                                        <span class="card__rate"><i--}}
{{--                                                class="icon ion-ios-star"></i>{{$movie['rate']}}</span>--}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    <!-- end card -->
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="3-tab">
                    <div class="row">
                        @foreach($tv_sereis as $sereis)
                            <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                                <div class="card">
                                    <div class="card__cover">
                                        <img src="{{asset($sereis['card_cover'])}}" alt="">
                                        <a href="{{route('frontend.movie',[$sereis['slug'],1])}}" class="card__play">
                                            <i class="icon ion-ios-play"></i>
                                        </a>
                                    </div>
                                    <div class="card__content">
                                        <h3 class="card__title"><a href="{{route('frontend.movie',[$sereis['slug'],1])}}">@if(\Session::get('language') =='ja'){{$sereis['name_ja']}}@else{{$sereis['name']}}@endif</a></h3>
                                        <span class="card__category">
                                        @foreach($sereis['types'] as $type)
                                                <a href="{{route('frontend.catalog',['type',$type['slug']])}}">{{__($type['title'])}}</a>
                                        @endforeach
									    </span>
{{--                                        <span class="card__rate"><i--}}
{{--                                                class="icon ion-ios-star"></i>{{$sereis['rate']}}</span>--}}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                 <div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="4-tab">
                    <div class="row">
                        @foreach($cartoons as $cartoon)
                                <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                                    <div class="card">
                                        <div class="card__cover">
                                            <img src="{{asset($cartoon['card_cover'])}}" alt="">
                                            <a href="{{route('frontend.movie',[$cartoon['slug'],1])}}" class="card__play">
                                                <i class="icon ion-ios-play"></i>
                                            </a>
                                        </div>
                                        <div class="card__content">
                                            <h3 class="card__title"><a href="{{route('frontend.movie',[$cartoon['slug'],1])}}">@if(\Session::get('language') =='ja'){{$cartoon['name_ja']}}@else{{$cartoon['name']}}@endif</a></h3>
                                            <span class="card__category">
                                            @foreach($cartoon['types'] as $type)
                                                        <a href="{{route('frontend.movie',$type['slug'])}}">{{__($type['title'])}}</a>
                                            @endforeach
									        </span>
{{--                                            <span class="card__rate"><i--}}
{{--                                                    class="icon ion-ios-star"></i>{{$cartoon['rate']}}</span>--}}
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- end content tabs -->
        </div>
    </section>
@endsection
