<?php

namespace App\Http\Controllers\Frontend;

use App\Chapter;
use App\Http\Controllers\Controller;
use App\Manga_ads;
use Illuminate\Http\Request;
use App\Option;
use App\Option_value;
use App\Type;
use App\Source;
use App\Video;
use App\Channel;
use App\Playlist;
use App\Manga;

class MangaController extends Controller
{
    public function index()
    {
        //manga nominations
        $manga_nominations = Manga::select('id', 'name', 'description', 'author', 'card_cover', 'slug', 'rate')
            ->where('nominations', '1')
            ->inRandomOrder()
            ->take(8)
            ->get();

        foreach ($manga_nominations as $manga) {
            $manga->types = $manga->Types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }
        $manga_nominations = $manga_nominations->toArray();

        //===New manga===

        //New
        $news = Manga::select('id', 'name', 'description', 'age', 'card_cover', 'author', 'slug', 'rate')
            ->orderBy('release_year', 'desc')
            ->take(6)
            ->get();
        foreach ($news as $new) {
            $new->types = $new->Types()->select('title', 'slug')
                ->get()
                ->toArray();
        }
        $news = $news->toArray();


        //manga


        return view('frontend.pages.manga.home_manga')->with([
            'manga_nominations' => $manga_nominations,
            'news' => $news,

        ]);
    }


    public function detail($manga)
    {
        $manga = Manga::select('id', 'name', 'card_cover', 'author', 'slug', 'description', 'country', 'status', 'age', 'release_year', 'rate')->where('slug', $manga)->first();
        $manga->types = $manga->Types()->select('types.id', 'title', 'slug')->where('table_name', 'mangas')->get()->toArray();
        $manga->total_chap = $manga->chapters()->count();

        $manga->chaps = $manga->chapters()->select('chapters.id', 'name', 'slug', 'chap')->get()->toArray();

        $manga = $manga->toArray();

        return view('frontend.pages.manga.manga_detail')->with('manga', $manga);
    }

    public function chapter($manga_slug, $slug)
    {
        $chapter = [];

        $manga = Manga::select('id', 'name','slug')->where('slug', $manga_slug)->first()->toArray();
        $chapters = Chapter::select('id', 'manga_id', 'name', 'slug', 'chap')->where('manga_id', $manga['id']);

        $current = Chapter::select('id', 'manga_id', 'name', 'slug', 'chap')->where('slug', $slug)->first();
        if ($current['chap'] != 1 && $current['chap'] != $chapters->count()) {
            $chapter['pre'] = Chapter::select('id', 'manga_id', 'name', 'slug', 'chap')
                ->where('chap', $current->chap - 1)
                ->first()
                ->toArray();
            $chapter['next'] = Chapter::select('id', 'manga_id', 'name', 'slug', 'chap')
                ->where('chap', $current->chap + 1)
                ->first()
                ->toArray();
        } elseif ($current['chap'] == 1) {
            $chapter['next'] = Chapter::select('id', 'manga_id', 'name', 'slug', 'chap')
                ->where('chap', $current->chap + 1)
                ->first();
            if($chapter['next'])
                $chapter['next'] = $chapter['next']
                ->toArray();
        } else {
            $chapter['pre'] = Chapter::select('id', 'manga_id', 'name', 'slug', 'chap')
                ->where('chap', $current->chap - 1)
                ->first()
                ->toArray();
        }

        $chapters = $chapters->orderBy('chap', 'asc')->get()->toArray();
        $pictures = $current->pictures()->select('chapter_pictures.id', 'chapter_id', 'link')->orderBY('order', 'asc')->get()->toArray();

        $ads = Manga_ads::select('id','object_id','table_name','link','artical')
            ->where('object_id',$current->id)
            ->inRandomOrder()
            ->limit((int)env('ADS',count($pictures)))
            ->get()
            ->toArray();
        //Tính vị trí của ads
        if (count($ads)!=0)
            $kc = count($pictures)/(count($ads)+1);
        else $kc = 0;

        $du =  count($pictures)%(count($ads)+1);

        $chapter['current'] = $current->toArray();

        return view('frontend.pages.manga.manga_chapter')->with([
            'manga' => $manga,
            'chapter' => $chapter,
            'chapters' => $chapters,
            'pictures' => $pictures,
            'ads' => $ads,
            'kc'=>(int)$kc,
            'du'=>$du,
        ]);
    }

    public function listManga($key,$slug)
    {
        if ($key == 'type') {
            $type = Type::select('id', 'title', 'slug')->where('slug', $slug)->first();
            $mangas = $type->mangas()->select('mangas.id', 'name', 'age', 'description', 'card_cover', 'author', 'slug', 'rate',  'country', 'release_year')
                ->paginate(12);
            foreach ($mangas as $manga) {
                $manga->type = $manga->types()->select('title', 'slug')->get()->toArray();
            }

            $params['type'] = $type->toArray();


        }
        else if ($key == 'country') {
            $option_id = Option::select('id')->where('name', 'country')->first();
            $country = Option_value::select('name', 'order')->where('option_id', $option_id->id)->where('order', $slug)->first();
            $mangas = Manga::select('id', 'name', 'age', 'card_cover', 'author', 'slug', 'rate', 'author', 'country','release_year')
                ->where('country', $slug)
                ->paginate(12);
            foreach ($mangas as $manga) {
                $manga->type = $manga->types()->select('title', 'slug')->get()->toArray();
            }

            $params['country'] = $country->toArray();
        }

        $filters['types'] = Type::select('id', 'title', 'slug')->where('table_name','mangas')->get()->toArray();
        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == 'country') {
                $filters['country'] = $option->optionValue->toArray();
            }
        }

        return view('frontend.pages.manga.manga_list')->with([
            'mangas' => $mangas,
            'params' => $params,
            'filters' => $filters
        ]);
    }

    public function filter(Request $request)
    {
        $mangas = Manga::select('mangas.id', 'name', 'age', 'card_cover', 'author', 'slug', 'rate','country','release_year');
        $params = [];
        if ($request->get('type') != null) {
            $mangas = $mangas->join('manga_type', function ($join) {
                $join->on('mangas.id', '=', 'manga_type.manga_id');
            })->where('manga_type.type_id', '=', $request->get('type'));
            $params['type'] = Type::select('id', 'title')->where('id', $request->get('type'))->first()->toArray();
        }


        if ($request->get('country') != null) {
            $mangas = $mangas->where('country', $request->get('country'));
            $option_id = Option::select('id')->where('name', 'country')->first();
            $country = Option_value::select('id', 'name', 'order')->where('option_id', $option_id->id)->where('order', $request->get('country'))->first();
            $params['country'] = $country->toArray();
        }

        $mangas = $mangas->paginate(12);
        foreach ($mangas as $manga) {
            $manga->type = $manga->Types()->select('types.id', 'title', 'slug')->get()->toArray();
        }

        $filters['types'] = Type::select('id', 'title', 'slug')->where('table_name','mangas')->get()->toArray();
        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == 'country') {
                $filters['country'] = $option->optionValue->toArray();
            }
        }
        return view('frontend.pages.manga.manga_list')->with([
            'mangas' => $mangas,
            'params' => $params,
            'filters' => $filters
        ]);
    }
    public function search(Request $request)
    {
        $mangas = Manga::select('id', 'name', 'age', 'card_cover', 'author', 'slug', 'rate','country','release_year')
            ->where('name', 'like', '%' . $request->get('search') . '%')
            ->paginate(12);
        foreach ($mangas as $manga) {
            $manga->type = $manga->types()->select('title', 'slug')->get()->toArray();
        }
        return view('frontend.pages.manga.search')->with([
            'mangas' => $mangas,
            'key' => $request->get('search')
        ]);
    }
}
