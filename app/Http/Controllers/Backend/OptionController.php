<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $options = Option::orderByRaw('created_at DESC')->paginate(10);
        $links = str_replace("pagination","paginator", str_replace("page-item", "paginator__item", $options->links()));
        $links = str_replace("active","paginator__item--active", $links);
        $links = str_replace('<span class="page-link">','<a class="page-link" href="#">', $links);
        $links = str_replace('</span>','</a>', $links);
        return view('backend.options.index')->with([
            'options'=>$options,
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
        return view('backend.options.create');
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
                'description'=>'required|min:8',
            ],
            [
                'name' => 'Name',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $option = new Option();

        $option->name = $request->get('name');
        $option->description = $request->get('description');

        $option->user_created_id = Auth::user()->id; // Auth::user()->id;
        $option->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $option->save();

        return redirect()->route('backend.option.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function show($optionId)
    {
        $option = Option::findOrFail($optionId);
        return view('backend.options.show')->with([
            'option'=>$option
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function edit($optionId)
    {
        $option = Option::findOrFail($optionId);
        return view('backend.options.create')->with([
            'option'=>$option
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $optionId)
    {
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'description'=>'required|min:8',
            ],
            [
                'name' => 'Name',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $option = Option::findOrFail($optionId);

        $option->name = $request->get('name');
        $option->description = $request->get('description');

        $option->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $option->save();

        return redirect()->route('backend.option.index');
    }

    public function changeStatus($optionId){
        $option = Option::findOrFail($optionId);
        $option->status = $option->status == 0 ? 1:0;
        $option->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $option->save();
        return redirect()->route('backend.option.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function destroy($optionId)
    {
        $option = Option::destroy($optionId);
    }
}
