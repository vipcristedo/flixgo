<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Movie;
use Illuminate\Http\Request;
use App\Option;
use App\Option_value;
use App\Type;
use App\Source;
use App\Video;
use App\Channel;
use App\Playlist;

class MovieController extends Controller
{
    public function watchMovie($slug,$sever = 1,$video = null)
    {
        $movie = Movie::select('id', 'name','name_ja','age', 'description', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')->where('slug', $slug)->first();
        if ($movie == null) return abort(404);
        $types = $movie->Types->toArray();
        $tags = $movie->tags->toArray();

        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == 'country') {

                $optionValues = $option->optionValue;

                foreach ($optionValues as $optionValue) {
                    if ($optionValue->order == $movie->country)
                        $movie->country = $optionValue->name;
                }
            }
            if ($option->name == 'quality') {

                $optionValues = $option->optionValue;

                foreach ($optionValues as $optionValue) {
                    if ($optionValue->order == $movie->country)
                        $movie->quality = $optionValue->name;
                }

            }
        }

        if ($movie['genre'] == 1) {
            //Trường hợp phim lẻ
            if ($sever == 1) {
                $source = Source::select('sources.id', 'source_key', 'video_id', 'prioritize', 'status', 'movie_id', 'channel_id')
                    ->join('movies', function ($join) {
                        $join->on('sources.movie_id', '=', 'movies.id');
                    })->where('movies.id', $movie->id)
                    ->where('sources.status',1)
                    ->where('prioritize', 1)->first();
            }
            else {
                $source = Source::select('sources.id', 'source_key', 'video_id', 'prioritize', 'sources.status', 'movie_id', 'channel_id')
                    ->join('movies', function ($join) {
                        $join->on('sources.movie_id', '=', 'movies.id');
                    })->join('channels', function ($join) {
                        $join->on('sources.channel_id', '=', 'channels.id');
                    })->where('channels.order', $sever)
                    ->where('sources.status',1)
                    ->where('movies.id', $movie->id)->first();
            }
            if ($source == null) abort(404);

            $channel = Channel::select('channels.id', 'title', 'channel_type','status','order')
                ->where('status',1)
                ->where('channels.id',$source->channel_id)
                ->first()->toArray();


            $backups = Channel::select('channels.id', 'channels.title', 'channels.status','channel_type','order')
                ->join('sources', function ($join) {
                    $join->on('channels.id', '=', 'channel_id');
                })->join('videos', function ($join) {
                    $join->on('sources.video_id', '=', 'videos.id');
                })->where('channels.status', 1)
                ->where('videos.id', $source->video_id)->get()->toArray();
            $video=$movie->videos()->select('videos.id','tags')->first();
            if($video==null) abort(404) ;
            return view('frontend.pages.movie.movie_detail')->with([
                'movie' => $movie,
                'types' => $types,
                'source' => $source,
                'channel' => $channel,
                'backups' => $backups,
                'tags' => $tags,
                'video' => $video->toArray()
            ]);
        } else {
            //Trường hợp phim bộ
            if ($video == null) {
                $video_movie = Video::select('videos.id', 'videos.title','videos.status', 'tags','slug', 'videos.movie_id', 'playlist_id', 'chap')
                    ->where('videos.movie_id', $movie['id'])
                    ->where('chap', '1')
                    ->where('videos.status', '1')
                    ->where('playlists.status', '1')
                    ->join('playlists', function ($join) {
                        $join->on('playlist_id', '=', 'playlists.id');
                    })->where('order', 1)
                    ->first();
            } else {
                $video_movie = Video::select('id', 'title','status', 'slug', 'movie_id', 'playlist_id', 'chap')
                    ->where('status', '1')
                    ->where('slug', $video)
                    ->first();
            }


            if ($video_movie == null) abort(404) ;

            if ($sever == 1) {

                $source = Source::select('id', 'source_key', 'video_id','status', 'prioritize', 'status', 'movie_id', 'channel_id','video_id')
                    ->where('prioritize', 1)
                    ->where('status', '1')
                    ->where('video_id', $video_movie->id);
            } else {
                $source = Source::select('sources.id', 'source_key','sources.status', 'video_id', 'prioritize', 'sources.status', 'sources.movie_id', 'channel_id')
                    ->join('channels', function ($join) {
                        $join->on('sources.channel_id', '=', 'channels.id');
                    })->where('channels.order', $sever)
                    ->where('sources.status', '1')
                    ->where('video_id', $video_movie->id);
            }

            $source = $source->first();


            //Lấy ra channel của soures sẽ sử dụng
            $channel = Channel::select('id', 'channel_type','status','order')
                ->where('id', $source->channel_id)
                ->where('status',1)
                ->first();

            //Lấy ra các channel backup
            $backups = Channel::select('channels.id', 'channels.title', 'channel_type','order')
                ->join('sources', function ($join) {
                    $join->on('channels.id', '=', 'channel_id');
                })->join('videos', function ($join) {
                    $join->on('sources.video_id', '=', 'videos.id');
                })->where('channels.status', 1)->where('videos.id', $video_movie['id'])->get()->toArray();

            $playlists = $movie->playlists()->select('id', 'title', 'description', 'status')->where('status',1)->get();
            foreach ($playlists as $playlist) {
                $playlist->videos = $playlist->videos()->orderBy('chap')->get()->toArray();
            }
            $playlists = $playlists->toArray();
            $movie = $movie->toArray();


            return view('frontend.pages.movie.movie_series')->with([
                'movie' => $movie,
                'types' => $types,
                'source' => $source,
                'playlists' => $playlists,
                'channel' => $channel,
                'backups' => $backups,
                'video' => $video_movie,
                'tags' => $tags
            ]);
        }
    }


    public function listMovie($key, $slug)
    {
        if ($key == 'type') {
            $type = Type::select('id', 'title', 'slug')->where('slug', $slug)->first();
            $movies = $type->movies()->select('movies.id', 'name','name_ja', 'age', 'description', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
                ->paginate(12);
            foreach ($movies as $video) {
                $video->type = $video->types()->select('title', 'slug')->get()->toArray();
            }

            $params['type'] = $type->toArray();

        } else if ($key == 'country') {
            $option_id = Option::select('id')->where('name', 'country')->first();
            $country = Option_value::select('name', 'order')->where('option_id', $option_id->id)->where('order', $slug)->first();
            $movies = Movie::select('id', 'name', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
                ->where('country', $slug)
                ->paginate(12);
            foreach ($movies as $video) {
                $video->type = $video->types()->select('title', 'slug')->get()->toArray();
            }

            $params['country'] = $country->toArray();
        } else if ($key == 'genre') {
            $option_id = Option::select('id')->where('name', 'genre')->first();
            $genre = Option_value::select('name', 'order')->where('option_id', $option_id->id)->where('order', $slug)->first();
            if ($slug == 1) {
                $movies = Movie::select('id', 'name', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
                    ->where('genre', $slug)
                    ->paginate(12);
                foreach ($movies as $video) {
                    $video->type = $video->types()->select('title', 'slug')->get()->toArray();
                }
            } else {
                $movies = Movie::select('id', 'name', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
                    ->where('genre', $slug)
                    ->paginate(12);
                foreach ($movies as $video) {
                    $video->type = $video->types()->select('title', 'slug')->get()->toArray();
                }
            }


            $params['genre'] = $genre->toArray();
        }

        //Lấy dữ liệu cho phần filter
        $filters['types'] = Type::select('id', 'title', 'slug')->where('table_name','movies')->get()->toArray();
        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == 'genre') {
                $filters['genre'] = $option->optionValue->toArray();
            } elseif ($option->name == 'quality') {
                $filters['quality'] = $option->optionValue->toArray();
            } elseif ($option->name == 'country') {
                $filters['country'] = $option->optionValue->toArray();
            }
        }
        return view('frontend.pages.movie.catalog_grid')->with([
            'movies' => $movies,
            'params' => $params,
            'filters' => $filters
        ]);
    }



    public function filter(Request $request)
    {
        $movies = Movie::select('movies.id', 'name','name_ja', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year');
        $params = [];
        if ($request->get('type') != null) {
            $movies = $movies->join('movie_type', function ($join) {
                $join->on('movies.id', '=', 'movie_type.movie_id');
            })->where('movie_type.type_id', '=', $request->get('type'));
            $params['type'] = Type::select('id', 'title')->where('id', $request->get('type'))->first()->toArray();
        }

        if ($request->get('genre') != null) {
            $movies = $movies->where('genre', $request->get('genre'));
            $option_id = Option::select('id')->where('name', 'genre')->first();
            $genre = Option_value::select('id', 'name', 'order')->where('option_id', $option_id->id)->where('order', $request->get('genre'))->first();
            $params['genre'] = $genre->toArray();
        }


        if ($request->get('country') != null) {
            $movies = $movies->where('country', $request->get('country'));
            $option_id = Option::select('id')->where('name', 'country')->first();
            $country = Option_value::select('id', 'name', 'order')->where('option_id', $option_id->id)->where('order', $request->get('country'))->first();
            $params['country'] = $country->toArray();
        }

        $movies = $movies->paginate(12);
        foreach ($movies as $video) {
            $video->type = $video->Types()->select('types.id', 'title', 'slug')->get()->toArray();
        }

        //Lấy dữ liệu cho filter
        $filters['types'] = Type::select('id', 'title', 'slug')->get()->toArray();
        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == 'genre') {
                $filters['genre'] = $option->optionValue->toArray();
            } elseif ($option->name == 'quality') {
                $filters['quality'] = $option->optionValue->toArray();
            } elseif ($option->name == 'country') {
                $filters['country'] = $option->optionValue->toArray();
            }
        }

        return view('frontend.pages.movie.catalog_grid')->with([
            'movies' => $movies,
            'params' => $params,
            'filters' => $filters
        ]);
    }
}
