<?php

namespace App\Http\Controllers\Backend;

use App\Movie;
use App\Tag;
use App\Type;
use App\Video;
use App\Source;
use App\Channel;
use App\Playlist;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.movies.index');
    }

    /**
     * Lấy dữ liệu cho danh sách các movie
     */
    public function getData(Request $request)
    {
        $movies = Movie::select('id', 'name', 'nominations', 'slug', 'created_at');

        if ($request->has('sort')) {
            if ($request->get('sort') == 'Name') {
                $movies = $movies->orderByRaw('name ASC');
            } elseif ($request->get('sort') == 'Release year') {
                $movies = $movies->orderByRaw('release_year DESC');
            } elseif ($request->get('sort') == 'Rating') {
                $movies = $movies->orderByRaw('rate DESC');
            } elseif ($request->get('sort') == 'Nominations') {
                $movies = $movies->orderByRaw('nominations DESC');
            }
        }

        $movies = $movies->orderByRaw('created_at DESC');
        if ($request->has('name')) {
            $movies = $movies->where('name', 'like', "%" . $request->get('name') . "%");
        }
        if ($request->has('slug')) {
            $movies = $movies->where('slug', 'like', "%" . $request->get('slug') . "%");
        }

        return DataTables::of($movies->get()->toArray())
            ->editColumn('id', function ($movie) {
                return '<div class="main__table-text">' . $movie['id'] . '</div>';
            })
            ->editColumn('name', function ($movie) {
                return '<div class="main__table-text">' . $movie['name'] . '</div>';
            })
            ->editColumn('slug', function ($movie) {
                return '<div class="main__table-text">' . $movie['slug'] . '</div>';
            })
            ->addColumn('action', function ($movie) {
                $nominations = $movie['nominations'] == 0? 
                'class="main__table-btn main__table-btn--delete open-modal btn-nominations" data-toggle="tooltip" title="Turn on nomination">
                <i class="icon ion-ios-radio-button-off"></i>
                '
                :
                'class="main__table-btn main__table-btn--edit open-modal btn-nominations" data-toggle="tooltip" title="Turn off nomination">
                <i class="icon ion-ios-radio-button-on"></i>
                ';
                return '
                <div class="main__table-btns">
                    <a href="#" movie_id="'. $movie['id'].'" data_status="'. $movie['nominations'] .'"  
                        '. $nominations . '
                    </a>
                <a href="' . route('backend.movie.edit', $movie['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="' . route('backend.movie.destroy', $movie['id']) . '" method="POST">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
            })
            ->rawColumns(['id', 'name', 'slug', 'action'])
            ->make(true);
    }

    public function nominations($id)
    {
        $movie = Movie::find($id);
        if ($movie->nominations == 1) $movie->nominations = 0;
        else $movie->nominations = 1;
        $movie->save();
        die($movie);
    }

    /**
     * Lấy dữ liệu các playlist của movie hiện tại
     */
    public function getMoviePlaylists(Request $request, $movieId)
    {
        $playlists = Playlist::select('id', 'title', 'order', 'description', 'movie_id')->orderByRaw('playlists.order ASC')->where('movie_id', '=', $movieId);

        if ($request->has('title')) {
            $playlists = $playlists->where('title', 'like', "%" . $request->get('title') . "%");
        }

        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return DataTables::of($playlists->toArray())
            ->addColumn('action', function ($playlist) {
                return '
            <div class="main__table-btns">
                <button class="main__table-btn main__table-btn--edit edit-playlist" data-toggle="tooltip" title="Edit Playlist" data-playlist="'. $playlist['id'] .'">
                    <i class="icon ion-ios-create"></i>
                </button>
                <a href="' . route('backend.playlist.edit', [$playlist['id'], $playlist['movie_id']]) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Videos">
                    <i class="ion ion-ios-videocam"></i>
                </a>
                <button type="submit" class="main__table-btn main__table-btn--delete" onclick="detachPlaylist(' . $playlist['id'] . ')" data-toggle="tooltip" title="Detach">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                <button class="main__table-btn main__table-btn--delete open-modal delete-playlist" data-toggle="tooltip" title="Delete" onclick="event.preventDefault();deletePlaylist(' . $playlist['id'] . ')" >
                    <i class="icon ion-ios-trash"></i>
                </button>
            </div>
            ';
            })
            ->editColumn('id', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['index'] . '</div>';
            })
            ->editColumn('title', function ($playlist) {
                return '<div class="main__table-text" data-toggle="tooltip" title="' . $playlist['description'] . '">' . $playlist['title'] . '</div>';
            })
            ->editColumn('order', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['order'] . '</div>';
            })
            ->rawColumns(['id', 'action', 'title', 'order'])
            ->make(true);
    }

    /**
     * Lấy dữ liệu các playlist chưa có movie
     */
    public function getPlaylists(Request $request)
    {
        $playlists = Playlist::select('id', 'title', 'order', 'description')->orderByRaw('playlists.order ASC')->where('movie_id', '=', NULL);

        if ($request->has('title')) {
            $playlists = $playlists->where('title', 'like', "%" . $request->get('title') . "%");
        }

        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return DataTables::of($playlists->toArray())
            ->addColumn('action', function ($playlist) {
                return '
            <div class="main__table-btns">
                <input type="checkbox" title="playlists[]" value="' . $playlist['id'] . '" style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange(' . $playlist['id'] . ')">
            </div>
            ';
            })
            ->editColumn('id', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['index'] . '</div>';
            })
            ->editColumn('title', function ($playlist) {
                return '<div class="main__table-text" data-toggle="tooltip" title="' . $playlist['description'] . '">' . $playlist['title'] . '</div>';
            })
            ->editColumn('order', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['order'] . '</div>';
            })
            ->rawColumns(['action', 'id', 'title', 'order',])
            ->make(true);
    }

    public function getVideos(Request $request)
    {
        $videos = Video::select('id', 'title', 'description')->where('movie_id', '=', NULL);

        if ($request->has('title')) {
            $videos = $videos->where('title', 'like', "%" . $request->get('title') . "%");
        }

        $videos = $videos->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

        return DataTables::of($videos->toArray())
            ->addColumn('action', function ($video) {
                return '
            <div class="main__table-btns">
                <input type="checkbox" title="videos[]" value="' . $video['id'] . '" style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange1(' . $video['id'] . ')">
            </div>
            ';
            })
            ->editColumn('id', function ($video) {
                return '<div class="main__table-text">' . $video['index'] . '</div>';
            })
            ->editColumn('title', function ($video) {
                return '<div class="main__table-text">' . $video['title'] . '</div>';
            })
            ->editColumn('description', function ($video) {
                return '<div class="main__table-text">' . $video['description'] . '</div>';
            })
            ->rawColumns(['id', 'action', 'title', 'description'])
            ->make(true);
    }

    public function showData($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        return response()->json([
            'error' => false,
            'movie' => $movie->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quality = \DB::table('options')->where('options.name', '=', 'quality')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $genre = \DB::table('options')->where('options.name', '=', 'genre')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $country = \DB::table('options')->where('options.name', '=', 'country')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $types = Type::where('table_name', 'movies')->get();
        $tags = Tag::all();
        return view('backend.movies.create')->with([
            'quality' => $quality->toArray(),
            'genre' => $genre->toArray(),
            'country' => $country->toArray(),
            'types' => $types->toArray(),
            'tags' => $tags->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|min:2',
                'name_ja' => 'required|min:2',
                'description' => 'required|min:8',
                'age' => 'required|numeric',
                'runtime' => 'required|numeric',
                'release_year' => 'required|numeric',
            ],
            [
                'name' => 'Name',
                'name' => 'Japanese name',
                'description' => 'Description',
                'age' => 'Age',
                'runtime' => 'Runtime',
                'release_year' => 'Release year'
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $movie = new Movie();
        $movie->description = $request->get('description');

        $movie->name = $request->get('name');
        $movie->name_ja = $request->get('name_ja');
        $movie->age = $request->get('age');
        $movie->genre = $request->get('genre');
        $movie->runtime = $request->get('runtime');
        $movie->release_year = $request->get('release_year');
        $movie->quality = $request->get('quality');
        $movie->country = $request->get('country');
        // $movie->rate = $request->get('rate');
        $movie->card_cover = '';

        $movie->user_created_id = Auth::user()->id; // Auth::user()->id;
        $movie->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $movie->save();
        if ($request->hasFile('card_cover')) {
            $card_cover = $request->file('card_cover');
            $name = $movie->id . '.' . $card_cover->getClientOriginalName();
            $card_cover->storeAs('public/images', $name);
            $movie->card_cover = '/storage/images/' . $name;
        }

        if ($request->get('slug') != null) {
            $movie->slug = Str::slug($request->get('slug'));
        } else {
            $status = Movie::where('slug', Str::slug($request->get('name') . '-' . $movie->id))->get();
            if (count($status) == null) {
                $movie->slug = Str::slug($request->get('name') . '-' . $movie->id);
            } else {
                $movie->slug = Str::slug($request->get('name') . '-' . $movie->id . '-1');
            }
        }
        $movie->save();
        //$tags ở dạng chuỗi
        $tags = $request->get('tags');
        //chuyển $stags sang mảng
        $tags = explode(',', $tags);
        for ($i = 0; $i < count($tags); $i++) {
            $t = Tag::where('name', $tags[$i])->first();
            //nếu tìm thấy $t thì thêm vào bảng quan hệ, không thấy thì thêm mới tag và thêm vào bảng quan hệ
            if ($t !== null) {
                DB::table('movie_tag')->insert(
                    [
                        'movie_id' => $movie->id,
                        'tag_id' => $t->id,
                        'user_created_id' => Auth::user()->id,
                        'user_updated_id' => Auth::user()->id,
                    ]
                );
            } else {
                //thêm mới tag
                $t = new Tag();
                $t->name = $tags[$i];
                $t->slug = Str::slug($tags[$i]) . time();
                $t->user_created_id = Auth::user()->id;
                $t->user_updated_id = Auth::user()->id;
                $t->save();
                //tạo quan hệ với movie
                DB::table('movie_tag')->insert(
                    [
                        'movie_id' => $movie->id,
                        'tag_id' => $t->id,
                        'user_created_id' => Auth::user()->id,
                        'user_updated_id' => Auth::user()->id,
                    ]
                );
            }
        }
        $types = $request->get('types');
        $movie->types()->attach($types, [
            'user_created_id' => Auth::user()->id,
            'user_updated_id' => Auth::user()->id
        ]);

        $request->session()->flash('status', 'new');

        return redirect()->route('backend.movie.edit', $movie->id);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function show($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        return view('backend.movies.show')->with([
            'movie' => $movie
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function edit($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $quality = \DB::table('options')->where('options.name', '=', 'quality')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $genre = \DB::table('options')->where('options.name', '=', 'genre')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $country = \DB::table('options')->where('options.name', '=', 'country')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $language = \DB::table('options')->where('options.name', '=', 'language')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $channels = Channel::all();
        $tags = Tag::all();
        $types = Type::where('table_name', '=', 'movies')->get();
        $movieTags = $movie->tags()->get();
        $t = '';
        foreach ($movieTags as $tag) {
            $t .= $tag->name . ',';
        }
        $movieTags = trim($t, ',');
        $movieTypes = $movie->types()->get();
        $videos = Video::where('movie_id', '=', '')->where('playlist_id', '=', '')->get();
        $movieVideos = $movie->videos()->get();
        // dd($movieTypes->toArray());
        return view('backend.movies.edit')->with([
            'movie' => $movie->toArray(),
            'quality' => $quality->toArray(),
            'genre' => $genre->toArray(),
            'country' => $country->toArray(),
            'language' => $language->toArray(),
            'channels' => $channels->toArray(),
            'tags' => $tags->toArray(),
            'types' => $types->toArray(),
            'movieTags' => $movieTags,
            'movieTypes' => $movieTypes->toArray(),
            'videos' => $videos,
            'movieVideos' => $movieVideos
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $movieId)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|min:2',
                'name_ja' => 'required|min:2',
                'description' => 'required|min:8',
                'age' => 'required|numeric',
                'runtime' => 'required|numeric',
                'release_year' => 'required|numeric',
            ],
            [
                'name' => 'Name',
                'name_ja' => 'Japanese name',
                'description' => 'Description',
                'age' => 'Age',
                'runtime' => 'Runtime',
                'release_year' => 'Release year'
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $movie = Movie::findOrFail($movieId);

        $movie->name = $request->get('name');
        $movie->name_ja = $request->get('name_ja');
        $movie->description = $request->get('description');
        $movie->age = $request->get('age');
        $movie->runtime = $request->get('runtime');
        $movie->release_year = $request->get('release_year');
        $movie->quality = $request->get('quality');
        $movie->country = $request->get('country');
        // $movie->rate = $request->get('rate');

        if ($request->get('slug') != null) {
            $movie->slug = Str::slug($request->get('slug'));
        } else {
            $status = Movie::where('slug', Str::slug($request->get('name') . '-' . $movie->id))->where('id', '!=', $movie->id)->get();
            if (count($status) == null) {
                $movie->slug = Str::slug($request->get('name') . '-' . $movie->id);
            } else {
                $movie->slug = Str::slug($request->get('name') . '-' . $movie->id . '-1');
            }
        }
        $movie->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $movie->save();

        if ($request->hasFile('card_cover')) {
            $card_cover = $request->file('card_cover');
            $name = $movie->id . '.' . $card_cover->getClientOriginalName();
            $link = str_replace('/storage/images/', '', $movie->card_cover);
            Storage::disk('public')->delete('/images/' . $link);
            $card_cover->storeAs('public/images', $name);
            $movie->card_cover = '/storage/images/' . $name;
        }

        $movie->save();
        //$tags ở dạng chuỗi
        $tags = $request->get('tags');
        //chuyển $stags sang mảng
        $tags = explode(',', $tags);
        //xóa hết quan hệ với tag cũ của movie
        DB::table('movie_tag')
            ->where('movie_id', '=', $movie->id)
            ->delete();
        //thêm quan hệ mới cho movie
        for ($i = 0; $i < count($tags); $i++) {
            $t = Tag::where('name', $tags[$i])->first();
            //nếu tìm thấy $t thì xóa quan hệ cũ với $movie thêm vào bảng quan hệ, không thấy thì thêm mới tag và thêm vào bảng quan hệ
            if ($t !== null) {
                DB::table('movie_tag')->insert(
                    [
                        'movie_id' => $movie->id,
                        'tag_id' => $t->id,
                        'user_created_id' => 1,
                        'user_updated_id' => 1
                    ]
                );
            } else {
                //thêm mới tag
                $t = new Tag();
                $t->name = $tags[$i];
                $t->slug = Str::slug($tags[$i]) . time();
                $t->user_created_id = 1;
                $t->user_updated_id = 1;
                $t->save();
                //tạo quan hệ với movie
                DB::table('movie_tag')->insert(
                    [
                        'movie_id' => $movie->id,
                        'tag_id' => $t->id,
                        'user_created_id' => 1,
                        'user_updated_id' => 1
                    ]
                );
            }
        }

        $types = $request->get('types');
        $movie->types()->detach();
        foreach ($types as $type) {
            $movie->types()->attach($type, ['user_created_id' => Auth::user()->id, 'user_updated_id' => Auth::user()->id]);
        }
//        $movie->types()->sync($types,
//            ['user_created_id' => Auth::user()->id],
//            ['user_updated_id' => Auth::user()->id,
//        ]);

        return redirect()->route('backend.movie.index');
    }

    public function updatePlaylist(Request $request, $movieId)
    {

        $moviePlaylists = Movie::findOrFail($movieId)->playlists()->get()->sortBy('order');
        $playlist = Playlist::findOrFail($request->get('playlistId'));
        $playlist->movie_id = $movieId;
        $videos = $playlist->videos;
        foreach ($videos as $video) {
            $video->movie_id = $movieId;
            $video->save();
            $sources = $video->sources;
            foreach ($sources as $source) {
                $source->movie_id = $movieId;
                $source->save();
            }
        }
        if ($playlist->order == NULL) {
            $playlist->order = 1;
            foreach ($moviePlaylists as $moviePlaylist) {
                if ($playlist->order == $moviePlaylist->order) {
                    $playlist->order++;
                }
            }
        }else{
            foreach ($moviePlaylists as $moviePlaylist) {
                if ($moviePlaylist->order >= $playlist->order) {
                    $moviePlaylist->order++;
                    $moviePlaylist->save();
                }
            }
        }

        $playlist->user_updated_id = Auth::user()->id;
        $playlist->save();
        return redirect()->back();
    }

    public function updateVideo(Request $request, $movieId)
    {
        $video = Video::findOrFail($request->get('videoId'));
        $video->movie_id = $movieId;
        $sources = $video->sources;
        foreach ($sources as $source) {
            $source->movie_id = $movieId;
            $source->save();
        }
        $video->user_updated_id = Auth::user()->id;
        $video->save();
        return redirect()->back();
    }

    public function tags($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $tags = $movie->tags()->get();
    }

    public function types($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $types = $movie->types()->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $movie->user_updated_id = Auth::user()->id;
        $movie->save();
        $Movie = Movie::destroy($movieId);

        return redirect()->route('backend.movie.index');
    }

    public function addVideo(Request $request, $movieId)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|min:2',
                'description' => 'required|min:2',
                'video' => 'max:'.env('MAX_VIDEO_SIZE', 500000),
            ]
        );
        if ($request->hasFile('video') == false && $request->get('source_key') == '') {
            $validator->after(function ($validator) {
                $validator->errors()->add('video', 'No source key or upload video available');
            });
        }
        if ($validator->fails()){
            return response()->json([
                'error'    => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $movie = Movie::findOrFail($movieId);
        $video = new Video();
        $video->title = $request->get('title');
        $video->tags = $request->get('tags');
        $video->description = $request->get('description');
        $video->user_created_id = Auth::user()->id;
        $video->user_updated_id = Auth::user()->id;
        $video->movie_id = $movieId;
        $video->chap = 1;
        $video->save();
        if ($request->get('slug') == null) {
            $slug = Str::slug($request->get('title') . '-' . $video->id);
            $status = Video::where('slug', $slug)->get();
            $video->slug = count($status) == 0 ? $slug : $slug . '-1';
        } else {
            $video->slug = Str::slug($request->get('slug'));
        }
        $video->save();
        $source = new Source();
        $source->video_id = $video->id;
        $source->language = $request->get('language') == null ? 1 : 2;
        $source->prioritize = 1;
        $source->movie_id = $movieId;
        $source->channel_id = $request->get('channel_id');
        $source->user_created_id = Auth::user()->id;
        $source->user_updated_id = Auth::user()->id;
        $source->save();
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $source->id . '.' . $file->getClientOriginalName();
            $file->storeAs('public/video', $name);
            $source->source_key = '/storage/video/' . $name;
        } else {
            $source->source_key = $request->get('source_key');
        }
        $source->save();
        return redirect()->back();
    }
}
