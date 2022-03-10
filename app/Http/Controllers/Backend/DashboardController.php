<?php

namespace App\Http\Controllers\backend;

use App\Admin;
use App\Movie;
use App\Playlist;
use App\Tag;
use App\Type;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function home(){
    	$totalMovie = Movie::all()->count();
    	$topMovies = Movie::orderByRaw('rate DESc')->take(6)->get();

    	$totalPlaylist = Playlist::all()->count();
    	$totalType = Type::all()->count();
    	$totalTag = Tag::all()->count();

    	$admins = Admin::orderByRaw('updated_at DESC')->take(6)->get();
    	return view('backend.dashboard')->with([
    		'totalMovie'=>$totalMovie,
    		'totalPlaylist'=>$totalPlaylist,
    		'totalType'=>$totalType,
    		'totalTag'=>$totalTag,
    		'topMovies'=>$topMovies->toArray(),
    		'admins'=>$admins,
    	]);
    }
}
