
@extends('backend.layouts.master')

@section('title')
Edit channel
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#order').select2({
        placeholder: "Choose order"
    });
    $('#channel_type').select2();
    function changeStatus (){
        $.ajax({
            type: "POST",
            url: '{!! route('backend.channel.changeStatus', $channel['id']) !!}',
            "data" : {
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            },
            error: function(data) {
                location.reload();
            }
        });
    }
</script>
@endsection

@section('main__title')
<h2>Edit channel</h2>
<a href="{{ route('backend.channel.index') }}" class="main__title-link">Channel List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <!-- red -->
            <div class="profile__meta profile__meta--green">
                <h3>{{ $channel['title'] }}</h3>
                <span>Channel ID: {{ $channel['id'] }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile btns -->
        <div class="profile__actions">
            <a href="#modal-status3" ></a>
            <button type="button" class="profile__action profile__action--banned" onclick="changeStatus()">
                @if($channel['status'] == 1)
                <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @else
                <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @endif
            </button>
            <form action="{{ route('backend.channel.destroy' , $channel['id']) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="profile__action profile__action--delete">
                    <i class="icon ion-ios-trash" data-toggle="tooltip" title="Delete"></i>
                </button>
            </form>
        </div>
        <!-- end profile btns -->
    </div>
</div>
<!-- end profile -->
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.channel.update', $channel['id'])  }}" method="POST" class="profile__form">
                        <div class="row">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Title 
                                        @error('title')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="username" type="text" name="title" class="profile__input" placeholder="{{ $channel['title'] }}" value="{{ $channel['title'] }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="email">Link 
                                        @error('link')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="link" type="text" name="link" class="profile__input" placeholder="{{ $channel['link'] }}" value="{{ $channel['link'] }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Order</label>
                                    <select name="order" id="order">
                                        @for($i=1; $i<=$totalChannel; $i++)
                                        <option value="{{ $i }}" @if($channel['order'] == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="email">Channel type</label>
                                    <select name="channel_type" id="channel_type">
                                        @foreach($channel_types as $channel_type)
                                        <option value="{{ $channel_type->order }}" @if($channel['channel_type'] == $channel_type->order) selected @endif>{{ $channel_type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Description
                                        @error('description')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Description">{{ $channel['description'] }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="profile__btn" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end details form -->
            </div>
        </div>  
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->
@endsection