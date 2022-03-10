<?php

namespace App\Http\Controllers\Backend;

use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderByRaw('created_at DESC')->paginate(10);
        $links = str_replace("pagination","paginator", str_replace("page-item", "paginator__item", $tags->links()));
        $links = str_replace("active","paginator__item--active", $links);
        $links = str_replace('<span class="page-link">','<a class="page-link" href="#">', $links);
        $links = str_replace('</span>','</a>', $links);
        return view('backend.tags.index')->with([
            'tags'=>$tags,
            'links'=>$links
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.tags.create');
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
                'name'=>'required|min:2',
                'slug'=>'unique:tags'
            ],
            [
                'required' => ':attribute Không được để trống',
                'min' => ':attribute Không được nhỏ hơn :min kí tự',
                'unique'=>':attribute đã tồn tại',
            ],
            [
                'name' => 'Tên Tag',
                'slug' => 'Đường dẫn Tag',
            ]
        );
        if($request->get('name')!=''){
        $status = Tag::where('name', 'like binary', $request->get('name'))->get();

        if (!count($status) ==null) {
        $validator->after(function ($validator) {
            $validator->errors()->add('name','Tên Tag đã tồn tại');
        });
        }

        }
        if ($validator->fails()) {
            // return response()->json([
            //     'error'    => true,
            //     'messages' => $validator->errors(),
            // ], 422);
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $tag = new Tag();
        $tag->name = $request->get('name');

        $tag->user_created_id = Auth::user()->id; // Auth::user()->id;
        $tag->user_updated_id = Auth::user()->id; // Auth::user()->id;

        $tag->save();
        if ($request->get('slug')!=null) {
            $tag->slug = Str::slug($request->get('slug'));
        }else{
            $status = Tag::where('slug',Str::slug($request->get('name').'-'.$tag->id))->get();
            if (count($status) == null) {
                $tag->slug = Str::slug($request->get('name').'-'.$tag->id);
            }else{
                $tag->slug = Str::slug($request->get('name').'-'.$tag->id.'-1');
            }
        }
        
        $tag->save();

        return redirect()->route('backend.tag.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        $created_at = date('H:i:s d-m-Y',strtotime($tag->created_at));
        $user = \DB::table('users')->where('id',$tag->user_id)->value('name');
        return view('backend.tags.show')->with([
            'tag'=>$tag,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('backend.tags.edit')->with([
            'tag'=>$tag,
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
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'slug'=>Rule::unique('tags')->ignore($id),
            ],
            [
                'required' => ':attribute Không được để trống',
                'min' => ':attribute Không được nhỏ hơn :min kí tự',
                'unique'=>':attribute đã tồn tại',
            ],
            [
                'name' => 'Tên Tag',
                'slug' => 'Đường dẫn Tag',
            ]
        );
        if($request->get('name')!=''){

        $status = Tag::where('name', 'like binary', $request->get('name'))->where('id','!=',$request->get('tag_id'))->get();
        
        if (count($status) >0) {
        $validator->after(function ($validator) {
            $validator->errors()->add('name','Tên Tag đã tồn tại');
        });
        }
        }
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $tag = Tag::findOrFail($id);
        $tag->name = trim($request->get('name'));
        $tag->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $tag->save();
        if ($request->get('slug')==null) {
            $slug = slug($request->get('name').'-'.$tag->id);
            $status = Tag::where('slug',$slug)->get();
            $tag->slug = count($status)==0?$slug:$slug.'-1';
        }else{
            $tag->slug = Str::slug($request->get('slug'));
        }
        $tag->save();
        Session::flash('msg','Đã cập nhật Tag '.$tag->name);
        return response()->json([
            'error' => false,
            'tag'  => $tag,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function movies($tagId){
        $tag = Tag::findOrFail($tagId);
        $movies = $tag->movies()->get();
        return view('backend.tags.movies')->with([
            'tag'=>$tag,
            'movies'=>$movies
        ]);
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($adminId)->movies()->detach();
        $tag = Tag::destroy($id);

        return redirect()->back();
    }
}
