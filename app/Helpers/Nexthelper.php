<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


use Log;
use App\Helpers\Systemhelper;
use DB;
use Auth;
use App\Blockchain;
use App\Acumulado;
use App\Btcusd;

use App\Lotto;
use App\User;
use App\Userbtcaddress;
use App\Holding;
use Myhelpers;
use App\Helpers\Viewhelper;


use Illuminate\Http\Request;

class Nexthelper {

	public static function sendcommissions() {

			Log::info('CAREFUL:: BITCOIN IS IN A SAFE PLACE, NOT ONLINE');
			$btcprice = DB::table('btcusds')->orderBy('id','desc')->first();
			$btcprice = number_format($btcprice->btcprice,8);
			$commission = $btcprice / 20;
			// dd($commission);
			// dd(Sysvar::all());
			$playnumber = DB::table('sysvars')->max('id');
			// dd($btcprice);
			// $playnumber = $playnumber->id;
			$users = User::all();

			foreach($users as $user) {

				$holding = Holding::where('user_id','=',$user->id)->where('debit', '>=', floatval($user->minimumpayment))->first();

				if($holding) {
					Log::info('Send Commission to user');
					$blockchain = new Blockchain;
					$blockchain->user_id = $holding->user_id;
					$blockchain->blockchainaddress = $user->btcaddress;
					$blockchain->destination_address = $user->btcaddress;
					$blockchain->confirmations = 0;
					$blockchain->debit = $holding->debit;
					$blockchain->reason = "sendCommission";
					$blockchain->save();
				}


			}


	}






	public static function databasecontrol()
	{
		$playnumber = DB::table('sysvars')->max('id');


		// CONTABILIDAD 
		// SUM(DEBIT DE HOLDINGS) + CROWDFUNDING DEBE SER IGUAL A LO QUE HAY EN NUESTRA CARTERA BITCOIN

		// 2. SE CONSERVAN LAS BTCADDRESSES DE LOS USUARIOS

		// Drop y Re-Crear Tabla Userbtcaddresses
		Log::info('Tumbar las antiguas Tablas0');

		Schema::drop('userbtcaddresses');
		Schema::create('userbtcaddresses', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id');
				$table->string('blockchainaddress');
				$table->string('secret');
				$table->string('reason');
				$table->timestamps();
			});


		$btcaddresses = Blockchain::where('reason','=','new address')->get();



		foreach($btcaddresses as $address)
		{
			$saveaddress = new Userbtcaddress;
			$saveaddress->user_id = $address->user_id;
			$saveaddress->blockchainaddress = $address->blockchainaddress;
			$saveaddress->secret = $address->secret;
			$saveaddress->reason = $address->reason;
			$saveaddress->save();
		}

		//	3. SE RENOMBRA LA TABLA MENSUAL DE TRANSACCIONES DB::Blockchains+playnumber 

		Log::info("Rename blockchains");

		Schema::rename('blockchains', "blockchains". $playnumber);
		

		//	4. SE CREA UNA NUEVA TABLA DE TRANSACCIONES DB::Blockchains
		Schema::create('blockchains', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id')->nullable();
				$table->integer('blockchain_id')->nullable();
				$table->integer('sender_id')->nullable();
				$table->integer('receiver_id')->nullable();
				$table->integer('commission_id')->nullable();
				$table->integer('cashout_id')->nullable();
				$table->integer('ticket_id')->nullable();
				$table->integer('playnumber')->nullable();
				$table->string('blockchainaddress')->nullable();
				$table->string('input_address')->nullable();
				$table->string('value')->default(0);
				$table->decimal('credit',16,8)->default(0);
				$table->decimal('debit',16,8)->default(0);
				$table->decimal('btcvalue',16,8)->default(0);
				$table->decimal('fee',16,8)->nullable();
				// $table->decimal('amount',16,8)->nullable();
				$table->string('confirmations')->nullable();
				$table->string('custom')->nullable();
				$table->string('type',10)->nullable();
				$table->string('transaction_hash')->nullable();
				$table->string('input_transaction_hash')->nullable();
				$table->string('destination_address')->nullable();
				$table->string('commission')->nullable();
				$table->integer('level')->nullable();			
				$table->string('secret')->nullable();
				$table->string('payer_username')->nullable();
				$table->boolean('callback')->default(0);
				$table->boolean('test')->default(0);
				$table->boolean('buyticket')->default(0);
				$table->string('reason')->default('default');
				$table->timestamps();
			});
		//	5. SE AGREGAN DIRECCIONES BITCOIN DE LOS USUARIOS DESDE DB::Newaddresses
		$addresses = Userbtcaddress::all();
			Log::info("Fill new blochchains new address BTC");

		foreach ($addresses as $address) {
			# code...
			$blockchain = new Blockchain;
			$blockchain->user_id = $address->user_id;
			$blockchain->blockchainaddress = $address->blockchainaddress;
			$blockchain->secret = $address->secret;
			$blockchain->reason = $address->reason;
			$blockchain->save();

		}
	}

	public static function holdingsAndPay() {


		//	6. SE AGREGAN LOS ACUMULADOS al blockchain| HOLDINGS | BITCOINS DE CADA USUARIO CON reason-> "last_month_holdings" o "wealth"
		

		Log::info("Holdings from last month");
		$holdings = Holding::where('user_id','>',0)->get();


			$playlevels = playlevels();
			$playnumber = DB::table('sysvars')->max('id');
				//		Extra obtener valor de pago de comision y hacer el btcprice string en number_format,8    playlevels estan en arreglo, podria ir a DB, pero x ahorita
			$btcprice = Btcusd::orderBy('id','desc')->first();
			$btcprice = number_format($btcprice->btcprice,8);
			$commission = $btcprice / $playlevels;
			


		foreach($holdings as $holding) {

			// echo gettype($holding->debit). "<br>";
			// echo $holding->debit;
			// $holding->debit = floatval($holding->debit);
			// if($holding->debit != "0.00000000") {
			// $holdings = floatval($holding->debit);
			// echo gettype($holdings);
			if($holding->debit > 0) {
				// echo "Is Greater";
				Log::info('YES with holdings');
				$blockchain = new Blockchain;
				$blockchain->user_id = $holding->user_id;
				$blockchain->blockchainaddress = $holding->blockchainaddress;
				$blockchain->secret = $holding->secret;
				$blockchain->debit = $holding->debit;
				$blockchain->reason = "last_month_holdings";
				$blockchain->save();

				if($holding->debit >= $btcprice)
				{
					// dd($holding->debit);
					// Log::info("Pay Next Month if holdings are more than price");
					Recursivepayment::paynextmonthsingle($holding->user_id, $btcprice, $playnumber, $commission,$holding->debit );
				}else{

					Log::info("User can not pay next month");
				}

				$holding->disabled = 1;
				$holding->save();
				
			}else{
				Log::info('NO holdings');
			}


		}


	}
	
}

