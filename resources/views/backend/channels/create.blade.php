
@extends('backend.layouts.master')

@section('title')
Add new channel
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
    $('#order').select2({
        placeholder: "Choose order"
    });
    $('#channel_type').select2();
</script>
@endsection

@section('main__title')
<h2>Add new channel</h2>
<a href="{{ route('backend.channel.index') }}" class="main__title-link">Channel List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.channel.store')  }}" method="POST" class="profile__form">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Title 
                                        @error('title')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="username" type="text" name="title" class="profile__input" placeholder="Required ... (Ex: My channel)" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="email">Link 
                                        @error('link')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input id="email" type="text" name="link" class="profile__input" placeholder="No required ... (Ex: youtube.com/channel/my-channel)" value="{{ old('link') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label">Order</label>
                                    <select name="order" id="order">
                                        @for($i=1; $i<=$totalChannel+1; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                <div class="profile__group">
                                    <label class="profile__label" for="email">Channel type</label>
                                    <select name="channel_type" id="channel_type">
                                        @foreach($channel_types as $channel_type)
                                        <option value="{{ $channel_type->order }}">{{ $channel_type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Description
                                        @error('description')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Required ... (Ex: My channel)">{{ old('description') }}</textarea>
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