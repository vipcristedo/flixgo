
@extends('backend.layouts.master')

@section('title')
Add new video
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
$('#channels').select2({
    placeholder: "Choose roles"
});
$('#channels').change(function(e){
    e.preventDefault();
    $('body').append('<p>'+$('#channels option:selected').attr('channel_type')+'</p>');
    if ($('#channels option:selected').attr('channel_type') == 1) {
        $('#youtube').show();
        $('#server').hide();
    }else{
        $('#youtube').hide();
        $('#server').show();
    }
})
</script>
@endsection

@section('main__title')
<h2>Add new video</h2>
{{-- <span class="main__title-stat">{{ count($types) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.video.index') }}" class="main__title-link">Video List</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.video.store')  }}" method="POST" class="profile__form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label">Title (*)
                                        @error('title')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <input type="text" name="title" class="profile__input" placeholder="Required... (Ex: My video)" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                <div class="profile__group">
                                    <label class="profile__label" for="oldpass">Description (*)
                                        @error('description')
                                        <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <textarea id="text" name="description" class="form__textarea" placeholder="Required... (Ex: My movie - my video)">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="profile__group">
                                    <label class="profile__label">Tags</label>
                                    <input type="text" name="tags" class="profile__input" placeholder="No required... (Ex: #myTag1 #myTag2)">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label class="profile__label">Channel (*)</label>
                                <select class="js-example-basic-single" id="channels" name="channel_id">
                                    @foreach ($channels as $channel)
                                    <option value="{{ $channel['id'] }}" @if($channel['id'] == 1)selected=""@endif channel_type="{{ $channel['channel_type'] }}">{{ $channel['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <!-- video -->
                                    <div class="col-12">
                                        <div class="collapse multi-collapse" id="server">
                                            <div class="form__video" style="overflow: visible;">
                                                <label id="video1" class="profile__label" for="form__video-upload">Upload video</label>
                                                @error('video')
                                                <div class="main__table-text--red">{{ $message }}</div>
                                                @enderror
                                                <input data-name="#video1" id="form__video-upload" name="video" class="form__video-upload" type="file" accept="video/mp4,video/x-m4v,video/*">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end video -->

                                    <!-- tv series -->
                                    <div class="col-12">
                                        <div class="collapse show multi-collapse" id="youtube">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label id="video1" class="profile__label">Source key (*)</label>
                                                    @error('video')
                                                    <div class="main__table-text--red">{{ $message }}</div>
                                                    @enderror
                                                    <input type="text" class="form__input" placeholder="Source key (Ex: f41QDkSqEQg)" name="source_key">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end tv series -->
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

<!-- paginator -->
<div class="col-12">
    <div class="paginator-wrap">
        {{-- <span>10 from {{ count($types) }}</span> --}}

    </div>
</div>
<!-- end paginator -->
@endsection
