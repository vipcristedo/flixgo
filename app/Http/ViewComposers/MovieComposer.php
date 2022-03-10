<?php
namespace App\Http\ViewComposers;

use App\Option;
use App\Type;
use Illuminate\View\View;

class MovieComposer
{
    public $main_menu = [];

    public function __construct()
    {
        $this->main_menu['types_movie'] = Type::select('title', 'slug')->where('table_name','movies')->get()->toArray();
        $this->main_menu['types_manga'] = Type::select('title', 'slug')->where('table_name','mangas')->get()->toArray();
        $country = Option::where('name', 'country')->first();
        $genre = Option::where('name', 'genre')->first();
        $this->main_menu['country'] = $country->optionValue->toArray();
        $this->main_menu['genre'] = $genre->optionValue->toArray();
    }

    public function compose(View $view)
    {
//        dd($this->main_menu);
        $view->with('main_menu', $this->main_menu);

    }
}
