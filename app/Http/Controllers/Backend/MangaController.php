<?php

namespace App\Http\Controllers\Backend;

use App\Manga;
use App\Type;
use App\Chapter;
use App\Chapter_picture;
use App\Manga_ad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MangaController extends Controller
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
        return view('backend.mangas.index');
    }

    /**
     * Lấy dữ liệu cho danh sách các manga
     */
    public function getData(Request $request)
    {
        $mangas = Manga::select('id', 'name','nominations', 'slug' ,'created_at');

        if ($request->has('sort')) {
            if ($request->get('sort') == 'Name') {
                $mangas = $mangas->orderByRaw('name ASC');
            } elseif ($request->get('sort') == 'Nominations') {
                $mangas = $mangas->orderByRaw('nominations DESC');
            }
        }

        $mangas = $mangas->orderByRaw('created_at DESC');
        if ($request->has('name')) {
            $mangas = $mangas->where('name', 'like', "%" . $request->get('name') . "%");
        }
        if ($request->has('slug')) {
            $mangas = $mangas->where('slug', 'like', "%" . $request->get('slug') . "%");
        }

        return DataTables::of($mangas->get()->toArray())
            ->editColumn('id', function ($manga) {
                return '<div class="main__table-text">' . $manga['id'] . '</div>';
            })
            ->editColumn('name', function ($manga) {
                return '<div class="main__table-text">' . $manga['name'] . '</div>';
            })
            ->editColumn('slug', function ($manga) {
                return '<div class="main__table-text">' . $manga['slug'] . '</div>';
            })
            ->addColumn('action', function ($manga) {
                $nominations = $manga['nominations'] == 0? 
                'class="main__table-btn main__table-btn--delete open-modal btn-nominations" data-toggle="tooltip" title="Turn on nomination">
                <i class="icon ion-ios-radio-button-off"></i>
                '
                :
                'class="main__table-btn main__table-btn--edit open-modal btn-nominations" data-toggle="tooltip" title="Turn off nomination">
                <i class="icon ion-ios-radio-button-on"></i>
                ';
                return '
                <div class="main__table-btns">
                    <a href="#" manga_id="'. $manga['id'].'" data_status="'. $manga['nominations'] .'"
                        '. $nominations . '
                    </a>
                    <a href="' . route('backend.manga.edit', $manga['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                    <form action="' . route('backend.manga.destroy', $manga['id']) . '" method="POST">
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

    public function nominations($mangaId)
    {
        $manga = Manga::find($mangaId);
        $manga->nominations = $manga->nominations == 0?1:0;
        $manga->user_updated_id = Auth::user()->id;
        $manga->save();
        die($manga);
    }

    /**
     * Lấy dữ liệu các chapter của manga hiện tại
     */
    public function getMangaChapters(Request $request, $mangaId)
    {
        $chapters = Chapter::select('id', 'name', 'chap', 'description', 'manga_id')->orderByRaw('chapters.chap ASC')->where('manga_id', '=', $mangaId);

        if ($request->has('name')) {
            $chapters = $chapters->where('name', 'like', "%" . $request->get('name') . "%");
        }

        $chapters = $chapters->get();

        foreach ($chapters as $index => $chapter) {
            $chapter->index = $index+1;
        }

        return DataTables::of($chapters->toArray())
            ->addColumn('action', function ($chapter) {
                return '
            <div class="main__table-btns">
                <button class="main__table-btn main__table-btn--edit edit-chapter" data-toggle="tooltip" title="Edit Chapter" data-chapter="'. $chapter['id'] .'">
                    <i class="icon ion-ios-create"></i>
                </button>
                <a href="' . route('backend.chapter.edit', [$chapter['id'], $chapter['manga_id'] ]) . '" class="main__table-btn main__table-btn--edit open-modal"data-toggle="tooltip" title="Pictures">
                    <i class="ion ion-ios-apps"></i>
                </a>
                <button class="main__table-btn main__table-btn--delete" onclick="detachChapter(' . $chapter['id'] . ')" data-toggle="tooltip" title="Detach">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                <button class="main__table-btn main__table-btn--delete open-modal delete-chapter" data-toggle="tooltip" title="Delete" onclick="event.preventDefault();deleteChapter(' . $chapter['id'] . ')" >
                    <i class="icon ion-ios-trash"></i>
                </button>
            </div>
            ';
            })
            ->editColumn('id', function ($chapter) {
                return '<div class="main__table-text">' . $chapter['index'] . '</div>';
            })
            ->editColumn('name', function ($chapter) {
                return '<div class="main__table-text" data-toggle="tooltip" title="'. $chapter['description'] .'">' . $chapter['name'] . '</div>';
            })
            ->editColumn('chap', function ($chapter) {
                return '<div class="main__table-text">' . $chapter['chap'] . '</div>';
            })
            ->rawColumns(['id', 'action', 'name', 'chap'])
            ->make(true);
    }

    /**
     * Lấy dữ liệu các chapter chưa có manga
     */
    public function getChapters(Request $request)
    {
        $chapters = Chapter::select('id', 'name', 'chap', 'description')->orderByRaw('chapters.chap ASC')->where('manga_id', '=', NULL);

        if ($request->has('name')) {
            $chapters = $chapters->where('name', 'like', "%" . $request->get('name') . "%");
        }

        $chapters = $chapters->get();

        foreach ($chapters as $index => $chapter) {
            $chapter->index = $index+1;
        }

        return DataTables::of($chapters->toArray())
            ->addColumn('action', function ($chapter) {
                return '
            <div class="main__table-btns">
                <input type="checkbox" title="chapters[]" value="' . $chapter['id'] . '" style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange(' . $chapter['id'] . ')">
            </div>
            ';
            })
            ->editColumn('id', function ($chapter) {
                return '<div class="main__table-text">' . $chapter['index'] . '</div>';
            })
            ->editColumn('name', function ($chapter) {
                return '<div class="main__table-text" data-toggle="tooltip" title="'. $chapter['description'] .'">' . $chapter['name'] . '</div>';
            })
            ->editColumn('chap', function ($chapter) {
                return '<div class="main__table-text">' . $chapter['chap'] . '</div>';
            })
            ->rawColumns(['action', 'id', 'name', 'chap',])
            ->make(true);
    }

    public function getMangaManga_ads(Request $request, $mangaId)
    {
        $ads = Manga_ad::select('id', 'link', 'artical', 'object_id','table_name')->where('object_id', '=', $mangaId)->where('table_name', '=', 'mangas');

        if ($request->has('link')) {
            $ads = $ads->where('link', 'like', "%" . $request->get('link') . "%");
        }

        $ads = $ads->get();

        foreach ($ads as $index => $ad) {
            $ad->index = $index+1;
        }

        return DataTables::of($ads->toArray())
            ->addColumn('action', function ($ad) {
                return '
            <div class="main__table-btns">
                <button class="main__table-btn main__table-btn--edit open-modal edit-manga_ad data-toggle="tooltip" title="Edit" data-manga_ad="'. $ad['id'] .'">
                    <i class="icon ion-ios-create"></i>
                </button>
                <button type="submit" class="main__table-btn main__table-btn--delete" onclick="detachManga_ad(' . $ad['id'] . ')" data-toggle="tooltip" title="Detach">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                <button class="main__table-btn main__table-btn--delete open-modal delete-manga_ad" data-toggle="tooltip" title="Delete" onclick="event.preventDefault();deleteManga_ad(' . $ad['id'] . ')" >
                    <i class="icon ion-ios-trash"></i>
                </button>
            </div>
            ';
            })
            ->editColumn('id', function ($ad) {
                return '<div class="main__table-text">' . $ad['index'] . '</div>';
            })
            ->editColumn('link', function ($ad) {
                return '<div class="main__table-text">' . $ad['link'] . '</div>';
            })
            ->editColumn('artical', function ($ad) {
                return '<div class="main__table-text">' . $ad['artical'] . '</div>';
            })
            ->rawColumns(['id', 'action', 'link', 'artical'])
            ->make(true);
    }

    public function getManga_ads(Request $request)
    {
        $ads = Manga_ad::select('id', 'link', 'artical')->where('object_id', '=', NULL);

        if ($request->has('link')) {
            $ads = $ads->where('link', 'like', "%" . $request->get('link') . "%");
        }

        $ads = $ads->get();

        foreach ($ads as $index => $ad) {
            $ad->index = $index+1;
        }

        return DataTables::of($ads->toArray())
            ->addColumn('action', function ($ad) {
                return '
            <div class="main__table-btns">
                <input type="checkbox" title="ads[]" value="' . $ad['id'] . '" style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange1(' . $ad['id'] . ')">
            </div>
            ';
            })
            ->editColumn('id', function ($ad) {
                return '<div class="main__table-text">' . $ad['index'] . '</div>';
            })
            ->editColumn('link', function ($ad) {
                return '<div class="main__table-text">' . $ad['link'] . '</div>';
            })
            ->editColumn('artical', function ($ad) {
                return '<div class="main__table-text">' . $ad['artical'] . '</div>';
            })
            ->rawColumns(['action', 'id', 'link', 'artical',])
            ->make(true);
    }

    public function showData($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        return response()->json([
            'error' => false,
            'manga' => $manga->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = \DB::table('options')->where('options.name', '=', 'country')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $types = Type::where('table_name', '=', 'mangas')->get();

        return view('backend.mangas.create')->with([
            'country' => $country->toArray(),
            'types' => $types->toArray(),
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
                'description' => 'required|min:8',
                'release_year' => 'required|numeric',
                'age' => 'required|numeric',
            ],
            [
                'name' => 'Name',
                'description' => 'Description',
                'release_year' => 'Release year',
                'age' => 'Age',
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $manga = new Manga();
        $manga->description = $request->get('description');

        $manga->name = $request->get('name');
        $manga->author = $request->get('author')==NULL? 'Anonymous':$request->get('author');
        $manga->age = $request->get('age');
        $manga->release_year = $request->get('release_year');
        $manga->country = $request->get('country');
        $manga->status = 1;
        $manga->rate = 5;
        $manga->nominations = 1;
        // $manga->rate = $request->get('rate');
        $manga->card_cover = '';

        $manga->user_created_id = Auth::user()->id; // Auth::user()->id;
        $manga->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $manga->save();
        if ($request->hasFile('card_cover')) {
            $card_cover = $request->file('card_cover');
            $name = 'manga'. $manga->id . '.' . $card_cover->getClientOriginalName();
            $card_cover->storeAs('public/images', $name);
            $manga->card_cover = '/storage/images/' . $name;
        }

        if ($request->get('slug') != null) {
            $manga->slug = Str::slug($request->get('slug'));
        } else {
            $status = Manga::where('slug', Str::slug($request->get('name') . '-' . $manga->id))->get();
            if (count($status) == null) {
                $manga->slug = Str::slug($request->get('name') . '-' . $manga->id);
            } else {
                $manga->slug = Str::slug($request->get('name') . '-' . $manga->id . '-1');
            }
        }
        $manga->save();
        $types = $request->get('types');
        $manga->types()->attach($types, [
            'user_created_id' => Auth::user()->id,
            'user_updated_id' => Auth::user()->id
        ]);

        $request->session()->flash('status','new');

        return redirect()->route('backend.manga.edit',$manga->id);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Manga $manga
     * @return \Illuminate\Http\Response
     */
    public function show($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        return view('backend.mangas.show')->with([
            'manga' => $manga
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Manga $manga
     * @return \Illuminate\Http\Response
     */
    public function edit($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        $country = \DB::table('options')->where('options.name', '=', 'country')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $types = Type::where('table_name', '=', 'mangas')->get();
        $mangaTypes = $manga->types()->get();
        $chapters = Chapter::where('manga_id', '=', '')->get();
        $mangaChapters = $manga->chapters()->get();
        // dd($mangaTypes->toArray());
        return view('backend.mangas.edit')->with([
            'manga' => $manga->toArray(),
            'country' => $country->toArray(),
            'types' => $types->toArray(),
            'mangaTypes' => $mangaTypes->toArray(),
            'chapters' => $chapters,
            'mangaChapters' => $mangaChapters
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Manga $manga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mangaId)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|min:2',
                'description' => 'required|min:8',
                'release_year' => 'required|numeric',
                'age' => 'required|numeric',
            ],
            [
                'name' => 'Name',
                'description' => 'Description',
                'release_year' => 'Release year',
                'age' => 'Age',
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $manga = Manga::findOrFail($mangaId);

        $manga->name = $request->get('name');
        $manga->author = $request->get('author')==NULL?'Anonymous':$request->get('author');
        $manga->description = $request->get('description');
        $manga->age = $request->get('age');
        $manga->release_year = $request->get('release_year');
        $manga->country = $request->get('country');
        // $manga->rate = $request->get('rate');

        if ($request->get('slug') != null) {
            $manga->slug = Str::slug($request->get('slug'));
        } else {
            $status = Manga::where('slug', Str::slug($request->get('name') . '-' . $manga->id))->where('id', '!=', $manga->id)->get();
            if (count($status) == null) {
                $manga->slug = Str::slug($request->get('name') . '-' . $manga->id);
            } else {
                $manga->slug = Str::slug($request->get('name') . '-' . $manga->id . '-1');
            }
        }
        $manga->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $manga->save();

        if ($request->hasFile('card_cover')) {
            $card_cover = $request->file('card_cover');
            $name = 'manga'. $manga->id . '.' . $card_cover->getClientOriginalName();
            $link = str_replace('/storage/images/', '', $manga->card_cover);
            Storage::disk('public')->delete('/images/' . $link);
            $card_cover->storeAs('public/images', $name);
            $manga->card_cover = '/storage/images/' . $name;
        }

        $manga->save();

        $types = $request->get('types');
        $manga->types()->sync($types);

        return redirect()->route('backend.manga.index');
    }

    public function updateChapter(Request $request, $mangaId)
    {
        $mangaChapters = Manga::findOrFail($mangaId)->chapters()->get()->sortBy('chap');
        $chapter = Chapter::findOrFail($request->get('chapterId'));
        $chapter->manga_id = $mangaId;
        $chapter_pictures = $chapter->pictures;
        foreach ($chapter_pictures as $chapter_picture) {
            $chapter_picture->manga_id = $mangaId;
            $chapter_picture->user_updated_id = Auth::user()->id;
            $chapter_picture->save();
        }
        if ($chapter->chap == NULL) {
            $chapter->chap = 1;
            foreach ($mangaChapters as $mangaChapter) {
                if ($chapter->chap == $mangaChapter->chap) {
                    $chapter->chap++;
                }
            }
        }else{
            foreach ($mangaChapters as $mangaChapter) {
                if ($mangaChapter->chap >= $chapter->chap) {
                    $mangaChapter->chap++;
                    $mangaChapter->save();
                }
            }
        }
        $chapter->user_updated_id = Auth::user()->id;
        $chapter->save();
        return redirect()->back();
    }

    public function updateManga_ad(Request $request, $mangaId)
    {
        $manga_ad = Manga_ad::findOrFail($request->get('manga_adId'));
        $manga_ad->table_name = 'mangas';
        $manga_ad->object_id = $mangaId;
        $manga_ad->user_updated_id = Auth::user()->id;
        $manga_ad->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Manga $manga
     * @return \Illuminate\Http\Response
     */
    public function destroy($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        $manga->user_updated_id = Auth::user()->id;
        $manga->save();
        $Manga = Manga::destroy($mangaId);

        return redirect()->route('backend.manga.index');
    }
}
