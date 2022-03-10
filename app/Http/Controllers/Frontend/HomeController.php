<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Movie;
use App\Option;
use App\Option_value;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Tag;

class HomeController extends Controller
{
    public function index()
    {
        //Movie nominations
        $video_nominations = Movie::select('id', 'name','name_ja', 'description', 'card_cover', 'runtime', 'slug', 'rate')
            ->where('nominations', '1')
            ->inRandomOrder()
            ->take(12)
            ->get();

        foreach ($video_nominations as $video) {
            $video->types = $video->Types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }
        $video_nominations = $video_nominations->toArray();

        //===New Movie===

        //New
        $news = Movie::select('id', 'name','name_ja', 'description', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'quality')
            ->orderBy('release_year', 'desc')
            ->take(6)
            ->get();
        $quality_id = Option::select('id')->where('name', 'quality')->first()->id;
        foreach ($news as $new) {
            $new->types = $new->Types()->select('title', 'slug')
                ->get()
                ->toArray();
            $new->quality = Option_value::select('name')->where('option_id', $quality_id)
                ->where('order', $new->quality)
                ->first()
                ->name;
        }
        $news = $news->toArray();


        //Movie
        $movies = Movie::select('id', 'name','name_ja', 'description', 'card_cover', 'runtime', 'slug', 'rate')
            ->where('genre', '1')
            ->orderBy('release_year', 'desc')
            ->take(12)
            ->get();
        foreach ($movies as $video) {
            $video->types = $video->Types()->select('title', 'slug')->get()->toArray();
        }
        $movies = $movies->toArray();

        //Tv series
        $tv_sereis = Movie::select('id', 'name','name_ja', 'description', 'card_cover', 'runtime', 'slug', 'rate')
            ->where('genre', '2')
            ->orderBy('release_year', 'desc')
            ->take(12)
            ->get();
        foreach ($tv_sereis as $video) {
            $video->types = $video->Types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }
        $tv_sereis = $tv_sereis->toArray();

//        Cartoons
        $type = Type::where('title', 'Cartoons')->first();
        if ($type) {
            $cartoons = $type->movies()
                ->orderBy('release_year', 'desc')
                ->take(12)
                ->get();
            foreach ($cartoons as $video) {
                $video->types = $video->Types()
                    ->select('title', 'slug')
                    ->get()
                    ->toArray();
            }
            $cartoons = $cartoons->toArray();
        } else {
            $cartoons = [];
        }


        return view('frontend.pages.movie.home')->with([
            'video_nominations' => $video_nominations,
            'news' => $news,
            'movies' => $movies,
            'tv_sereis' => $tv_sereis,
            'cartoons' => $cartoons
        ]);
    }

    public function language($language)
    {
        if ($language) {
            Session::put('language', $language);
        }
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $movies = Movie::select('id','name','name_ja', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
            ->where('name', 'like', '%' . $request->get('search') . '%')
            ->paginate(12);
        foreach ($movies as $video) {
            $video->type = $video->types()->select('title', 'slug')->get()->toArray();
        }
        return view('frontend.pages.movie.search')->with([
            'movies' => $movies,
            'key' => $request->get('search')
        ]);
    }
    public function searchTag($slug)
    {
        $tag=Tag::select('id','name','slug')->where('slug',$slug)->first();

        $movies=$tag->movies()->select('movies.id','name','name_ja', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')->paginate(12);
        foreach ($movies as $video) {
            $video->type = $video->types()->select('title', 'slug')->get()->toArray();
        }
        $tag=$tag->toArray();
        return view('frontend.pages.movie.searchTag')->with([
            'movies' => $movies,
            'tag'=>$tag
        ]);
    }
}
