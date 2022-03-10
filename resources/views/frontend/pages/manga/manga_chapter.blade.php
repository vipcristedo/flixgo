@extends('frontend.layouts.master')
@section('header')
    @include('frontend.layouts.main_header_manga')
@endsection
@section('header_content')
    <section class="section section--first section--bg" data-bg=""
             style="background: url(&quot;img/section/section.jpg&quot;) center center / cover no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__wrap">
                        <!-- section title -->
                        <h2 class="section__title"><a
                                href="{{route('frontend.manga.detail',$manga['slug'])}}">{{$manga['name']}}</a>{{'>'.$chapter['current']['name']}}
                        </h2>
                        <!-- end section title -->

                        <!-- end breadcrumb -->
                    </div>
                </div>

                <div class="col-12" style="margin-top: 20px">
                    <div class="row">
                        <div style="margin: 0 auto">
                            @if(isset($chapter['pre']))
                                <a href="{{route('frontend.manga.chapter',[$manga['slug'],$chapter['pre']['slug']])}}"
                                   class="chap_btn" style=" margin-right: 3px;">
                                    <i class="icon ion-ios-arrow-back"></i>
                                </a>
                            @endif
                            <select name="" class="chap_list" id="chap_redirect" data-manga="{{$manga['slug']}}">
                                @foreach($chapters as $chap)
                                    <option value="{{$chap['slug']}}"
                                            @if($chap['id'] == $chapter['current']['id']) selected @endif>{{$chap['name']}}</option>
                                @endforeach
                            </select>
                            @if(isset($chapter['next']))
                                <a href="{{route('frontend.manga.chapter',[$manga['slug'],$chapter['next']['slug']])}}"
                                   class="chap_btn">
                                    <i class="icon ion-ios-arrow-forward"></i>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <section class="section details">
        <!-- details background -->
        <div class="details__bg" data-bg=""></div>
        <!-- end details background -->

        <!-- details content -->
        <div class="container">
            <div class="row">

                @if($ads !=null && env('ADS_HEADER_FOOTER')==true)
                    <div class="col-12">
                        <div class="">
                            <div class="row">
                                <!-- card cover -->
                                <div style="margin: 0 auto">
                                    <div class="">
                                        <a href="{{$ads[rand(0,count($ads)-1)]['link']}}" target="_blank">
                                            <img style="max-width: 819px"
                                                 src="{{asset($ads[rand(0,count($ads)-1)]['artical'])}}" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            <!-- content -->
                <div class="col-12">
                    <div class="">
                        @php
                            $i = 0;$vt = 0;
                        @endphp
                        @for(;$i<count($pictures);$i++)
                            <div class="row">
                                <!-- card cover -->
                                <div style="margin: 0 auto">
                                    <div class="">
                                        <img src="{{asset($pictures[$i]['link'])}}" alt="">
                                    </div>
                                </div>
                            </div>
                            @if($ads !=null)
                                @if(($i+1)%$kc==0 && $vt<count($ads))
                                    <div class="row">
                                        <!-- card cover -->
                                        <div style="margin: 0 auto">
                                            <div class="">
                                                <a href="{{$ads[$vt]['link']}}" target="_blank">
                                                    <img style="max-width: 819px" src="{{asset($ads[$vt]['artical'])}}"
                                                         alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $vt++;
                                    @endphp
                                @elseif($du>0 && $vt>=count($ads) && $vt<count($ads)+$du)
                                    <div class="row">
                                        <!-- card cover -->
                                        <div style="margin: 0 auto">
                                            <div class="">
                                                <a href="{{$ads[rand(0,count($ads)-1)]['link']}}" target="_blank">
                                                    <img style="max-width: 819px" src="{{asset($ads[rand(0,count($ads)-1)]['artical'])}}"
                                                         alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $vt++;
                                    @endphp
                                @endif
                            @endif
                        @endfor
                    </div>
                </div>

                <!-- end content -->
                <div class="col-12" style="margin-top: 20px">
                    <div class="row">
                        <div style="margin: 0 auto">
                            @if(isset($chapter['pre']))
                                <a href="{{route('frontend.manga.chapter',[$manga['slug'],$chapter['pre']['slug']])}}"
                                   class="chap_btn" style=" margin-right: 3px;">
                                    <i class="icon ion-ios-arrow-back"></i>
                                </a>
                            @endif
                            <select name="" class="chap_list" id="chap_redirect" data-manga="{{$manga['slug']}}">
                                @foreach($chapters as $chap)
                                    <option value="{{$chap['slug']}}"
                                            @if($chap['id'] == $chapter['current']['id']) selected @endif>{{$chap['name']}}</option>
                                @endforeach
                            </select>
                            @if(isset($chapter['next']))
                                <a href="{{route('frontend.manga.chapter',[$manga['slug'],$chapter['next']['slug']])}}"
                                   class="chap_btn">
                                    <i class="icon ion-ios-arrow-forward"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- end player -->
                @if($ads !=null && env('ADS_HEADER_FOOTER')==true)
                    <div class="col-12" style="margin-top: 20px">
                        <div class="">
                            <div class="row">
                                <!-- card cover -->
                                <div style="margin: 0 auto">
                                    <div class="">
                                        <a href="{{$ads[rand(0,count($ads)-1)]['link']}}" target="_blank">
                                            <img style="max-width: 819px"
                                                 src="{{asset($ads[rand(0,count($ads)-1)]['artical'])}}" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
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


