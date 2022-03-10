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
                        <h2 class="section__title">{{__('Search')}}: {{$key}}</h2>
                        <!-- end section title -->

                        <!-- breadcrumb -->
                        <ul class="breadcrumb">
                            <li class="breadcrumb__item"><a href="{{route('frontend.home')}}">Home</a></li>
                            <li class="breadcrumb__item breadcrumb__item--active">{{$key}}</li>
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
                                    @foreach($movie['types'] as $type)
                                        <a href="{{route('frontend.catalog',['type',$type['slug']])  }}">{{$type['title']}}</a>
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

