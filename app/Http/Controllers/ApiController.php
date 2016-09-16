<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Helpers\Viewhelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Feedback;

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


          return response()->json(['txt' => 'I was trying to insert a view, but it has Auth and user Variables. this is pure text']);
    	// return response()->json(txt : 'I was trying to insert a view, but it has Auth and user Variables. this is pure text');


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
            ->view('welcome', $array , 200)
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
    public function json(Request $request)
    {

    	// $input = $request->all();

    	// Feedback::create($input);

    	//OR test git push default

    	// $feedback = new Feedback;
    	// $feedback->json 	=$request->json;
    	// $feedback->key 		=$request->key;
    	// $feedback->value 	=$request->value;
    	// $feedback->save();
    	return response()->json(['res'=>"success"]);

    }

   
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// curl --data "mykey=shittykey&json='jsonjson'&value='singletextlieve'" http://bitcoinmerida.dev/app-post
    	// curl --data "mykey=shittykey&json='ptu'&value='singletextlieve'" http://bitcoinmerida.dev/app-post
    	// curl --data "mykey=shittykey&json='{"available":true,"platform":"Android","version":"6.0.1","uuid":"eacdde4d56f9841","cordova":"5.1.1","model":"Nexus 5","manufacturer":"LGE","isVirtual":false,"serial":"06d0db6af0e96f19"}'&value='singletextlieve'" http://bitcoinmerida.dev/app-post
    	//{"available":true,"platform":"Android","version":"6.0.1","uuid":"eacdde4d56f9841","cordova":"5.1.1","model":"Nexus 5","manufacturer":"LGE","isVirtual":false,"serial":"06d0db6af0e96f19"} 

    	//$input = $request->all();
    	
    	// return "hello";	
    	//OR
    	$json = json_decode($request->json);

    	// dd($json);
    	$feedback = new Feedback;
    	$feedback->uuid 	=$json->uuid;
    	$feedback->json 	=$request->json;
    	$feedback->mykey 	=$request->mykey;
    	$feedback->value 	=$request->value;
    	$feedback->save();
    	return response()->json(['res'=>"success"]);

    }


}
