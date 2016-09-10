<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Helpers\Viewhelper;
////use Log;
use Auth;
use Illuminate\Contracts\Auth\Guard;

class Checksponsor
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        //Log::info("Checksponsor");

        //Log::error('iERROR');
        if(!isset($request->user()->id)) {

            //Log::error('imposible to get here with auth middleware??');
            //Log::error('user without id == VISITOR == authmiddleware???? Re-Checking??');
            return redirect('/')->with('message', 'Entra con tu usario, ahorita el email no funciona');

        }




        //Log::notice("After Authenticated user test");
        //Log::notice("-check if sponsor");

        // //Log::info($request->user()->id);

        if($request->user()->parentid == NULL)
        {

            //Log::info('MEMBER');
            //Log::info('user_id: ' . Auth::user()->id);

            $sponsors = User::where('id','>',0)->orderBy('id', 'desc')->get();

            return view('lato.sponsorship')->with('users', $sponsors);
        }

        //Log::info("User with sponsor");
        return $next($request);
    }
}
