
@extends('backend.layouts.master')

@section('title')
Edit source
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
$('#channels').select2({
    placeholder: "Choose channel"
});
function changeChannelType(){
    if ($('#channels option:selected').attr('channel_type') == 1) {
        $('#youtube').show();
        $('#server').hide();
    }else{
        $('#youtube').hide();
        $('#server').show();
    }
}
changeChannelType();
$('#channels').change(function(e){
    e.preventDefault();
    changeChannelType();
})
</script>
@endsection

@section('main__title')
<h2>Edit source</h2>
{{-- <span class="main__title-stat">{{ count($types) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.video.edit', [$source['video_id'], $video['playlist_id'] ]) }}" class="main__title-link">Back</a>
@endsection

@section('content')
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <form action="{{ route('backend.source.update', $source['id'])  }}" method="POST" class="profile__form" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-12">
                                <label class="profile__label">Prioritize</label>
                                @error('prioritize')
                                <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form__input" placeholder="Required... (Ex: 1)" name="prioritize" value="{{ $source['prioritize'] }}">
                            </div>

                            <div class="col-12">
                                <label class="profile__label">Channel</label>
                                <select class="js-example-basic-single" id="channels" name="channel_id">
                                    @foreach ($channels as $channel)
                                    <option value="{{ $channel['id'] }}" @if($channel['id'] == $source['channel_id'])selected=""@endif channel_type="{{ $channel['channel_type'] }}">{{ $channel['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <!-- video -->
                                    <div class="col-12">
                                        <div class="collapse multi-collapse" id="server">
                                            <div class="form__video" style="overflow: visible;">
                                                <label id="video1" for="form__video-upload">Upload video</label>
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
                                                    <label class="profile__label">Source key</label>
                                                    @error('video')
                                                    <div class="main__table-text--red">{{ $message }}</div>
                                                    @enderror
                                                    <input type="text" class="form__input" placeholder="Source key (Ex: f41QDkSqEQg)" name="source_key" value="{{ $source['source_key'] }}">
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
@endsection