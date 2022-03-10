<?php

namespace App\Http\Controllers\Backend;

use App\Playlist;
use App\Video;
use App\Movie;
use App\Channel;
use App\Source;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playlists = Playlist::orderByRaw('created_at DESC')->get();
        return view('backend.playlists.index')->with([
            'playlists'=>$playlists
        ]);
    }

    public function getData(Request $request){
        $playlists = Playlist::select('id','title','description','status','created_at');

        if($request->has('sort')){
            if ($request->get('sort')=='Title') {
                $playlists = $playlists->orderByRaw('title ASC');
            }elseif ($request->get('sort')=='Date created') {
                $playlists = $playlists->orderByRaw('created_at DESC');
            }elseif($request->get('sort')=='Active'){
                $playlists = $playlists->where('status', '=', '1');
            }elseif($request->get('sort')=='Hidden'){
                $playlists = $playlists->where('status', '=', '0');
            }
        }
        if($request->has('title')){
            $playlists = $playlists->where('title','like',"%" . $request->get('title') . "%");
        }

        return DataTables::of($playlists->get()->toArray())
        ->editColumn('id', function ($playlist){
            return '<div class="main__table-text">'. $playlist['id'] .'</div>';
        })
        ->editColumn('title', function ($playlist){
            return '<div class="main__table-text">'. $playlist['title'] .'</div>';
        })
        ->editColumn('description', function ($playlist){
            return '<div class="main__table-text">'. $playlist['description'] .'</div>';
        })
        ->editColumn('status', function ($playlist){
            if ($playlist['status'] == 1) {
                return '<div class="main__table-text main__table-text--green">Active</div>';
            }else
            return '<div class="main__table-text main__table-text--red">Hidden</div>';
        })

        ->addColumn('action', function ($playlist) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.playlist.edit' , $playlist['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.playlist.destroy' , $playlist['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
        })
        ->rawColumns(['id','title','description','status','action'])

        ->make(true);
    }

    public function getPlaylistVideos(Request $request, $playlistId){
        $videos = Video::select('id','title', 'description', 'chap', 'playlist_id')->where('playlist_id', '=', $playlistId)->orderByRaw('chap ASC');

        if($request->has('title')){
            $videos = $videos->where('title','like',"%" . $request->get('title') . "%");
        }

        $videos = $videos->get();

        foreach ($videos as $index => $video) {
            $video->index = $index+1;
        }

        return DataTables::of($videos->toArray())
        ->addColumn('action', function ($video) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.video.edit', [ $video['id'], $video['playlist_id'] ]) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.video.detach', $video['id'] ) .'" method="POST">
                    '. csrf_field() .'
                    <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Detach">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
            ';
        })
        ->editColumn('id', function ($video){
            return '<div class="main__table-text">'. $video['index'] .'</div>';
        })
        ->editColumn('title', function ($video){
            return '<div class="main__table-text" data-toggle="tooltip" title="'. $video['description'] .'">'. $video['title'] .'</div>';
        })
        ->editColumn('chap', function ($video){
            return '<div class="main__table-text">'. $video['chap'] .'</div>';
        })

        ->rawColumns(['id', 'title', 'chap', 'action'])

        ->make(true);
    }


    public function getVideos(Request $request){
        $videos = Video::select('id','title', 'description')->where('movie_id', '=', NULL)->where('playlist_id', '=', NULL);

        if($request->has('title')){
            $videos = $videos->where('title','like',"%" . $request->get('title') . "%");
        }

        $videos = $videos->get();

        foreach ($videos as $index => $video) {
            $video->index = $index+1;
        }

        return DataTables::of($videos->toArray())
        ->addColumn('action', function ($video) {
            return '
            <div class="main__table-btns">
                <input type="checkbox" title="videos[]" value="'. $video['id'] .'" style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange('. $video['id'] .')">
            </div>
            ';
        })
        ->editColumn('id', function ($video){
            return '<div class="main__table-text">'. $video['index'] .'</div>';
        })
        ->editColumn('title', function ($video){
            return '<div class="main__table-text">'. $video['title'] .'</div>';
        })
        ->editColumn('description', function ($video){
            return '<div class="main__table-text">'. $video['description'] .'</div>';
        })

        ->rawColumns(['action', 'id', 'title', 'description'])

        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.playlists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->session()->flash('status','new');
        $validator = Validator::make($request->all(),
            [
                'title'=>'required|min:2',
                'order'=>'required|numeric',
                'description'=>'required|min:2',
            ],
            [
                'title'=>'Title',
                'order'=>'Order',
                'description'=>'Description'
            ]
        );
        if ($request->get('order')<= 0) {
        $validator->after(function ($validator) {
            $validator->errors()->add('order',"Order must be greater than zero");
        });
        }
        if ($validator->fails()){
            if ($request->has('movie_id')) {
                return response()->json([
                    'error'    => true,
                    'messages' => $validator->errors(),
                ], 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $playlist = new Playlist();

        $playlist->title = $request->get('title');
        $playlist->description = $request->get('description');
        $playlist->order = $request->get('order');
        if($request->has('movie_id')){
            $playlist->movie_id = $request->get('movie_id');
            $moviePlaylists = Movie::findOrFail($playlist->movie_id)->playlists()->get()->sortBy('order');
            foreach ($moviePlaylists as $key => $moviePlaylist) {
                if ($moviePlaylist->order >= $playlist->order) {
                    $moviePlaylist->order++;
                    $moviePlaylist->save();
                }
            }
        }

        $playlist->user_created_id = Auth::user()->id; // Auth::user()->id;
        $playlist->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $playlist->save();
        return redirect()->route('backend.playlist.edit',$playlist->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function show($playlistId)
    {
        $playlist = Playlist::findOrFail($playlistId);
        return response()->json([
            'error' => false,
            'playlist'  => $playlist,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function edit($playlistId, $movieId = NULL)
    {
        $playlist = Playlist::findOrFail($playlistId);
        if ($movieId != NULL) {
            Session::flash('movieId', $movieId);
            Session::flash('status', 'new');
        }
        $channels = Channel::all();
        return view('backend.playlists.edit')->with([
            'playlist'=>$playlist->toArray(),
            'channels'=>$channels->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $playlistId)
    {
        $validator = Validator::make($request->all(),
            [
                'title'=>'required|min:2',
                'order'=>'required|numeric',
                'description'=>'required|min:2',
            ],
            [
                'title'=>'Title',
                'order'=>'Order',
                'description'=>'Description'
            ]
        );
        if ($request->get('order')<= 0) {
        $validator->after(function ($validator) {
            $validator->errors()->add('order',"Order must be greater than zero");
        });
        }
        if ($validator->fails()){
            if ($request->has('movie_id')) {
                return response()->json([
                    'error'    => true,
                    'messages' => $validator->errors(),
                ], 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $playlist = Playlist::findOrFail($playlistId);
        $playlist->title = $request->get('title');
        $playlist->description = $request->get('description');
        $beforeOrder = $playlist->order;
        $afterOrder = $request->get('order');

        if($playlist->movie_id != NULL ){
            $moviePlaylists = Movie::findOrFail($playlist->movie_id)->playlists()->get()->sortBy('order');
            if ($afterOrder > $beforeOrder) {
                foreach ($moviePlaylists as $key => $moviePlaylist) {
                    if ($moviePlaylist->order > $beforeOrder && $moviePlaylist->order <= $afterOrder) {
                        $moviePlaylist->order--;
                        $moviePlaylist->save();
                    }
                }
            }elseif($afterOrder < $beforeOrder){
                foreach ($moviePlaylists as $key => $moviePlaylist) {
                    if ($moviePlaylist->order >= $afterOrder && $moviePlaylist->order < $beforeOrder) {
                        $moviePlaylist->order++;
                        $moviePlaylist->save();
                    }
                }
            }
        }

        $playlist->order = $afterOrder;
        $playlist->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $playlist->save();
        if ($request->get('movieId') != NULL && $playlist->movie_id != NULL) {
            Session::flash('status','new');
            return redirect()->route('backend.movie.edit', $playlist->movie_id);
        }
        if ($request->has('movie_id')) {
            return response()->json([
                'error' => false,
                'playlist'  => $playlist,
            ]);
        }

        return redirect()->route('backend.playlist.index');
    }

    public function updateVideo(Request $request, $playlistId){
        $playlist = Playlist::findOrFail($playlistId);
        $playlistVideos = $playlist->videos()->get()->sortBy('chap');
        $video = Video::findOrFail($request->get('videoId'));
        $video->playlist_id = $playlistId;

        if ($video->chap == NULL) {
            $video->chap = 1;
            foreach ($playlistVideos as $playlistVideo) {
                if ($video->chap == $playlistVideo->chap) {
                    $video->chap++;
                }
            }
        }else{
            foreach ($playlistVideos as $playlistVideo) {
                if ($playlistVideo->chap >= $video->chap) {
                    $playlistVideo->chap++;
                    $playlistVideo->save();
                }
            }
        }
        
        $video->movie_id = $playlist->movie_id == NULL?NULL:$playlist->movie_id;
        $sources = $video->sources;
        foreach ($sources as $source) {
            $source->movie_id = $playlist->movie_id == NULL?NULL:$playlist->movie_id;
            $source->save();
        }
        $video->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy($playlistId)
    {
        $playlist = Playlist::findOrFail($playlistId);
        $playlist->videos()->update(['playlist_id'=>null, 'playlist_id'=>null]);
        $playlist->delete();
        return redirect()->back();
    }

    public function detach($playlistId){
        $playlist = Playlist::findOrFail($playlistId);
        $playlist->movie_id = null;
        $videos = $playlist->videos;
        foreach ($videos as $video)
        {
            $video->movie_id = null;
            $video->save();
            $sources=$video->sources;
            foreach ($sources as $source)
            {
                $source->movie_id = null;
                $source->save();
            }
        }
        $playlist->save();
        return redirect()->back();
    }

    public function addVideo(Request $request, $playlistId){
        $validator = Validator::make($request->all(),
            [
                'title'=>'required|min:2',
                'description'=>'required|min:2',
                'chap'=>'numeric',
                'video'=>'max:'.env('MAX_VIDEO_SIZE', 500000),
            ]
        );
        if ($request->hasFile('video')== false && $request->get('source_key') == '') {
        $validator->after(function ($validator) {
            $validator->errors()->add('video','No source key or upload video available');
        });
        }
        if ($validator->fails()){
            return response()->json([
                'error'    => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $playlist = Playlist::findOrFail($playlistId);
        $playlistVideos = $playlist->videos()->get()->sortBy('chap');
        $video = new Video();
        $video->title = $request->get('title');
        $video->tags = $request->get('tags');

        $video->chap = $request->get('chap');
        if ($video->chap == NULL) {
            $video->chap = 1;
            foreach ($playlistVideos as $playlistVideo) {
                if ($video->chap == $playlistVideo->chap) {
                    $video->chap++;
                }
            }
        }else{
            foreach ($playlistVideos as $playlistVideo) {
                if ($playlistVideo->chap >= $video->chap) {
                    $playlistVideo->chap++;
                    $playlistVideo->save();
                }
            }
        }

        $video->description = $request->get('description');
        $video->user_created_id = Auth::user()->id;
        $video->user_updated_id = Auth::user()->id;
        $video->movie_id = $playlist->movie_id == null ? null:$playlist->movie_id;
        $video->playlist_id = $playlistId;
        $video->save();
        if ($request->get('slug')==null) {
            $slug = Str::slug($request->get('title').'-'.$video->id);
            $status = Video::where('slug',$slug)->get();
            $video->slug = count($status)==0?$slug:$slug.'-1';
        }else{
            $video->slug = Str::slug($request->get('slug'));
        }
        $video->save();
        $source = new Source();
        $source->video_id = $video->id;
        $source->language = $request->get('language')==null?1:2;
        $source->prioritize = 1;
        $source->movie_id = $video->movie_id;
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
        return redirect()->back();
    }

    public function changeStatus($playlistId){
        $playlist = Playlist::findOrFail($playlistId);
        $playlist->status = $playlist->status == 0 ? 1:0;
        $playlist->user_updated_id =  Auth::user()->id;
        $playlist->save();
        return redirect()->route('backend.playlist.index');
    }
}
