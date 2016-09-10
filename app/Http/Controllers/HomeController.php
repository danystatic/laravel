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
        //Log::notice('ViewsController@__construct');

        //Log::notice('ViewsController@__construct auth middleware');
        $this->middleware('auth', ['except' => array('index', 'proyecto', 'contact')]);

        //Log::notice('ViewsController@__construct checksponsor middleware');
        $this->middleware('checksponsor', ['except' => array('index', 'proyecto', 'contact')]);

        //Log::notice('after middleware');
        //Log::notice('Visitor &&|| Member??');

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


   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebook()
    {
        //Log::notice('ViewsController@facebook');
        //Log::notice('ViewsController@facebook');

        // dd("n2");
        Session::flash('logout', 'Log out!');
        return view('lato.facebook');//->with('message', 'logged in');//"Welcome Back " . Auth::user()->id;
//      
    }
      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function google()
    {
        //Log::notice('ViewsController@google');
        //Log::info("@ google");

        // dd("n2");
       Session::flash('logout', 'Log out!');
        return view('lato.google');//->with('message', 'logged in');//"Welcome Back " . Auth::user()->id;
//      
    }
      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        //Log::notice('ViewsController@home');
        //Log::info("ViewsController@home");

        // dd("n2");
       Session::flash('logout', 'Log out!');
        // return view('lato.shieldlean');//->with('message', 'logged in');//"Welcome Back " . Auth::user()->id;
        return view('cleanblog.members.welcome');//->with('message', 'logged in');//"Welcome Back " . Auth::user()->id;
//      
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function control(Request $request)
    {
        //Log::info("ViewsController@control");
        

        //Log::notice('ViewsController@control');
        //Log::warning("Always checking for parentid if is admin.");
        //Log::warning("Some hard code. Checking. reason: user_id = 0 is giving issues and trouble making downline css");

        if($request->user()->parentid == 0)
        {
            $sponsor = new User;
            $sponsor->email = "admin@clubdigital.tk";
            $sponsor = User::where('id','=',1)->first();
        }else
        {

            $sponsor = User::where('id','=',$request->user()->parentid)->first();
        }
        
            // dd($request->user()->parentid);
        // dd($sponsor);
        // dd($request->user()->parentid);

        //Log::info('crear todas las vistas de la pagina de Control es un chingo');

        $news = News::orderBy('id', 'desc')->take(3)->get();


        //Log::info('performance');
        $downline = Viewhelper::performance($request->user()->id);

        // dd($downline);

        //Log::info('wallet');
        $wallet = Viewhelper::wallet();
        //Log::info('lottable');
        $lottotable = Viewhelper::lottotable();
        //Log::info('dtreegoodview');
        $dtreegoodview = Viewhelper::dtreegoodview(Auth::user()->id);
        //Log::info('lazyusers');
        $lazyusers = Viewhelper::lazyusers(Auth::user()->id);
        //Log::info('parentlessusers');
        $parentlessusers = User::where('parentid','=',NULL)->get();

        // dd($lazyusers);
        // dd($dtreegoodview);
        // dd($parentlessusers);
        // dd($wallet);
        //Log::notice('Flashing log out user state ???');
        //Log::notice('returning views.....');
        //Log::notice('returning views.....');

        Session::flash('logout', 'Log out!');
        return view('cleanblog.members.control')
            ->with('news', $news)
            ->with('downline', $downline)
            ->with('lottotable', $lottotable)
            ->with('dtreegoodview', $dtreegoodview)
            ->with('parentlessusers', $parentlessusers)
            ->with('lazyusers', $lazyusers)
            ->with('parentlessusers', $parentlessusers)
            ->with('wallet', $wallet)
            ->with('sponsor', $sponsor);//"Welcome Back " . Auth::user()->id;
//      
    }

    public function presentacion()
    {

        //Log::notice('ViewsController@presentacion');


        return view('cleanblog.members.presentation');
    }


    public function proyecto()
    {
        //Log::notice('ViewsController@proyecto');

        return view('cleanblog.presentacion');
    }



    public function contact()
    {
        //Log::notice('ViewsController@contact');

        return view('cleanblog.contact');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function multinivel()
    // {
    //     //Log::notice('ViewsController@multinivel');


    //     $playnumber = playnumber();

    //     $domain = 'Comparte este link con tus amigos <br>Seamos mas<br> Construyamos algo juntos...! ';
    //     $socios = count( Lotto::where('playnumber','=',$playnumber)->groupBy('user_id')->get());
    //     $socios2 = User::where('id','>',0)->count();
    //     // dd($socios);
    //     $newsocios =  "<span style='border-radius:3px;padding:1px;background-color:#BBFF99;'>$socios</span> de 
    //     <span style='border-radius:3px;padding:1px;background-color:#D3D3D3;'>$socios2</span>";
    //     // $activeusers = $socios;
    //     // $inactiveusers = $socios2 - $socios;

    //     // echo "<pre>";
    //     // dd($inactiveusers);

    //     if(Auth::user()->id == 1)
    //     {
    //         $parentlessusers = User::where('parentid','=',NULL)->get();

    //     }else{
    //         // $parentlessusers = User::where('parentid','=',NULL)->where('sponsorid','=',Auth::user()->id)->get();
    //         $parentlessusers = User::where('parentid','=',NULL)->get();
            
    //     }

    //     //Option 4
    //     // $mlmtable = Myhelpers::displaytreeevolution('users',1);
    //     //Option 5
    //     // $mlmtable = Myhelpers::displaydtree2ul('users',1);
    //     // $mlmtable = Myhelpers::displaydtreedefault(1,1);
    //     //Log::warning('Viewhelper::dtreegoodview ALL USERS !!');
    //     $mlmtable = Viewhelper::dtreegoodview('users',1);

    //     // dd($mlmtable);
    //     //$mlmtable = Myhelpers::fastdisplaybinarytree(1,1);
    //     // $mlmtable = Myhelpers::fastdisplaytree(1,0);
    //     // $mlmtable = Myhelpers::displaytree('users',1);
    //     // $mlmtable = Myhelpers::displaymlmtable3();
    //     //Log::warning('Viewhelper::getlazyusers NEED to test!!');
    //     $lazyusers = Viewhelper::getlazyusers();
    //     // $mlmtable = $mlmtable;
    //     $view = view('partials.mlmtree6')
    //         ->with('mlmtable', $mlmtable)
    //         ->with('lazyusers', $lazyusers)
    //         ->with('parentlessusers', $parentlessusers);
    //         // ->with('crowdfunding', $crowdfunding)
    //         // ->with('crowdfundingUSD', $crowdfundingUSD)
    //         // ->with('activeusers', $activeusers)
    //         // ->with('inactiveusers', $inactiveusers)




    //     //Log::info('view jusftifiedMenu');
    //     $menu = view('justifiedMenu');

    //     //Log::info('Viewhelper::retrieveUserPaymentAddress');
    //     $paymentAddress = Viewhelper::retrieveUserPaymentAddress();
    //     //Log::info('Viewhelper next operations....');

    //     $holdingsview = Viewhelper::userholdings(Auth::user()->id);

        
    //     $btcusd = Btcusd::where('id','>',0)->orderBy('id','desc')->first();

    //     // dd($btcusd->pesosprice);
    //     $holdingspesos =  number_format(($holdingsview / $btcusd->pesosprice) * 100,2);

    //     // dd($holdingspesos);


    //     $lotto = Lotto::where('user_id','=',Auth::user()->id)->where('playnumber','=',$playnumber)->first();

    //     // dd( number_format($btcusd->btcprice,8));
    //     // dd($holdingsview);
    //     //      dd($lotto->lotto);
    //     // if(!isset($lotto->lotto)){
    //     //  echo "not is set";
    //     // }else{
    //     //  echo "is set";
    //     //  echo gettype($lotto->lotto);
    //     // }
    //     // exit();

    //         // return $mlmtable;
    //         // $menu = view('lean.justifiedMenu');
    //         // return view('test');
    //     //Log::warning('view tickets ??');
    //         return view('tickets')
    //         ->with('paymentAddress', $paymentAddress)
    //         ->with('holdingsview', $holdingsview)
    //         ->with('holdingspesos', $holdingspesos)
    //         ->with('btcusd', $btcusd)
    //         ->with('view', $view)
    //         ->with('lotto', $lotto);
    //         // ->with('view2', $view2);


    //         // return view('layout')
    //         //     ->with('menu', $menu);

    // }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuracion()
    {

        //Log::notice('ViewsController@configuracion');
        //
        //Log::info("@ configuracion");        
        // return "configuracion;";

        return view('cleanblog.members.configuracion');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function estadofinanciero()
    {


        //Log::notice('ViewsController@estadofinanciero');
        //
        //Log::info("@ comisiones");        
        


        $btcusd = Btcusd::where('id','>',0)->orderBy('id','desc')->first();

        $crowdIn = DB::table('blockchains')->where('reason','=', 'crowdfunding')->sum('btcvalue');

        
        $crowdInPesos =  number_format(($crowdIn / $btcusd->btcprice) * 100,2);


        $blockchains = Blockchain::where('user_id','=',Auth::user()->id)->get();

        return view('lato.estadofinanciero')
                    ->with('acumulado', $crowdIn)
                    ->with('acumuladopesos', $crowdInPesos)
                    ->with('blockchains', $blockchains);



        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contabilidad()
    {
        //Log::notice('ViewsController@contabilidad');
        //
        //Log::info("@ contabilidad");        
        return "contabilidad;";
        return "<h1>Trabajando....</h1>";

        // User holdings = total deposited - purchased tickets
        $totaldeposit = DB::table('blockchains')->where('reason','=','deposit')->sum('btcvalue');

        $totalplays = DB::table('blockchains')->where('reason','=', 'play')->sum('btcvalue');

        $totalcrowdfunding = DB::table('blockchains')->where('reason', '=', 'crowdfunding')->sum('btcvalue');
        
        $commissions = DB::table('blockchains')->where('reason', '=', 'commission')->sum('btcvalue');

        $retiros = DB::table('blockchains')->where('reason', '=', 'retiro')->sum('btcvalue');

        // $var = $totalplays+$totalcrowdfunding+$commissions;



        $userholdingsNETO = Viewhelper::userholdingsNETO();
        // dd($userholdingsNETO);
        
        $jackpot = DB::table('blockchains')->where('reason', '=', 'jackpot')->sum('btcvalue');
        // dd($holdings + (-$retiros));
        // dd($jackpot);
        $btcUsuarios =  $userholdingsNETO + $retiros + $jackpot ;

        // dd($retiros );
        // dd($btcUsuarios);
        // dd($totaldeposit);

        if($btcUsuarios == $totaldeposit){
            $check = "SI";
        }else{
            $check = "NO";
        }

        $var = 0;
        $crowdfunding = 0;
        $totalticket = 0;
        // dd(($holdings+(-($retiros))));
        // dd($var);
        // dd($check);
        // dd($crowdfunding - $jackpot + $commissions);
        // dd($commissions);
        // dd($commissions);
        // dd($tickets);
        // dd($totalplays);
        // dd($totaldeposit);

        $retiros = DB::table('blockchains')->where('reason', '=', 'retiro')->sum('btcvalue');

        $tusretiros = DB::table('blockchains')->where('reason', '=', 'retiro')->where('user_id','=',Auth::user()->id)->get();

        // 
        // dd(count($tusretiros));
        $view = view('simple.contabilidad')
            ->with('var', $var)
            ->with('check', $check)
            ->with('retiros', $retiros)
            ->with('btcUsuarios', $btcUsuarios)
            ->with('jackpot', $jackpot)
            ->with('crowdfunding', $crowdfunding)
            ->with('commissions', $commissions)
            ->with('tusretiros', $tusretiros)
            ->with('totalticket', $totalticket)
            ->with('totaldeposit', $totaldeposit);


        $menu = view('boot.justifiedMenu');
        
        return view('united.layout')
        ->with('menu', $menu)
        ->with('view', $view);

    }


    public function getSponsored()
    {
        //Log::notice('ViewsController@getSponsored');
        return view('loto.sponsorship');
    }
  
}

