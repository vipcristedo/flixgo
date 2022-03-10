<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Source;
use App\Channel;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session::flash('status','new');
        $validator = Validator::make($request->all(),
            [
                'prioritize'=>'nullable|numeric',
            ],
            [
                'prioritize' => 'Prioritize',
                'video' => 'max:'.env('MAX_VIDEO_SIZE', 500000),
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

        $source = new Source();
        $source->video_id = $request->get('video_id')==null?null:$request->get('video_id');
        $source->language = $request->get('language')==null?1:2;

        $videoSources = Video::findOrFail($source->video_id)->sources()->get()->sortBy('prioritize');
        $source->prioritize = $request->get('prioritize');
        if ($source->prioritize == NULL) {
            $source->prioritize = 1;
            foreach ($videoSources as $videoSource) {
                if ($source->prioritize == $videoSource->prioritize) {
                    $source->prioritize++;
                }
            }
        }else{
            foreach ($videoSources as $videoSource) {
                if ($videoSource->prioritize >= $source->prioritize) {
                    $videoSource->prioritize++;
                    $videoSource->save();
                }
            }
        }
        
        $source->channel_id = $request->get('channel_id');
        if ($request->get('movie_id')!=null) $source->movie_id = $request->get('movie_id');
        else $source->movie_id = null;
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function show($sourceId)
    {
        $source = Source::findOrFail($sourceId);
        if (Channel::findOrFail($source->channel_id)->channel_type == 2) {
            $source->source_key = '';
        }
        return response()->json([
            'error' => false,
            'source'  => $source,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function edit($sourceId)
    {
        $source = Source::findOrFail($sourceId)->toArray();
        $channels = Channel::all();
        $video = Video::findOrFail($source['video_id']);

        if (Channel::findOrFail($source['channel_id'])->channel_type == 2) {
            $source['source_key'] = '';
        }
        return view('backend.videos.editSource')->with([
            'channels'=>$channels->toArray(),
            'source'=>$source,
            'video'=>$video->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sourceId)
    {
        Session::flash('status', 'new');
        $validator = Validator::make($request->all(),
            [
                'prioritize'=>'numeric',
            ],
            [
                'prioritize' => 'Prioritize',
                'video' => 'max:'.env('MAX_VIDEO_SIZE', 500000),
            ]
        );
        $source = Source::findOrFail($sourceId);
        if ($request->has('source_id')) {
            $source = Source::findOrFail($request->get('source_id'));
        }
        $channel = Channel::findOrFail($request->get('channel_id'));
        if ($channel->channel_type == 2 && $source->channel_id != $request->get('channel_id')) {
            if ($request->hasFile('video')==false){
                $validator->after(function ($validator) {
                    $validator->errors()->add('video','No upload video available');
                });
            }
        }
        if ($channel->channel_type == 1) {
            if ($request->get('source_key') == NULL) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('video','No source key available');
                });
            }
        }
        if ($validator->fails()){
            return response()->json([
                'error'    => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $video = Video::findOrFail($source->video_id);
        $videoSources = $video->sources()->get()->sortBy('prioritize');

        if($request->get('prioritize') != $source->prioritize){
            if ($request->get('prioritize') != NULL) {
                $beforePrioritize = $source->prioritize;
                $afterPrioritize = $request->get('prioritize');                
                if ($afterPrioritize > $beforePrioritize) {
                    foreach ($videoSources as $videoSource) {
                        if ($videoSource->prioritize > $beforePrioritize && $videoSource->prioritize <= $afterPrioritize) {
                            $videoSource->prioritize--;
                            $videoSource->save();
                        }
                    }
                }elseif($afterPrioritize < $beforePrioritize){
                    foreach ($videoSources as $videoSource) {
                        if ($videoSource->prioritize >= $afterPrioritize && $videoSource->prioritize < $beforePrioritize) {
                            $videoSource->prioritize++;
                            $videoSource->save();
                        }
                    }
                }
                $source->prioritize = $afterPrioritize;
            }else{
                $source->prioritize = 1;
                foreach ($videoSources as $videoSource) {
                    if ($source->prioritize == $videoSource->prioritize) {
                        $source->prioritize++;
                    }
                }
            }
        }

        $source->channel_id = $request->get('channel_id');

        $source->user_updated_id = Auth::user()->id; // Auth::user()->id;

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $source->id.'.'.$file->getClientOriginalName();
            $link = str_replace('/storage/video/', '', $source->source_key);
            Storage::disk('public')->delete('/video/' . $link);
            $file->storeAs('public/video',$name);
            $source->source_key = '/storage/video/'.$name;
        }else{
            if($source->source_key != $request->get('source_key') && $channel->channel_type == 1){
                $link = str_replace('/storage/video/', '', $source->source_key);
                Storage::disk('public')->delete('/video/' . $link);
                $source->source_key = $request->get('source_key');
            }
        }
        $source->save();

        // return response()->json([
        //     'error' => false,
        //     'source'  => $source,
        // ], 200);
        if ($video->playlist_id != NULL && $request->get('playlistId') != NULL) {
            return redirect()->route('backend.video.edit', [$source->video_id, $video->playlist_id]);
        }

        return redirect()->route('backend.video.edit', $source->video_id);
    }

    public function changeStatus($sourceId){
        $source = Source::findOrFail($sourceId);
        $source->status = $source->status == 0 ? 1:0;
        $source->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $source->save();
        return redirect()->route('backend.source.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function destroy($sourceId)
    {
        $source = Source::destroy($sourceId);
        return  redirect()->back();
    }
}
