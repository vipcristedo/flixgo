<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//===front end===


//=== Route Minh ===
Route::group([
    'namespace'=>'Frontend',
    'middleware' => 'language'
],function (){
    Route::get('/','HomeController@index')->name('frontend.home');
    Route::get('/home','HomeController@index')->name('frontend.home');
    Route::get('/filter','MovieController@filter')->name('frontend.filter');
    Route::get('/search','HomeController@search')->name('frontend.search');
    Route::get('/search/{slug}','HomeController@searchTag')->name('frontend.searchTag');
    Route::get('language/{language}','HomeController@language')->name('frontend.language');
    Route::get('/catalog/{key}/{slug}','MovieController@listMovie')->name('frontend.catalog');
    Route::get('/movie/{movie}/{sever?}/{video?}','MovieController@watchMovie')->name('frontend.movie');
    Route::group([
        'prefix'=>'manga'
    ],function (){
        Route::get('/','MangaController@index')->name('frontend.manga.home');
        Route::get('/catalog/{key}/{slug}','MangaController@listManga')->name('frontend.manga.catalog');
        Route::get('/filter','MangaController@filter')->name('frontend.manga.filter');
        Route::get('/search','MangaController@search')->name('frontend.manga.search');

        Route::get('/{manga}','MangaController@detail')->name('frontend.manga.detail');
        Route::get('/{manga}/{slug}','MangaController@chapter')->name('frontend.manga.chapter');
    });
});

//=== End Route Minh ===

//=== Route Sáng ===
Route::group([
    'middleware'=>'auth',
	'namespace'=>'Backend',
	'prefix'=>'admin',
	'as'=>'backend.'
],function (){
	Route::get('/home', 'DashboardController@home')->name('home');
	Route::get('/', 'DashboardController@home')->name('home');
	Route::group([
		'prefix'=>'admins',
		'as'=>'admin.'
	],function(){
		Route::get('/', 'AdminController@index')->name('index');
	    Route::get('create', 'AdminController@create')->name('create');
	    Route::post('store', 'AdminController@store')->name('store');
	    Route::get('{id}/edit', 'AdminController@edit')->name('edit');
	    Route::get('show/{id}', 'AdminController@show')->name('show');
	    Route::put('update/{id}', 'AdminController@update')->name('update');
	    Route::put('changePassword/{id}', 'AdminController@changePassword')->name('changePassword');
	    Route::post('changeStatus/{id}', 'AdminController@changeStatus')->name('changeStatus');
	    Route::post('updateRole/{id}', 'AdminController@updateRole')->name('updateRole');
	    Route::delete('delete/{id}', 'AdminController@destroy')->name('destroy');

	    Route::get('data', 'AdminController@getData')->name('data');
	    Route::get('roles/{id}', 'AdminController@getRoles')->name('roles');
	});

	Route::group([
		'prefix'=>'channel',
		'as'=>'channel.'
	],function(){
		Route::get('/', 'ChannelController@index')->name('index');
	    Route::get('create', 'ChannelController@create')->name('create');
	    Route::post('store', 'ChannelController@store')->name('store');
	    Route::get('{id}/edit', 'ChannelController@edit')->name('edit');
	    Route::get('show/{id}', 'ChannelController@show')->name('show');
	    Route::put('update/{id}', 'ChannelController@update')->name('update');
	    Route::post('changeStatus/{id}', 'ChannelController@changeStatus')->name('changeStatus');
	    Route::delete('delete/{id}', 'ChannelController@destroy')->name('destroy');

	    Route::get('data', 'ChannelController@getData')->name('data');
	});

	Route::group([
		'prefix'=>'manga_ad',
		'as'=>'manga_ad.',
	],function(){
		Route::get('/', 'Manga_adController@index')->name('index');
	    Route::get('create', 'Manga_adController@create')->name('create');
	    Route::post('store', 'Manga_adController@store')->name('store');
	    Route::get('{id}/edit/{objId?}', 'Manga_adController@edit')->name('edit');
	    Route::get('show/{id}', 'Manga_adController@show')->name('show');
	    Route::put('update/{id}', 'Manga_adController@update')->name('update');
	    Route::delete('delete/{id}', 'Manga_adController@destroy')->name('destroy');
	    Route::post('detach/{id}', 'Manga_adController@detach')->name('detach');

	    Route::get('data', 'Manga_adController@getData')->name('data');
	});

	Route::group([
		'prefix'=>'manga',
		'as'=>'manga.'
	],function(){
		Route::get('/', 'MangaController@index')->name('index');
	    Route::get('create', 'MangaController@create')->name('create');
	    Route::post('store', 'MangaController@store')->name('store');
	    Route::get('{id}/edit', 'MangaController@edit')->name('edit');
	    Route::get('show/{id}', 'MangaController@show')->name('show');
	    Route::put('update/{id}', 'MangaController@update')->name('update');
	    Route::delete('delete/{id}', 'MangaController@destroy')->name('destroy');

	    Route::get('data', 'MangaController@getData')->name('data');

	    Route::get('mangaChapters/{id}', 'MangaController@getMangaChapters')->name('mangaChapters');
	    Route::get('chapters/{id}', 'MangaController@getChapters')->name('chapters');
	    Route::post('updateChapter/{id}', 'MangaController@updateChapter')->name('updateChapter');

	    Route::get('mangaManga_ads/{id}', 'MangaController@getMangaManga_ads')->name('mangaManga_ads');
	    Route::get('manga_ads/{id}', 'MangaController@getManga_ads')->name('manga_ads');
	    Route::post('updateManga_ad/{id}', 'MangaController@updateManga_ad')->name('updateManga_ad');

	    Route::get('{id}/nominations','MangaController@nominations')->name('nominations');
	});

	Route::group([
		'prefix'=>'movie',
		'as'=>'movie.'
	],function(){
		Route::get('/', 'MovieController@index')->name('index');
	    Route::get('create', 'MovieController@create')->name('create');
	    Route::post('store', 'MovieController@store')->name('store');
	    Route::get('{id}/edit', 'MovieController@edit')->name('edit');
	    Route::get('show/{id}', 'MovieController@show')->name('show');
	    Route::put('update/{id}', 'MovieController@update')->name('update');
	    Route::get('tags', 'MovieController@tags')->name('tags');
	    Route::get('types', 'MovieController@types')->name('types');
	    Route::delete('delete/{id}', 'MovieController@destroy')->name('destroy');

	    Route::get('data', 'MovieController@getData')->name('data');
	    Route::post('{id}/add-video', 'MovieController@addVideo')->name('addVideo');
	    Route::get('moviePlaylists/{id}', 'MovieController@getMoviePlaylists')->name('moviePlaylists');
	    Route::get('playlists/{id}', 'MovieController@getPlaylists')->name('playlists');
	    Route::get('videos/{id}', 'MovieController@getVideos')->name('videos');
	    Route::post('updatePlaylist/{id}', 'MovieController@updatePlaylist')->name('updatePlaylist');
	    Route::post('updateVideo/{id}', 'MovieController@updateVideo')->name('updateVideo');
	    Route::get('{id}/nominations','MovieController@nominations')->name('nominations');
	});

	Route::group([
		'prefix'=>'option',
		'as'=>'option.'
	],function(){
		Route::get('/', 'OptionController@index')->name('index');
	    Route::get('create', 'OptionController@create')->name('create');
	    Route::post('store', 'OptionController@store')->name('store');
	    Route::get('{id}/edit', 'OptionController@edit')->name('edit');
	    Route::get('show/{id}', 'OptionController@show')->name('show');
	    Route::put('update/{id}', 'OptionController@update')->name('update');
	    Route::post('changeStatus/{id}', 'OptionController@changeStatus')->name('changeStatus');
	    Route::delete('delete/{id}', 'OptionController@destroy')->name('destroy');
	});

	Route::group([
		'prefix'=>'permission',
		'as'=>'permission.'
	],function(){
		Route::get('/', 'PermissionController@index')->name('index');
	    Route::get('create', 'PermissionController@create')->name('create');
	    Route::post('store', 'PermissionController@store')->name('store');
	    Route::get('{id}/edit', 'PermissionController@edit')->name('edit');
	    Route::get('show/{id}', 'PermissionController@show')->name('show');
	    Route::put('update/{id}', 'PermissionController@update')->name('update');
	    Route::post('changeStatus/{id}', 'PermissionController@changeStatus')->name('changeStatus');
	    Route::put('updateRole/{id}', 'PermissionController@updateRole')->name('updateRole');
	    Route::delete('delete/{id}', 'PermissionController@destroy')->name('destroy');

	    Route::get('data', 'PermissionController@getData')->name('data');
	});

	Route::group([
		'prefix'=>'playlist',
		'as'=>'playlist.'
	],function(){
		Route::get('/', 'PlaylistController@index')->name('index');
	    Route::get('create', 'PlaylistController@create')->name('create');
	    Route::post('store', 'PlaylistController@store')->name('store');
	    Route::get('{id}/edit/{movieId?}', 'PlaylistController@edit')->name('edit');
	    Route::get('show/{id}', 'PlaylistController@show')->name('show');
	    Route::put('update/{id}', 'PlaylistController@update')->name('update');
	    Route::post('changeStatus/{id}', 'PlaylistController@changeStatus')->name('changeStatus');
	    Route::delete('delete/{id}', 'PlaylistController@destroy')->name('destroy');
	    Route::post('detach/{id}', 'PlaylistController@detach')->name('detach');

	    Route::get('data', 'PlaylistController@getData')->name('data');
	    Route::post('{id}/add-video', 'PlaylistController@addVideo')->name('addVideo');
	    Route::get('playlistVideos/{id}', 'PlaylistController@getPlaylistVideos')->name('playlistVideos');
	    Route::get('videos/{id}', 'PlaylistController@getVideos')->name('videos');
	    Route::post('updateVideo/{id}', 'PlaylistController@updateVideo')->name('updateVideo');
	});

	Route::group([
		'prefix'=>'role',
		'as'=>'role.'
	],function(){
		Route::get('/', 'RoleController@index')->name('index');
	    Route::get('create', 'RoleController@create')->name('create');
	    Route::post('store', 'RoleController@store')->name('store');
	    Route::get('{id}/edit', 'RoleController@edit')->name('edit');
	    Route::get('show/{id}', 'RoleController@show')->name('show');
	    Route::put('update/{id}', 'RoleController@update')->name('update');
	    Route::post('changeStatus/{id}', 'RoleController@changeStatus')->name('changeStatus');
	    Route::post('updatePermission/{id}', 'RoleController@updatePermission')->name('updatePermission');
	    Route::delete('delete/{id}', 'RoleController@destroy')->name('destroy');

	    Route::get('data', 'RoleController@getData')->name('data');
	    Route::get('permissions/{id}', 'RoleController@getPermissions')->name('permissions');
	});

	Route::group([
		'prefix'=>'source',
		'as'=>'source.'
	],function(){
	    Route::get('create', 'SourceController@create')->name('create');
	    Route::post('store', 'SourceController@store')->name('store');
	    Route::get('{id}/edit', 'SourceController@edit')->name('edit');
	    Route::get('show/{id}', 'SourceController@show')->name('show');
	    Route::post('update/{id}', 'SourceController@update')->name('update');
	    Route::post('changeStatus/{id}', 'SourceController@changeStatus')->name('changeStatus');
	    Route::post('delete/{id}', 'SourceController@destroy')->name('destroy');
	});

	Route::group([
		'prefix'=>'tag',
		'as'=>'tag.'
	],function(){
		Route::get('/', 'TagController@index')->name('index');
	    Route::get('create', 'TagController@create')->name('create');
	    Route::post('store', 'TagController@store')->name('store');
	    Route::get('{id}/edit', 'TagController@edit')->name('edit');
	    Route::get('show/{id}', 'TagController@show')->name('show');
	    Route::put('update/{id}', 'TagController@update')->name('update');
	    Route::get('movies', 'TagController@movies')->name('movies');
	    Route::delete('delete/{id}', 'TagController@destroy')->name('destroy');
	});

	Route::group([
		'prefix'=>'type',
		'as'=>'type.'
	],function(){
		Route::get('/', 'TypeController@index')->name('index');
	    Route::get('create', 'TypeController@create')->name('create');
	    Route::post('store', 'TypeController@store')->name('store');
	    Route::get('{id}/edit', 'TypeController@edit')->name('edit');
	    Route::get('show/{id}', 'TypeController@show')->name('show');
	    Route::put('update/{id}', 'TypeController@update')->name('update');
	    Route::delete('delete/{id}', 'TypeController@destroy')->name('destroy');

	    Route::get('data', 'TypeController@getData')->name('data');
	});

	Route::group([
		'prefix'=>'video',
		'as'=>'video.'
	],function(){
		Route::get('/', 'VideoController@index')->name('index');
	    Route::get('create', 'VideoController@create')->name('create');
	    Route::post('store', 'VideoController@store')->name('store');
	    Route::get('{id}/edit/{playlistId?}', 'VideoController@edit')->name('edit');
	    Route::get('show/{id}', 'VideoController@show')->name('show');
	    Route::put('update/{id}', 'VideoController@update')->name('update');
	    Route::post('changeStatus/{id}', 'VideoController@changeStatus')->name('changeStatus');
	    Route::delete('delete/{id}', 'VideoController@destroy')->name('destroy');
	    Route::post('detach/{id}', 'VideoController@detach')->name('detach');

	    Route::get('data', 'VideoController@getData')->name('data');
	    Route::get('sources/{id}', 'VideoController@getSources')->name('sources');

	});
    Route::group([
        'prefix'=>'chapter',
        'as'=>'chapter.'
    ],function(){
        Route::get('/', 'ChapterController@index')->name('index');
        Route::get('create', 'ChapterController@create')->name('create');
        Route::post('store', 'ChapterController@store')->name('store');
        Route::get('{id}/edit/{mangaId?}', 'ChapterController@edit')->name('edit');
        Route::get('show/{id}', 'ChapterController@show')->name('show');
        Route::post('update/{id}', 'ChapterController@update')->name('update');
        Route::delete('delete/{id}', 'ChapterController@destroy')->name('destroy');
        Route::get('data', 'ChapterController@getData')->name('data');
        Route::get('get_picture/{id}', 'ChapterController@getPictures')->name('getPictures');
        Route::get('get_Chapter_ads/{id}', 'ChapterController@getChapterAds')->name('getChapterAds');
        Route::get('get_ads/{id}', 'ChapterController@getAds')->name('getAds');
        Route::post('{id}/add-Picture', 'ChapterController@addPicture')->name('addPicture');
        Route::delete('{id}/remove-Picture', 'ChapterController@removePicture')->name('removePicture');
        Route::get('{id}/edit-picture','ChapterController@editPicture')->name('editPicture');
        Route::post('update-Picture/{id}', 'ChapterController@updatePicture')->name('updatePicture');
        Route::post('updateManga_ad/{id}', 'ChapterController@updateManga_ad')->name('updateManga_ad');

	    Route::post('detach/{id}', 'ChapterController@detach')->name('detach');
    });
});

//=== End Route Sáng ===
//Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login.form');
Route::post('login', 'Auth\LoginController@login')->name('login.store');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
