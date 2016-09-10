<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// use App\Helpers\Sendmail;
use App\Helpers\Viewhelper;
// use App\Mail\Welcome;
Route::get('/', function () {
		// Sendmail::verify("danystatic@hotmail.com", '888', 'danystatic', 'danystatic@hotmail.com');
	  // Mail::to('danystatic@hotmail.com')->send(new Welcome);


    return view('welcome');
});

# For Email Tree View
    // $array['countdownline']=Viewhelper::countdownline();
    //     $array['getdomain']=Viewhelper::getdomain();

    // $dtreegoodview = Viewhelper::dtreegoodview(1);

    // return view('testtree2mail')->with('array',$array)->with('lazyusers','usuarios sin invitaciones')->with('dtreegoodview',$dtreegoodview);

# For Tree View

    // $array['countdownline']=Viewhelper::countdownline();
    //     $array['getdomain']=Viewhelper::getdomain();

    // $dtreegoodview = Viewhelper::dtreegoodview(1);

    // return view('testtree2')->with('array',$array)->with('lazyusers','usuarios sin invitaciones')->with('dtreegoodview',$dtreegoodview);



Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/registro', 'MyregistroController@index');
Route::post('/registro', 'MyregistroController@registro');