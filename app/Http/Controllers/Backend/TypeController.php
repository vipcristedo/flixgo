<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Type;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.types.index');
    }

    public function getData(Request $request){
        $types = Type::select('id', 'title', 'description', 'created_at');
        if($request->has('sort')){
            if ($request->get('sort')=='Title') {
                $types = $types->orderByRaw('title ASC');
            }elseif ($request->get('sort')=='Date created') {
                $types = $types->orderByRaw('created_at DESC');
            }elseif($request->get('sort')=='Movies types'){
                $types = $types->where('table_name', '=', 'movies');
            }elseif($request->get('sort')=='Mangas types'){
                $types = $types->where('table_name', '=', 'mangas');
            }
        }
        if($request->has('title')){
            $types = $types->where('title','like',"%" . $request->get('title') . "%");
        }

        return DataTables::of($types->get()->toArray())
        ->editColumn('id', function ($type){
            return '<div class="main__table-text">'. $type['id'] .'</div>';
        })
        ->editColumn('title', function ($type){
            return '<div class="main__table-text">'. $type['title'] .'</div>';
        })
        ->editColumn('description', function ($type){
            return '<div class="main__table-text">'. $type['description'] .'</div>';
        })

        ->addColumn('action', function ($type) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.type.edit' , $type['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.type.destroy' , $type['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
        })
        ->rawColumns(['id', 'title', 'description', 'action'])

        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.types.create');
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
                'description'=>'required|min:8',
            ],
            [
                'title' => 'Title',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = new Type();

        $type->title = $request->get('title');
        $type->table_name = $request->get('table_name');
        $type->description = $request->get('description');

        $type->user_created_id = Auth::user()->id; // Auth::user()->id;
        $type->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $type->save();
        if ($request->get('slug')!=null) {
            $type->slug = Str::slug($request->get('slug'));
        }else{
            $status = Type::where('slug',Str::slug($request->get('title').'-'.$type->id))->get();
            if (count($status) == null) {
                $type->slug = Str::slug($request->get('title').'-'.$type->id);
            }else{
                $type->slug = Str::slug($request->get('title').'-'.$type->id.'-1');
            }
        }
        $type->save();

        return redirect()->route('backend.type.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show($typeId)
    {
        $type = Type::findOrFail($typeId);
        // return view('backend.types.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit($typeId)
    {
        $type = Type::findOrFail($typeId);
        return view('backend.types.edit')->with([
            'type'=>$type->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $typeId)
    {
        $validator = Validator::make($request->all(),
            [
                'title'=>'required|min:2',
                'description'=>'required|min:8',
            ],
            [
                'title' => 'Title',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = Type::findOrFail($typeId);

        $type->title = $request->get('title');
        $type->table_name = $request->get('table_name');
        $type->description = $request->get('description');
        if ($request->get('slug')!=null) {
            $type->slug = Str::slug($request->get('slug'));
        }else{
            $status = Type::where('slug',Str::slug($request->get('title').'-'.$type->id))->where('id', '!=', $type->id)->get();
            if (count($status) == null) {
                $type->slug = Str::slug($request->get('title').'-'.$type->id);
            }else{
                $type->slug = Str::slug($request->get('title').'-'.$type->id.'-1');
            }
        }
        $type->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $type->save();

        return redirect()->route('backend.type.index');
    }

    public function movies($typeId){
        $type = Type::findOrFail($typeId);
        $movies = $type->movies()->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($typeId)
    {
        $type = Type::findOrFail($typeId)->movies()->detach();
        $type = Type::destroy($typeId);

        return redirect()->route('backend.type.index');
    }
}
