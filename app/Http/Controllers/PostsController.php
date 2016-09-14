<?php

namespace App\Http\Controllers;

////use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Lotto;
use App;
use App\Blockchain;
use App\Helpers\Viewhelper;



class PostsController extends Controller
{
    
    protected $request;

    public function __constructor(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }






    // Delete only admin or parent
    public function deleteUser($id){

        $user = User::where('id','=',$id)->delete();

        return redirect('multinivel')->with('msg', 'El usuario ha sido eliminado.');
    }

    
    /**
     * Display a listing of the resource.
     * GET /posts
     *
     * @return Response
     */
    public function setUser()
    {

        // dd(Input::get('username'));
        //Log::info("post::SetUser");
        $user = User::where('id','=', Auth::user()->id)->first();
        $user->email = Input::get('email');
        $user->username = Input::get('username');
        $user->luckynumber = Input::get('luckynumber');
        $user->btcaddress = Input::get('btcaddress');
        $user->passwordstring = Input::get('password');
        $user->password = Hash::make(Input::get('password'));
        $user->cellphone = Input::get('cellphone');
        $user->save();

        $playnumber = 2;

        $lotto = Lotto::where('user_id','=', Auth::user()->id)->where('playnumber','=',$playnumber)->first();
        if(!$lotto){
            $lotto = new Lotto;
        }
        $lotto->playnumber = $playnumber;
        $lotto->lotto = Input::get('luckynumber');
        $lotto->save();


        Session::flash('msg', 'Informacion actualizada.');
        return redirect()->route('configuracion');
    }


    /**
     * Display the specified resource.
     * GET /posts/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function assignParent()
    {
        //

        //Log::info('post::asignparent');
        $binaryplan = User::where('parent','=',Input::get('parentname'))->get();
        
        $parent = User::where('username','=',Input::get('parentname'))->first();

        $user = User::where('id','=',Input::get('userid'))->first();

        # Assign parent with Both Children Full
        if( count($binaryplan) > $parent->limitchildren ){
        
            $user->sponsor = $parent->username;
            $user->sponsorid = $parent->id;
            $user->btcaddress = 'pending';
            $user->save();
        }else{
            $user->parent = $parent->username;
            $user->parentid = $parent->id;
            $user->save();
        }


        Myhelpers::rebuild_shortmlm_tree('users',1,1);
        //Log::info('asignparent success');
        Session::flash('msg', '<h1>Se ha asignado con éxito</h1>');
        return Redirect::to('multinivel');
    }



    /**
     * Update the specified resource in storage.
     * PUT /posts/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function sendBitcoins()
    {

        // dd($request->all());
        // dd(Auth::user()->id);
        //Log::info('post::sendBitcoins');

        $playnumber = playnumber();

        // dd(Input::get('receiver'));
        // dd(Input::get('receiver'));

        # Verify Ticket Receiver 
        $receiver = User::where('username','=', Input::get('receiver'))->first();
        if(!$receiver)
        {

                
                return redirect()->route('multinivel', ['msg', "El usuario destino: " . Input::get('receiver') . " no existe"]);
                
                
        }


        $amount = Input::get('amount');
        
        // User Holdings
        $holdings = Viewhelper::userholdings(Auth::user()->id);

        if($holdings >= $amount){

            //Take Away Sent Bitcoins
            //from user to user and amount

            $buyticket = new Blockchain;
            $buyticket->user_id = Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $buyticket->receiver_id = $receiver->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $buyticket->reason = 'sent';
            $buyticket->credit = $amount;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $buyticket->playnumber = $playnumber;
            $buyticket->save();

            //Received Bitcoins
            //from user and amount

            $buyticket = new Blockchain;
            $buyticket->user_id = $receiver->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $buyticket->sender_id = Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $buyticket->reason = 'received';
            $buyticket->playnumber = $playnumber;
            $buyticket->debit = $amount;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $buyticket->save();



            // dd(")");
            // return redirect()->route('shome');
            return redirect('multinivel')->with('msg', 'Bitcoin Enviados!!');
            // return redirect()->route('multinivel',['msg' => 'Bitcoin Enviados!!']);
        }

            // return redirect()->route('multinivel',['msg' => 'Bitcoin Enviados!!']);
            return redirect('multinivel')->with('msg', 'No tienes suficientes Bitcoin');
    }




    /**
     * Remove the specified resource from storage.
     * DELETE /posts/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function serepresenante(Request $request)
    {
        //


        if( Input::get("presentacion") == 1 &&
            Input::get("puntosfuertes") == 1 &&
            Input::get("reglas") == 1 &&
            Input::get("tecnico") == 1 &&
            Input::get("volabit") == 1 &&
            Input::get("dinerodigital") == 1){

            $user = User::where('id','=',Auth::user()->id)->first();
            $user->representante = 1;
            $user->save();

            Session::flash('msg', '<h1 style="color:red">Modificado</h1>');

            return redirect()->route('/configuracion');
        }

                Session::flash('msg', '<h1 style="color:red">SinModificacion</h1>');
            return redirect()->route('/configuracion');
        

    }
}
