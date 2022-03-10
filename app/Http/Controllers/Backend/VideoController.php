<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Video;
use App\Source;
use App\Channel;
use App\Playlist;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::orderByRaw('created_at DESC')->get();
        return view('backend.videos.index')->with([
            'videos'=>$videos
        ]);
    }

    public function getData(Request $request){
        $videos = Video::select('id', 'title', 'status', 'created_at');

        if($request->has('sort')){
            if ($request->get('sort')=='Title') {
                $videos = $videos->orderByRaw('title ASC');
            }elseif ($request->get('sort')=='Date created') {
                $videos = $videos->orderByRaw('created_at DESC');
            }elseif($request->get('sort')=='Active'){
                $videos = $videos->where('status', '=', '1');
            }elseif($request->get('sort')=='Hidden'){
                $videos = $videos->where('status', '=', '0');
            }
        }
        if($request->has('title')){
            $videos = $videos->where('title','like',"%" . $request->get('title') . "%");
        }

        return DataTables::of($videos->get()->toArray())
        ->editColumn('id', function ($video){
            return '<div class="main__table-text">'. $video['id'] .'</div>';
        })
        ->editColumn('title', function ($video){
            return '<div class="main__table-text">'. $video['title'] .'</div>';
        })
        ->editColumn('status', function ($video){
            if ($video['status'] == 1) {
                return '<div class="main__table-text main__table-text--green">Active</div>';
            }else
            return '<div class="main__table-text main__table-text--red">Hidden</div>';
        })

        ->addColumn('action', function ($video) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.video.edit' , $video['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                    <i class="icon ion-ios-create"></i>
                </a>
                <form action="'. route('backend.video.destroy' , $video['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
        })
        ->rawColumns(['id', 'title', 'status', 'action'])

        ->make(true);
    }

    public function getSources(Request $request, $videoId){
        $sources = Source::select('id','source_key', 'prioritize', 'channel_id')->where('video_id', '=', $videoId)->orderByRaw('prioritize ASC');

        if($request->has('source_key')){
            $sources = $sources->where('source_key','like',"%" . $request->get('source_key') . "%");
        }

        return DataTables::of($sources->get()->toArray())
        ->addColumn('action', function ($source) {
            return '
            <div class="main__table-btns">
                <button class="main__table-btn main__table-btn--edit edit-source" data-toggle="tooltip" title="Edit" data-source="'. $source['id'] .'">
                    <i class="icon ion-ios-create"></i>
                </button>

                <button type="submit" class="main__table-btn main__table-btn--delete" onclick="deleteSource(' . $source['id'] . ')" data-toggle="tooltip" title="Delete">
                    <i class="icon ion-ios-trash"></i>
                </button>
            </div>
            ';
        })
        ->editColumn('id', function ($source){
            return '<div class="main__table-text">'. $source['id'] .'</div>';
        })
        ->editColumn('source_key', function ($source){
            return '<div class="main__table-text">'. $source['source_key'] .'</div>';
        })
        ->editColumn('prioritize', function ($source){
            return '<div class="main__table-text">'. $source['prioritize'] .'</div>';
        })
        ->editColumn('channel_id', function ($source){
            $channel = \DB::table('channels')->where('id', '=', $source['channel_id'])->value('title');
            return '<div class="main__table-text">'. $channel .'</div>';
        })

        ->rawColumns(['id', 'source_key', 'prioritize', 'channel_id', 'action'])

        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels = Channel::all();
        return view('backend.videos.create')->with([
            'channels'=>$channels->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title'=>'required|min:2',
                'description'=>'required|min:2',
                'video'=>'max:'.env('MAX_VIDEO_SIZE', 500000),
            ]
        );
        if ($request->hasFile('video')== false && $request->get('source_key') == '') {
        $validator->after(function ($validator) {
            $validator->errors()->add('video','No source key or upload video available');
        });
        }
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $video = new Video();

        $video->title = $request->get('title');
        $video->tags = $request->get('tags');
        $video->description = $request->get('description');
        $video->movie_id = $request->get('movie_id');
        $video->slug = Str::slug($request->get('title'));
        $video->playlist_id = $request->get('playlist_id');
        $video->user_created_id = Auth::user()->id;
        $video->user_updated_id = Auth::user()->id;
        $video->save();

        $source = new Source();
        $source->video_id = $video->id;
        $source->language = $request->get('language')==null?1:2;
        $source->prioritize = 1;
        if ($request->has('movie_id')) $source->movie_id = $request->get('movie_id');
        else $source->movie_id = NULL;
        $source->channel_id = $request->get('channel_id');
        $source->user_created_id = Auth::user()->id;
        $source->user_updated_id = Auth::user()->id;
        $source->save();
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $source->id.'.'.$file->getClientOriginalName();
            $file->storeAs('public/video',$name);
            $source->source_key = '/storage/video/'.$name;
        }else{
            $source->source_key = $request->get('source_key');
        }
        $source->save();

        if ($request->get('slug')==null) {
            $video->slug = Str::slug($request->get('slug'));
        }else{
            $status = Tag::where('slug',Str::slug($request->get('title').'-'.$video->id))->get();
            if (count($status) == null) {
                $video->slug = Str::slug($request->get('title').'-'.$video->id);
            }else{
                $video->slug = Str::slug($request->get('title').'-'.$video->id.'-1');
            }
        }
        $video->slug = $request->get('slug')==null?'':Str::slug($request->get('slug'));

        return redirect()->route('backend.video.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($videoId)
    {
        $video = Video::findOrFail($videoId);
        // return view('backend.videos.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit($videoId, $playlistId = NULL)
    {
        $video = Video::findOrFail($videoId);
        $channels = Channel::all();
        if ($playlistId != NULL) {
            Session::flash('status','new');
            if ($video->playlist_id != NULL) {
                Session::flash('playlistId',$video->playlist_id);
            }
            if ($video->movie_id != NULL) {
                Session::flash('movieId',$video->movie_id);
            }
        }

        return view('backend.videos.edit')->with([
            'video'=>$video->toArray(),
            'channels'=>$channels
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $videoId)
    {
        $video = Video::findOrFail($videoId);

        $validator = Validator::make($request->all(),
            [
                'title'=>'required|min:2',
                'description'=>'required|min:2',
                'video'=>'max:'.env('MAX_VIDEO_SIZE', 500000),
                'chap'=>'numeric',
            ]
        );

        if ($video->playlist_id != NULL) {
        $validator->after(function ($validator) {
            $validator->errors()->add('chap',"video's chap in playlist is required.");
        });
        }

        if ($validator->failed()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $video->title = $request->get('title');
        $video->tags = $request->get('tags');
        $video->description = $request->get('description');

        if ($request->has('playlist_id'))
            $video->playlist_id = $request->get('playlist_id');
        if($request->has('chap')){
            if ($video->playlist_id != NULL) {
                $beforeChap = $video->chap;
                $afterChap = $request->get('chap');
                $playlistVideos = Playlist::findOrFail($video->playlist_id)->videos()->get()->sortBy('chap');
                if ($afterChap > $beforeChap) {
                    foreach ($playlistVideos as $key => $playlistVideo) {
                        if ($playlistVideo->chap > $beforeChap && $playlistVideo->chap <= $afterChap) {
                            $playlistVideo->chap--;
                            $playlistVideo->save();
                        }
                    }
                }elseif($afterChap < $beforeChap){
                    foreach ($playlistVideos as $key => $playlistVideo) {
                        if ($playlistVideo->chap >= $afterChap && $playlistVideo->chap < $beforeChap) {
                            $playlistVideo->chap++;
                            $playlistVideo->save();
                        }
                    }
                }
                $video->chap = $afterChap;
            }
        }
        $video->user_updated_id = Auth::user()->id;
        $video->save();

        if ($request->get('slug')==null) {
            $video->slug = Str::slug($request->get('slug'));
        }else{
            $status = Tag::where('slug',Str::slug($request->get('title').'-'.$video->id))->get();
            if (count($status) == null) {
                $video->slug = Str::slug($request->get('title').'-'.$video->id);
            }else{
                $video->slug = Str::slug($request->get('title').'-'.$video->id.'-1');
            }
        }
        $video->slug = $request->get('slug')==null?'':Str::slug($request->get('slug'));

        if ($request->get('playlistId') != NULL && $video->playlist_id != NULL) {
            Session::flash('status','new');
            return redirect()->route('backend.playlist.edit', $video->playlist_id);
        }
        if ($request->get('movieId') != NULL && $video->movie_id !=NULL) {
            Session::flash('status','new');
            return redirect()->route('backend.movie.edit', $video->movieId);
        }

        if ($request->get('playlistId') != NULL && $video->playlist_id != NULL) {
            Session::flash('status','new');
            return redirect()->route('backend.playlist.edit', $video->playlist_id);
        }

        return redirect()->route('backend.video.index');

        // if(isset($_GET['links'])){
        //     if($_GET['links']=='movie') return redirect('/admin/movie');
        //     if($_GET['links']=='playList') return redirect('/admin/playlist');
        // }else{
        //     return redirect('/admin/video');
        // }
    }

    public function changeStatus($sourceId){
        $video = Video::findOrFail($sourceId);
        $video->status = $video->status == 0 ? 1:0;
        $video->user_updated_id = Auth::user()->id;
        $video->save();
        return redirect()->route('backend.video.index');
    }

    public function roles($videoId){
        $video = Video::findOrFail($videoId);
        $roles = $video->roles()->get();
    }

    public function detach($videoId){
        $video = Video::findOrFail($videoId);
        $video->playlist_id = null;
        $video->movie_id = null;
        $video->save();
        $sources = $video->sources()->get();
        foreach ($sources as $source) {
            $source->movie_id = null;
            $source->save();
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy($videoId)
    {
        $video = Video::destroy($videoId);
        return redirect()->route('backend.video.index');
    }
}
