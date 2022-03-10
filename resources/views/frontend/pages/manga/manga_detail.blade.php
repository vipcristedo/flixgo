@extends('frontend.layouts.master')
@section('header')
    @include('frontend.layouts.main_header_manga')
@endsection
@section('header_content')
    <section class="section details">
        <!-- details background -->
        <div class="details__bg" data-bg=""></div>
        <!-- end details background -->

        <!-- details content -->
        <div class="container">
            <div class="row">
                <!-- title -->
                <div class="col-12">
                    <h1 class="details__title">{{ $manga['name'] }}</h1>
                </div>
                <!-- end title -->

                <!-- content -->
                <div class="col-10">
                    <div class="card card--details card--series">
                        <div class="row">
                            <!-- card cover -->
                            <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                <div class="card__cover">
                                    <img src="{{asset($manga['card_cover']) }}" alt="">
                                </div>
                            </div>
                            <!-- end card cover -->

                            <!-- card content -->
                            <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-9">
                                <div class="card__content">
                                    <div class="card__wrap">
                                        <span class="card__rate"><i
                                                class="icon ion-ios-star"></i>{{ $manga['rate'] }}</span>

                                        <ul class="card__list">
                                            <li>{{ $manga['age'] }}+</li>
                                        </ul>
                                    </div>

                                    <ul class="card__meta">
                                        <li><span>{{__('Genre')}}:</span>
                                            @foreach($manga['types'] as $type)
                                                <a href="{{route('frontend.catalog',['$type',$type['slug']])}}">{{ __($type['title']) }}</a>
                                        @endforeach
                                        <li><span>{{__('Release year')}}:</span> {{ $manga['release_year'] }}</li>
                                        <li><span>{{__('Total chap')}}:</span> {{ $manga['total_chap'] }}</li>
                                        <li><span>{{__('Country')}}:</span> <a href="{{route('frontend.manga.catalog',['$country',$manga['country']])}}">{{ $manga['country'] }}</a></li>

                                    </ul>

                                    <div class="card__description card__description--details">
                                        {{ $manga['description'] }}
                                    </div>
                                </div>
                            </div>
                            <!-- end card content -->
                        </div>
                    </div>
                </div>
                <!-- end content -->
                <div class="col-12">
                    <div class="accordion" id="accordion">
                            <div class="accordion__card">
                                <div class="card-header" id="headingTwo">
                                    <button class="collapsed" type="button" data-toggle="collapse"
                                            data-target="#collapse" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                        <span>List Chapter</span>
                                    </button>
                                </div>

                                <div id="collapse" class="collapse show" aria-labelledby="headingTwo"
                                     data-parent="#accordion">
                                    <div class="card-body">

                                        <table class="accordion__list">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Title')}}</th>

                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($manga['chaps'] as $chap)
                                                <tr>
                                                    <th>{{$chap['chap']}}</th>
                                                    <td><a href="{{route('frontend.manga.chapter',[$manga['slug'],$chap['slug']])}}">{{$chap['name']}}</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                    <!-- end accordion -->
                    </div>
                </div>

                <!-- end player -->


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
                        <h2 class="content__title">{{__('comments')}}</h2>
                        <!-- end content title -->

                        <!-- content tabs nav -->
                        <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab"
                                   aria-controls="tab-1" aria-selected="true">{{__('comments')}}</a>
                            </li>

                        </ul>
                        <!-- end content tabs nav -->

                        <!-- content mobile tabs nav -->
                        <div class="content__mobile-tabs" id="content__mobile-tabs">
                            <div class="content__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs"
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <input type="button" value="Comments">
                                <span></span>
                            </div>

                            <div class="content__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" data-value="comments"><a class="nav-link active" id="1-tab"
                                                                                  data-toggle="tab" href="#tab-1"
                                                                                  role="tab" aria-controls="tab-1"
                                                                                  aria-selected="true">{{__('comments')}}</a>
                                    </li>

                            </div>
                        </div>
                        <!-- end content mobile tabs nav -->
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 col-xl-8">
                    <!-- content tabs -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                            <div class="row">
                                <!-- comments -->
                                <div class="col-12">
                                    <div class="comments">
                                        <ul class="comments__list">
                                            <li class="comments__item">
                                                <div class="comments__autor">
                                                    <img class="comments__avatar" src="/frontend/img/user.svg" alt="">
                                                    <span class="comments__name">John Doe</span>
                                                    <span class="comments__time">30.08.2018, 17:53</span>
                                                </div>
                                                <p class="comments__text">There are many variations of passages of Lorem
                                                    Ipsum available, but the majority have suffered alteration in some
                                                    form, by injected humour, or randomised words which don't look even
                                                    slightly believable. If you are going to use a passage of Lorem
                                                    Ipsum, you need to be sure there isn't anything embarrassing hidden
                                                    in the middle of text.</p>
                                                <div class="comments__actions">
                                                    <div class="comments__rate">
                                                        <button type="button"><i class="icon ion-md-thumbs-up"></i>12
                                                        </button>

                                                        <button type="button">7<i class="icon ion-md-thumbs-down"></i>
                                                        </button>
                                                    </div>

                                                    <button type="button"><i
                                                            class="icon ion-ios-share-alt"></i>{{__('Reply')}}
                                                    </button>
                                                    <button type="button"><i
                                                            class="icon ion-ios-quote"></i>{{__('Quote')}}
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>

                                        <form action="#" class="form">
                                            <textarea id="text" name="text" class="form__textarea"
                                                      placeholder="{{__('Add comment')}}"></textarea>
                                            <button type="button" class="form__btn">{{__('Send')}}</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- end comments -->
                            </div>
                        </div>


                    </div>
                    <!-- end content tabs -->
                </div>

                <!-- sidebar -->
                <div class="col-12 col-lg-4 col-xl-4">
                    <div class="row">
                        <!-- section title -->
                        <div class="col-12">
                            <h2 class="section__title section__title--sidebar">{{__('You may also like')}}...</h2>
                        </div>

                    </div>
                </div>
                <!-- end sidebar -->
            </div>
        </div>
    </section>
@endsection

