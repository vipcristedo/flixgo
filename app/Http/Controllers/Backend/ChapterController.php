<?php

namespace App\Http\Controllers\Backend;

use App\Chapter;
use App\Chapter_picture;
use App\Http\Controllers\Controller;
use App\Manga;
use App\Manga_ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chapters = Chapter::orderByRaw('created_at DESC')->get();
        return view('backend.chapters.index')->with([
            'playlists'=>$chapters
        ]);
    }

    public function getData(Request $request){
        $chapters = Chapter::select('id','name','description','status','created_at');

        $chapters = $chapters->orderByRaw('created_at DESC');

        if ($request->has('sort')) {
            if ($request->get('sort') == 'Name') {
                $chapters = $chapters->orderByRaw('name ASC');
            } elseif ($request->get('sort') == 'Release year') {
                $chapters = $chapters->orderByRaw('release_year DESC');
            } elseif ($request->get('sort') == 'Chap') {
                $chapters = $chapters->orderByRaw('chap DESC');
            }
        }
        if ($request->has('name')) {
            $chapters = $chapters->where('name', 'like', "%" . $request->get('name') . "%");
        }

        return DataTables::of($chapters->get()->toArray())
            ->editColumn('id', function ($chapter){
                return '<div class="main__table-text">'. $chapter['id'] .'</div>';
            })
            ->editColumn('name', function ($chapter){
                return '<div class="main__table-text">'. $chapter['name'] .'</div>';
            })
            ->editColumn('description', function ($chapter){
                return '<div class="main__table-text">'. $chapter['description'] .'</div>';
            })
            ->editColumn('status', function ($chapter){
                if ($chapter['status'] == 1) {
                    return '<div class="main__table-text main__table-text--green">Active</div>';
                }else
                    return '<div class="main__table-text main__table-text--green">Hidden</div>';
            })

            ->addColumn('action', function ($chapter) {
                return '
            <div class="main__table-btns">
                <a href="'. route('backend.chapter.edit' , $chapter['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Chỉnh sửa">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.chapter.destroy' , $chapter['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
            })
            ->rawColumns(['id','name','description','status','action'])

            ->make(true);
    }


    public function getPictures(Request $request,$id){
        $pictures = Chapter_picture::where('chapter_id','=',$id);
        $pictures = $pictures->orderBy('order','DESC')->get();

        return DataTables::of($pictures->toArray())
            ->editColumn('id', function ($picture){
                return '<div class="main__table-text">'. $picture['id'] .'</div>';
            })
            ->editColumn('title', function ($picture){
                return '<div class="main__table-text">'. $picture['title'] .'</div>';
            })

            ->editColumn('picture', function ($picture){
                return '<img src="'.asset($picture['link']).'" alt="" class="column_image">';
            })
            ->editColumn('order', function ($picture){
                return '<div class="main__table-text">'. $picture['order'] .'</div>';
            })
            ->editColumn('status', function ($picture){
                if ($picture['status'] == 1) {
                    return '<div class="main__table-text main__table-text--green">Active</div>';
                }else
                    return '<div class="main__table-text main__table-text--green">Hidden</div>';
            })

            ->addColumn('action', function ($picture) {
                return '
            <div class="main__table-btns">
                <button class="main__table-btn main__table-btn--edit edit-pic" data-toggle="tooltip" title="Edit" data-id="'. $picture['id'] .'">
                    <i class="icon ion-ios-create"></i>
                </button>
                <form action="'. route('backend.chapter.removePicture' , $picture['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
            })
            ->rawColumns(['id','title','picture','order','status','action'])

            ->make(true);
    }


    public function create()
    {
        return view('backend.chapters.create');
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
                'name'=>'required|min:2',
                'release_year'=>'required|numeric',
                'chap'=>'numeric',
                'description'=>'required|min:2',
            ],
            [
                'name'=>'Name',
                'Chap'=>'Chap',
                'release_year'=>'Release year',
                'description'=>'Description'
            ]
        );
        if($request->get('chap')){
            if ($request->get('chap')<= 0) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('chap',"chap must be greater than zero");
                });
            }

        }

        if ($validator->fails()){
            if ($request->has('manga_id')) {
                return response()->json([
                    'error'    => true,
                    'messages' => $validator->errors(),
                ], 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $chapter=new Chapter();
        $chapter->user_created_id=Auth::user()->id;
        $chapter->user_updated_id=Auth::user()->id;
        $chapter->name=$request->get('name');
        $chapter->description=$request->get('description');
        $chapter->slug='';
        if($request->get('chap')!=null){
            $chapter->chap=$request->get('chap');
        }
        $chapter->status=1;
        if ($request->get('manga_id') != NULL ) {
            $manga = Manga::findOrFail($request->get('manga_id'));
            $chapter->manga_id = $manga->id;
            Session::flash('status', 'new');
        }
        $chapter->release_year=$request->get('release_year');
        $chapter->save();
        $chapter->slug=Str::slug($chapter->name).'_'.$chapter->id;
        $chapter->save();
        if ($request->get('manga_id') != NULL ) {
            return redirect()->route('backend.manga.edit', $manga->id);
        }
        return redirect()->route('backend.chapter.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chapter = Chapter::findOrFail($id);
        return response()->json([
            'error' => false,
            'chapter'  => $chapter,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $mangaId = NULL)
    {
        $chapter=Chapter::findOrFail($id);
        if ($mangaId != NULL) {
            Session::flash('status','new');
        }
        return view('backend.chapters.edit')->with([
            'chapter'=>$chapter
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->session()->flash('status','new');
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'release_year'=>'required|numeric',
                'chap'=>'numeric',
                'description'=>'required|min:2',
            ],
            [
                'name'=>'Name',
                'Chap'=>'Chap',
                'release_year'=>'Release year',
                'description'=>'Description'
            ]
        );
        if($request->get('chap')!=null) {
            if ($request->get('chap') <= 0) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('chap', "chap must be greater than zero");
                });
            }
        }

        if ($validator->fails()){
            if ($request->has('manga_id')) {
                return response()->json([
                    'error'    => true,
                    'messages' => $validator->errors(),
                ], 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $chapter=Chapter::find($id);
        $chapter->user_updated_id=Auth::user()->id;
        $chapter->name=$request->get('name');
        $chapter->description=$request->get('description');
       if($request->get('chap')!=null){
           $chapter->chap=$request->get('chap');
       }
        $chapter->slug=Str::slug($chapter->name).'_'.$chapter->id;
        $chapter->status=1;
        $chapter->release_year=$request->get('release_year');
        $chapter->save();
        if ($request->has('manga_id')) {
            return response()->json([
                'error' => false,
                'chapter'  => $chapter,
            ]);
        }
        return redirect()->route('backend.chapter.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chapter=Chapter::find($id);
        $chapter->delete();
        return redirect()->back();
    }

    public function detach($id){
        $chapter = Chapter::findOrFail($id);
        $chapter->manga_id = null;
        foreach ($chapter->pictures as $picture) {
            $picture->manga_id = null;
            $picture->save();
        }
        $chapter->user_updated_id = Auth::user()->id;

        $chapter->save();
        return redirect()->back();
    }

    public function addPicture(Request $request,$id){
        $pic=new Chapter_picture();
        $chapter= Chapter::findOrFail($id);

        $pic->chapter_id=$id;
        $pic->order=$request->get('order');

        if (Chapter_picture::where('chapter_id',$id)->where('order',$request->get('order'))->first()!=null) {
           return redirect()->back()->with('err','picture order already exists');
        }
        $pic->title=$request->get('title');
        if($request->get('sources')!=null) {
            $pic->sources = $request->get('sources');
        }
        $pic->manga_id = $chapter->manga_id == NULL?NULL:$chapter->manga_id;
        $pic->status=1;
        $pic->user_created_id=Auth::user()->id;
        $pic->user_updated_id=Auth::user()->id;
        $pic->link='';
        $pic->save();
        //lưu xong
        $link = $request->file('link');
        $name = $pic->id . '.' . $link->getClientOriginalName();
        $link->storeAs('public/images', $name);
        $pic->link = '/storage/images/' . $name;
        $pic->save();
        return redirect()->back();
    }

    public function editPicture($id){
        $pic = Chapter_picture::findOrFail($id);
        return response()->json([
            'pic'=>$pic
        ]);
    }

    public function removePicture($id){
        $pic=Chapter_picture::find($id);
        $pic->delete();
        Session::flash('status','new');
        return redirect()->back();
    }
    public function updatePicture(Request $request,$id){
//        dd($request->all());
        $pic=Chapter_picture::find($id);
        $pic->title=$request->get('title');
        if($pic->order!=$request->get('order')){
            if (Chapter_picture::where('chapter_id',$id)->where('order',$request->get('order'))->first()!=null){
                dd(Chapter_picture::where('order',$request->get('order')->first()));
                return redirect()->back()->with('err','picture order already exists');
            }
        }
        $pic->order=$request->get('order');

        $pic->status=1;
        $pic->user_updated_id=Auth::user()->id;
       if ($request->file('link')!=null){
           $link = $request->file('link');
           $name = $pic->id . '.' . $link->getClientOriginalName();
           $link->storeAs('public/images', $name);
           $pic->link = '/storage/images/' . $name;
       }
        if($request->get('source')!=null) {
            $pic->sources = $request->get('sources');
        }
        $pic->save();
//                    dd($pic);
        return redirect()->back();
}

    public function getChapterAds(Request $request, $chapterId)
    {
        $ads = Manga_ad::select('id', 'link', 'artical', 'object_id','table_name')->where('object_id', '=', $chapterId)->where('table_name', '=', 'chapters');

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
                <a href="' . route('backend.manga_ad.edit', [$ad['id'], $ad['object_id'] ]) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit ad/Add ad picture">
                    <i class="icon ion-ios-create"></i>
                </a>
                <button type="submit" class="main__table-btn main__table-btn--delete" onclick="detachManga_ad(' . $ad['id'] . ')" data-toggle="tooltip" title="Detach">
                    <i class="fa fa-times" aria-hidden="true"></i>
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

    public function getAds(Request $request)
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
    public function updateManga_ad(Request $request, $chapterId)
    {
        $manga_ad = Manga_ad::findOrFail($request->get('manga_adId'));
        $manga_ad->table_name = 'chapters';
        $manga_ad->object_id = $chapterId;
        $manga_ad->user_updated_id = Auth::user()->id;
        $manga_ad->save();
        return redirect()->back();
    }
}
