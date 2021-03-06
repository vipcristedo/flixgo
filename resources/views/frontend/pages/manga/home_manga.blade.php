@extends('frontend.layouts.master')
@section('header')
    @include('frontend.layouts.main_header_manga')
@endsection
@section('header_content')
<section class="home home--bg">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="home__title">{!! __('nominations manga') !!}</h1>

                <button class="home__nav home__nav--prev" type="button">
                    <i class="icon ion-ios-arrow-round-back"></i>
                </button>
                <button class="home__nav home__nav--next" type="button">
                    <i class="icon ion-ios-arrow-round-forward"></i>
                </button>
            </div>

            <div class="col-12">
                <div class="owl-carousel home__carousel">
                    @foreach($manga_nominations as $manga_nomination)
                    <div class="item">
                        <!-- card -->
                        <div class="card card--big">
                            <div>


                                <a href="{{route('frontend.manga.detail',$manga_nomination['slug'])}}">
                                    <img
                                        src="{{asset($manga_nomination['card_cover'])}}"
                                        alt="">
                                </a>
                        </div>
                        <div class="card__content">
                            <h3 class="card__title">
                                <a href="{{route('frontend.manga.detail',$manga_nomination['slug'])}}">{{$manga_nomination['name']}}</a>
                            </h3>
                            <span class="card__category">
                                @foreach($manga_nomination['types'] as $type)
                                <a href="{{route('frontend.catalog',['type',$type['slug']])}}">{{__($type['title'])}}</a>
                                @endforeach
                            </span>
                            <span class="card__rate"><i class="icon ion-ios-star"></i>{{$manga_nomination['rate']}}</span>
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
                    <h2 class="content__title">{{__('New Manga')}}</h2>
                    <!-- end content title -->

                    <!-- content tabs nav -->
                       {{--  <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
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


                        </ul> --}}
                        <!-- end content tabs nav -->

                        <!-- content mobile tabs nav -->
                        {{-- <div class="content__mobile-tabs" id="content__mobile-tabs">
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
                        </div> --}}
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
                                        <div class="card__cover_manga">

                                            <a href="{{route('frontend.manga.detail',$new['slug'])}}" class="">
                                                <img src="{{asset($new['card_cover'])}}"
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-8">
                                        <div class="card__content">
                                            <h3 class="card__title"><a
                                                href="{{route('frontend.manga.detail',$new['slug'])}}">{{$new['name']}}</a>
                                            </h3>
                                            <span class="card__category">
                                                @foreach($new['types'] as $type)
                                                <a href="{{route('frontend.manga.catalog',['type',$type['slug']])}}">{{($type['title'])}}</a>
                                                @endforeach
                                            </span>

                                            <div class="card__wrap">
                                                <span class="card__rate"><i class="icon ion-ios-star"></i>{{$new['rate']}}</span>

                                                <ul class="card__list">
                                                    <li>{{$new['author']}}</li>
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

            </div>
            <!-- end content tabs -->
        </div>
    </section>
    @endsection
