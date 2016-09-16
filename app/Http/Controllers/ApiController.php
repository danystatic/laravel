<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Helpers\Viewhelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApiController extends Controller
{
    //


   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $array['countdownline']=Viewhelper::countdownline();
        $array['getdomain']=Viewhelper::getdomain();

        //$dtreegoodview = Viewhelper::dtreegoodview(Auth::id());
        $array['dtreegoodview'] = Viewhelper::dtreegoodview(Auth::id());
        $array['lazyusers'] = 0;//Viewhelper::dtreegoodview(Auth::id());
//         return response()->json([
//     'name' => 'Abigail',
//     'state' => 'CA'
// ]);  
        $var['var'] = 'dany';
        $var['var2'] = 'dany';

        return response()
            ->view('apiindex', $array , 200)
            ->header('Content-Type', 'application/json');


        $view = View::make('testtree2')->with('array',$array)->with('lazyusers','usuarios sin invitaciones')->with('dtreegoodview',$dtreegoodview);
        dd($view);
        // $view = json_encode($view);
        // $view = 1;
        return response()->view('welcome', array('view' => $view), 200)->header('Content-Type', 'application/json');
        $array['countdownline']=Viewhelper::countdownline();
        $array['getdomain']=Viewhelper::getdomain();
        return view('home')->with('array', $array);
    }


   
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function page2()
    {


        
        $array['countdownline']=Viewhelper::countdownline();
        $array['getdomain']=Viewhelper::getdomain();

        //$dtreegoodview = Viewhelper::dtreegoodview(Auth::id());
        $array['dtreegoodview'] = Viewhelper::dtreegoodview(Auth::id());
        $array['lazyusers'] = 0;//Viewhelper::dtreegoodview(Auth::id());
//         return response()->json([
//     'name' => 'Abigail',
//     'state' => 'CA'
// ]);  
        $var['var'] = 'dany';
        $var['var2'] = 'dany';

        return response()
            ->view('apiindex', $array , 200)
            ->header('Content-Type', 'application/json');


        $view = View::make('testtree2')->with('array',$array)->with('lazyusers','usuarios sin invitaciones')->with('dtreegoodview',$dtreegoodview);
        dd($view);
        // $view = json_encode($view);
        // $view = 1;
        return response()->view('welcome', array('view' => $view), 200)->header('Content-Type', 'application/json');
        $array['countdownline']=Viewhelper::countdownline();
        $array['getdomain']=Viewhelper::getdomain();
        return view('home')->with('array', $array);
    }


   
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {

    	// $input = $request->all();

    	// Feedback::create($input);

    	//OR

    	// $feedback = new Feedback;
    	// $feedback->json 	=$request->json;
    	// $feedback->key 		=$request->'video-1';
    	// $feedback->value 	=$request->value;
    	// $feedback->save();
    	return response()->json("success");

    }

   
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    	$input = $request->all();

    	Feedback::create($input);

    	//OR

    	// $feedback = new Feedback;
    	// $feedback->json 	=$request->json;
    	// $feedback->key 		=$request->'video-1';
    	// $feedback->value 	=$request->value;
    	// $feedback->save();
    	return response()->json("success");

    }


}
