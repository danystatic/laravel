<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Helpers\Commissionhelper;
use Illuminate\Http\Request;
use App\Helpers\Viewhelper;
use App\Http\Requests;
use App\Blockchain;
use App\Btcusd;
use DB;

class SystemController extends Controller
{
    
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'btcusd']);
    }




     /**
     * Agregar mejor a evento o algo para que no consuma recursos
     *
     * @return \Illuminate\Http\Response
     */
    public function callback(){
        //

        if(! Input::get('secret')){
            //Log::info('CallbackController deposit FAIL');
            //Log::info('~ ERROR OR SAMPLE CALLBACK ~');
            exit();
        }else{
            //Log::info('CallbackController deposit attempt');
        }

        //Log::info('~~~~~~~~~~~~~~~');
        //Log::info('|value: '.  Input::get('value'));
        //Log::info('|input_address: '.  Input::get('input_address'));
        //Log::info('|confirmations: '.  Input::get('confirmations'));
        //Log::info('|transaction_hash: '.  Input::get('transaction_hash'));
        //Log::info('|input_transaction_hash: '.  Input::get('input_transaction_hash'));
        //Log::info('|destination_address: '.  Input::get('destination_address'));
        //Log::info('~~~~~~~~~~~~~~~');

        $blockchain = Blockchain::where('secret','=',Input::get('secret'))->where('callback','=',0)->first();
        // $btcprice = DB::table('btcusds')->orderBy('id','desc')->first();

        if($blockchain)
        {
            $blockchain->input_transaction_hash = Input::get('input_transaction_hash');// The original paying in hash before forwarding.
            $blockchain->destination_address = Input::get('destination_address');// 
            $blockchain->transaction_hash = Input::get('transaction_hash');// The transaction hash.
            $blockchain->input_address = Input::get('input_address');// The bitcoin address that received the transaction.
            $blockchain->confirmations = Input::get('confirmations');// The number of confirmations of this transaction.
            $blockchain->btcvalue = Input::get('value') / 100000000;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $blockchain->debit = Input::get('value') / 100000000;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $blockchain->secret = Input::get('secret') + " used";//{Custom Parameters} Any parameters included in the callback URL will be passed back to the callback URL in the notification.
            $blockchain->callback = $blockchain->callback + 1;
            $blockchain->value = Input::get('value');// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
            $blockchain->test = Input::get('test');
            $blockchain->reason = 'deposit';

            $blockchain->save();

            //Log::info("Success  id:$blockchain->id");
            echo "*ok*";

                
            //Log::info("start2playSingle - Transaction - After deposit pay next month fee if needed.");
            Commissionhelper::start2playSingle($blockchain->user_id);
            exit();
        }

        //Log::error("Writing to blockchain Fail !");
        echo "*ok*";
    }


    public function generateNewAddressBlockcypher()
    {
        //
        //Log::info('SystemController generateNewAddressBlockcypher');
    
        $paymentAddress = Viewhelper::generateNewAddressBlockcypher();


        // if(strlen($paymentAddress) != 34) {
            
        // }
        return redirect('/control');

    }

    /**
     * Show the form for creating a new resource.
     * GET /system/create
     *
     * @return Response
     */
    public function btcusd()
    {
        //
        //Log::info('SystemController Logout');

        // dd("hey");
        $now = date("Y-m-d H:i:s");

        //Log::info('get::btcusd'. $now);

        $mensualidad = precioboleto();

        //Log::error('Error: line here 1');
        try{

            //Log::error('Error: line here 1.5');
            $api = file_get_contents('https://www.volabit.com/api/v1/ticker/ticker');
            // $api = file_get_contents('http://currency-api.appspot.com/api/MXN/USD.json?key=ffd71f1784f68ed84e0dda6bc07b4220c55ef198&amount='.$pesos);


                if(isset($api)){

                //Log::error('Error: line here 2');

                    $api = json_decode($api);
                    $usd = $mensualidad / $api->usd_mxn_sell;
                    
                    // dd(api);
                    //$api = ffd71f1784f68ed84e0dda6bc07b4220c55ef198
                    // //Log::info('file_get_contents blockchains');

                    // $btc = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$usd);
                    
                    // dd(cienpesos);
                    $btc = $mensualidad / $api->btc_mxn_sell;
                    $btc2 = $mensualidad / $api->btc_mxn_sell;
                    // dd($btc2);

                    $btcusd = new Btcusd;
                    $btcusd->usd_mxn_buy = $api->usd_mxn_buy;
                    $btcusd->usd_mxn_sell = $api->usd_mxn_sell;
                    $btcusd->btc_mxn_buy = $api->btc_mxn_buy;
                    $btcusd->btc_mxn_sell = $api->btc_mxn_sell;
                    $btcusd->pesosprice = $btc2;
                    $btcusd->usdprice = $usd;
                    $btcusd->btcprice = $btc;
                    $btcusd->save();

                }
                //Log::error('Error: line here 3');

            }catch(Exception $e){
                //Log::error('Error: line here 4');
            }
    }



}
