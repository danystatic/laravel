<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Viewhelper;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $array['countdownline']=Viewhelper::countdownline();
        $array['getdomain']=Viewhelper::getdomain();

    $dtreegoodview = Viewhelper::dtreegoodview(Auth::id());

    return view('testtree2')->with('array',$array)->with('lazyusers','usuarios sin invitaciones')->with('dtreegoodview',$dtreegoodview);

        $array['countdownline']=Viewhelper::countdownline();
        $array['getdomain']=Viewhelper::getdomain();
        return view('home')->with('array', $array);
    }
}
