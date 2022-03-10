
@extends('backend.layouts.master')

@section('title')
Tag List
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('script')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
{{-- 
<script type="text/javascript">
    @if(Session::has('msg'))

    $(function msg(){
        swal("{{ Session::get('msg') }}", " ", "success");
    })

@endif
</script> --}}
@endsection

@section('main__title')
<h2>Tag List</h2>
{{-- <span class="main__title-stat">{{ count($tags) }} Total</span> --}}
<a href="{{ route('backend.tag.create') }}" class="main__title-link">add new Tag</a>
@endsection

@section('content')
<div class="col-12">
    <div class="main__table-wrap">
        <table class="main__table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>SLUG</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tags as $key => $tag)
                <tr>
                    <td>
                        <div class="main__table-text">{{ $tag->id }}</div>
                    </td>
                    <td>
                        <div class="main__table-text">{{$tag->name}}</div>
                    </td>
                    <td>
                        <div class="main__table-text">{{ $tag->slug }}</div>
                    </td>
                    <td>
                        <div class="main__table-btns">
                            {{-- <a href="#modal-status" class="main__table-btn main__table-btn--banned open-modal">
                                <i class="icon ion-ios-lock"></i>
                            </a> --}}
                            <a href="{{ route('backend.tag.edit' , $tag->id) }}" class="main__table-btn main__table-btn--edit">
                                <i class="icon ion-ios-create"></i>
                            </a>
                            <form action="{{ route('backend.tag.destroy' , $tag->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="main__table-btn main__table-btn--delete">
                                    <i class="icon ion-ios-trash"></i>
                                </button>
                            </form>
                            {{-- <a href="#modal-delete" class="main__table-btn main__table-btn--delete open-modal">
                                <i class="icon ion-ios-trash"></i>
                            </a> --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- end users -->

<!-- paginator -->
<div class="col-12">
    <div class="paginator-wrap">
        {{-- <span>10 from {{ count($tags) }}</span> --}}
        {!! $links !!}
    </div>
</div>
<!-- end paginator -->
@endsection