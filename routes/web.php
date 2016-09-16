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



// API CALLS

Route::get('/apihome', 'ApiController@json');
Route::get('/app-page-2', 'ApiController@page2');
Route::post('/app-post', 'ApiController@store');

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

use App\Helpers\Sendmail;
use App\User;

Route::get('/email', function(){


// Sendmail::verify('danielvt@gmail.com', '32', 'Tester2', 'danystatic@hotmail.com');
// dd();
		// public static function verify($email, $confirmation_code, $username, $sponsoremail){
	 $to = 'danielvt@gmail.com'; 
	 $subject = 'Test email using PHP';
	  $message = 'This is a test email message'; 
		 
	$headers = "From: " . "do-not-reply@clubdigital.tk" . "\r\n";
	$headers .= "Reply-To: ". "do-not-reply@clubdigital.tk" . "\r\n";
	$headers .= "CC: do-not-reply@clubdigital.tk\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	  // $headers = 'From: do-not-reply@clubdigital.tk' . "\r\n" . 'Reply-To: do-not-reply@clubdigital.tk' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); 
	  // $headers = 'From: admin@clubdigital.tk' . "\r\n" . 'Reply-To: admin@clubdigital.tk' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); 
	  // mail($to, $subject, $message, $headers, 'admin@clubdigital.tk'); 
	  // mail($to, $subject, $message, $headers, 'do-not-reply@clubdigital.tk'); 
	  mail($to, $subject, $message, $headers, 'do-not-reply@clubdigital.tk'); 

dd();
	// return view('emails.image');
		$user = User::where('id','=',1)->first();

	 // dd($user);
        // Mail::send('emails.image', ['user' => $user->email, 'user_id' => $user->id], function ($m) use ($user) {
        Mail::send('emails.welcome', ['user' => $user], function ($m) use ($user) {

            $m->to('danystatic@hotmail.com')->subject('Apoya a Maná');
            $m->to('danielvt@gmail.com')->subject('iframes');
        });

     dd("email Sent");


});
Route::get('/home', 'HomeController@index');

Route::get('/registro', 'MyregistroController@index');
Route::post('/registro', 'MyregistroController@registro');


/////////////////////////////////////////////////
Route::get('/configuracion', ['as' => 'configuracion', 'uses' => 'ViewsController@configuracion']);
Route::get('/comisiones', ['as' => 'comisiones', 'uses' => 'ViewsController@estadofinanciero']);
Route::get('/contabilidad',  ['as' => 'contabilidad', 'uses' => 'ViewsController@contabilidad']);
Route::get('/registro',  ['as' => 'registro', 'uses' => 'RegisterController@registro']);


Route::post('asignparent', array('before' => 'auth', 'uses' => 'PostsController@assignParent'));




//System Routes
Route::get('btcusd', 'SystemController@btcusd'); //  ES CRON JOB puede cambiar
Route::post('callback', 'SystemController@callback');


Route::get('generateNewAddressBlockcypher', 'SystemController@generateNewAddressBlockcypher');


// POSTS Routes
// Route::post('entrar', 'PostsController@entrar');
Route::post('SetUser', array('before' => 'auth', 'uses' => 'PostsController@setUser'));
Route::post('sendBitcoins',array('before'=> 'auth', 'uses' => 'PostsController@sendBitcoins'));
Route::post('deleteUser/{id?}', array('before' => 'auth', 'uses' => 'PostsController@deleteUser'));
// WTF Que hacemos con esto? serrepresentante
Route::post('serepresenante', array('before' => 'auth', 'uses' => 'PostsController@serepresenante'));

Route::get('privacidad', function(){
	return view('lato.privacypolicy');
});
Route::get('terminos', function(){
	return view('lato.terms');
});
Route::get('soporte', function(){
	return view('lato.support');
});