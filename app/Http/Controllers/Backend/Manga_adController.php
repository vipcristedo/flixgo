<?php

namespace App\Http\Controllers\Backend;

use App\Manga;
use App\Manga_ad;
use App\Type;
use App\Chapter;
use App\Chapter_picture;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Manga_adController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.manga_ads.index');
    }

    public function getData(Request $request)
    {
        $manga_ads = Manga_ad::select('id', 'link','artical', 'object_id', 'table_name', 'created_at');

        $manga_ads = $manga_ads->orderByRaw('created_at DESC');
        if ($request->has('link')) {
            $manga_ads = $manga_ads->where('link', 'like', "%" . $request->get('link') . "%");
        }

        return DataTables::of($manga_ads->get()->toArray())
            ->editColumn('id', function ($manga_ad) {
                return '<div class="main__table-text">' . $manga_ad['id'] . '</div>';
            })
            ->editColumn('link', function ($manga_ad) {
                return '<div class="main__table-text">' . $manga_ad['link'] . '</div>';
            })
            ->editColumn('artical', function ($manga_ad) {
                return '<div class="main__table-text">' . $manga_ad['artical'] . '</div>';
            })
            ->addColumn('action', function ($manga_ad) {
                return '
                <div class="main__table-btns">
                    <a href="' . route('backend.manga_ad.edit', $manga_ad['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                    <form action="' . route('backend.manga_ad.destroy', $manga_ad['id']) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                            <i class="icon ion-ios-trash"></i>
                        </button>
                    </form>
                </div>
                ';
            })
            ->rawColumns(['id', 'link', 'artical', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.manga_ads.create');
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
                'link' => 'required|min:2',
                'artical' => 'required|min:2',
            ],
            [
                'link' => 'Link',
                'artical' => 'Image link',
            ]
        );
        if ($validator->fails()) {
            if ($request->has('table_name')) {
                Session::flash('status','tab-3');
            }
            if ($request->get('table_name') == 'mangas') {
                return response()->json([
                    'error'    => true,
                    'messages' => $validator->errors(),
                ], 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $manga_ad = new Manga_ad();
        $manga_ad->link = $request->get('link');
        $manga_ad->artical = $request->get('artical');

        if ($request->get('table_name') == 'mangas') {
            $manga_ad->table_name = $request->get('table_name');
            if (Manga::findOrFail($request->get('object_id'))) {
                $manga_ad->object_id = $request->get('object_id');
            }
        }elseif($request->get('table_name') == 'chapters'){
            $manga_ad->table_name = $request->get('table_name');
            if (Chapter::findOrFail($request->get('object_id'))) {
                $manga_ad->object_id = $request->get('object_id');
            }
        }
        $manga_ad->table_name = $request->get('table_name');
        $manga_ad->object_id = $request->get('object_id');

        $manga_ad->user_created_id = Auth::user()->id;
        $manga_ad->user_updated_id = Auth::user()->id;

        $manga_ad->save();
        return redirect()->back();
        return response()->json(['success' => true],200);
//        return redirect()->route('backend.manga_ad.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Manga_ad  $manga_ad
     * @return \Illuminate\Http\Response
     */
    public function show($manga_adId)
    {
        $manga_ad = Manga_ad::findOrFail($manga_adId);
        return response()->json([
            'error' => false,
            'manga_ad'  => $manga_ad,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Manga_ad  $manga_ad
     * @return \Illuminate\Http\Response
     */
    public function edit($manga_adId, $objId = NULL)
    {
        $manga_ad = Manga_ad::findOrFail($manga_adId)->toArray();
        if ($manga_ad['table_name'] != NULL && $manga_ad['object_id'] != NULL && $objId != NULL) {
            Session::flash('table_name', $manga_ad['table_name']);
            Session::flash('object_id', $manga_ad['object_id']);
        }
        return view('backend.manga_ads.edit')->with([
            'manga_ad' => $manga_ad,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Manga_ad  $manga_ad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $manga_adId)
    {
        $validator = Validator::make($request->all(),
            [
                'link' => 'required|min:2',
                'artical' => 'required|min:2',
            ],
            [
                'link' => 'Link',
                'artical' => 'Image link',
            ]
        );
        if ($validator->fails()) {
            if ($request->get('table_name')=='mangas') {
                return response()->json([
                    'error'    => true,
                    'messages' => $validator->errors(),
                ], 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $manga_ad = Manga_ad::findOrFail($manga_adId);
        $manga_ad->link = $request->get('link');
        $manga_ad->artical = $request->get('artical');

        $manga_ad->user_updated_id = Auth::user()->id;

        $manga_ad->save();

        if ($request->has('table_name') && $request->has('object_id') && $manga_ad->table_name != NULL && $manga_ad->object_id != NULL ) {
            if ($manga_ad->table_name = 'mangas') {
                return response()->json([
                    'error' => false,
                    'manga_ad'  => $manga_ad,
                ]);
            }
            return redirect()->route('backend.chapter.edit', $manga_ad->object_id);
        }

        return redirect()->route('backend.manga_ad.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Manga_ad  $manga_ad
     * @return \Illuminate\Http\Response
     */
    public function destroy($manga_adId)
    {
        $manga_ad = Manga_ad::findOrFail($manga_adId);
        $manga_ad->user_updated_id = Auth::user()->id;
        $manga_ad->save();
        $Manga_ad = Manga_ad::destroy($manga_adId);

        return redirect()->route('backend.manga_ad.index');
    }

    public function detach($manga_adId){
        $manga_ad = Manga_ad::findOrFail($manga_adId);
        $manga_ad->object_id = null;
        $manga_ad->table_name = null;
        $manga_ad->user_updated_id = Auth::user()->id;

        $manga_ad->save();
        return redirect()->back();
    }
}
