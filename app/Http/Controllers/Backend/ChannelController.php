<?php

namespace App\Http\Controllers\Backend;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.channels.index');
    }

    public function getData(Request $request){
        $channels = Channel::select('id','title','description','status','created_at');

        if($request->has('sort')){
            if ($request->get('sort')=='Title') {
                $channels = $channels->orderByRaw('title ASC');
            }elseif($request->get('sort')=='Active'){
                $channels = $channels->where('status', '=', 1);
            }elseif($request->get('sort')=='Hidden'){
                $channels = $channels->where('status', '=', 0);
            }
        }
        if($request->has('title')){
            $channels = $channels->where('title','like',"%" . $request->get('title') . "%");
        }

        $channels = $channels->orderByRaw('created_at DESC');

        return DataTables::of($channels->get()->toArray())
        ->editColumn('id', function ($channel){
            return '<div class="main__table-text">'. $channel['id'] .'</div>';
        })
        ->editColumn('title', function ($channel){
            return '<div class="main__table-text">'. $channel['title'] .'</div>';
        })
        ->editColumn('description', function ($channel){
            return '<div class="main__table-text">'. $channel['description'] .'</div>';
        })
        ->editColumn('status', function ($channel){
            if ($channel['status'] == 1) {
                return '<div class="main__table-text main__table-text--green">Active</div>';
            }else
            return '<div class="main__table-text main__table-text--red">Hidden</div>';
        })

        ->addColumn('action', function ($channel) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.channel.edit' , $channel['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.channel.destroy' , $channel['id']) .'" method="POST">
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channel_types = \DB::table('options')->where('options.name', '=', 'channel_type')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $totalChannel = Channel::all()->count();
        return view('backend.channels.create')->with([
            'channel_types'=>$channel_types->toArray(),
            'totalChannel'=>$totalChannel,
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

        $channel = new Channel();

        $channel->title = $request->get('title');
        $channel->link = $request->get('link');
        $channel->description = $request->get('description');
        $channel->channel_type = $request->get('channel_type');
        $channel->status = 1;
        $channel->order = $request->get('order');

        $channel->user_created_id = Auth::user()->id;
        $channel->user_updated_id = Auth::user()->id;
        $channel->save();
        return redirect()->route('backend.channel.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($channelId)
    {
        $channel = Channel::findOrFail($channelId);
        return view('backend.channels.show')->with([
            'channel'=>$channel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit($channelId)
    {
        $channel = Channel::findOrFail($channelId);
        $channel_types = \DB::table('options')->where('options.name', '=', 'channel_type')->join('option_values', 'options.id', '=', 'option_values.option_id')->get();
        $totalChannel = Channel::all()->count();
        return view('backend.channels.edit')->with([
            'channel_types'=>$channel_types->toArray(),
            'channel'=>$channel->toArray(),
            'totalChannel'=>$totalChannel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $channelId)
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

        $channel = Channel::findOrFail($channelId);

        $channel->title = $request->get('title');
        $channel->link = $request->get('link');
        $channel->description = $request->get('description');
        $channel->channel_type = $request->get('channel_type');
        $channel->order = $request->get('order');

        $channel->user_updated_id = Auth::user()->id;
        $channel->save();
        return redirect()->route('backend.channel.index');
    }

    public function changeStatus($channelId){
        $channel = Channel::findOrFail($channelId);
        $channel->status = $channel->status == 0 ? 1:0;
        $channel->user_updated_id =  Auth::user()->id;
        $channel->save();
        return redirect()->route('backend.channel.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy($channelId)
    {
        $channel = Channel::destroy($channelId);
        return redirect()->route('backend.channel.index');
    }
}
