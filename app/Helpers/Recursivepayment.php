<?php

namespace App\Helpers;

use Log;
use App\Helpers\Viewhelper;
use App\Helpers\Commissionhelper;

use App\Btcusd;
use App\Blockchain;
use App\Lotto;
use App\User;
use App\Sysvar;
use App\Holding;
use Auth;
use DB;

class Recursivepayment{


	public static function paynextmonthsingle($user_id, $btcprice, $playnumber, $commission, $debit = NULL){


		Log::info('Pay Next Month Single User');

			// Put LOTTO Number and activate on Lotto
				$lotto = new Lotto;
				$lotto->user_id = $user_id;
				$lotto->playnumber = $playnumber;

					// WTF para sacar el luckynumber y el parentid

					$user = User::where('id','=',$user_id)->first();

					// if(!$user){
					// 	echo "user not exists " . $user_id;
					// 	dd();
					// }
				$lotto->lotto = $user->luckynumber;
				$lotto->save();


				// Charge btcprice to user 

				/* --------------------------------------
				|  user_id		id Owner of Ticket || Upline parentid for payment
				|  Commission 	Ticket commission
				|  id 			id of user || name of Auth::user()
				|  username     id of username || name of Auth::user()
				|  level 		level of payment max 17
				|  playnumber   exclusive for monthly lotto || 
				|
				*/

				Log::info("--------------------------------");
				Log::info("PAY FEE");
				$crowdfunding = new Blockchain;
				$crowdfunding->user_id = $user_id;
				$crowdfunding->reason =  'play';
				$crowdfunding->playnumber = $playnumber;
				$crowdfunding->credit = $btcprice;
				$crowdfunding->save();




				Log::info("PAY UPLINE");
				// Log::info("----------------");
				$level = Commissionhelper::payCommission3(
					$user->parentid,
					$crowdfunding->id,
					$playnumber,
					$commission,
					0);

				// Log::info("----------------");
				// Log::info("Stop Pay Commissions:");

				


				Log::info("Crowdfunding Level $level");
				Log::info("--------------------------------");
				$crowdfunding = new Blockchain;
				$crowdfunding->user_id = $user_id;
				$crowdfunding->reason =  'crowdfunding';
				$crowdfunding->blockchain_id = $crowdfunding->id;
				$crowdfunding->playnumber = $playnumber;
				$crowdfunding->btcvalue =  $btcprice - ($level * $commission);
				$crowdfunding->level =  $level;
				$crowdfunding->save();


				// echo $btcprice;
				// echo gettype($debit);
				// dd($debit);
				// Myhelpers::acumulados($crowdfunding->btcvalue);
				

	}




	public static function paynextmonth(){
		Log::info('Recursivepayment::paynextmonth()');
		$playlevels = playlevels();
		$playnumber = DB::table('sysvars')->max('id');
			//		Extra obtener valor de pago de comision y hacer el btcprice string en number_format,8    playlevels estan en arreglo, podria ir a DB, pero x ahorita
		$btcprice = Btcusd::orderBy('id','desc')->first();
		$btcprice = number_format($btcprice->btcprice,8);
		$commission = $btcprice / $playlevels;


		//		7.2 BUSCAMOS EN LA BASE DE DATOS where reason = last_month_holdings AND debit >= btcprice y nos traemos el user_id 
		//		   !btcprice es String y last_month_holdings es decimal 
		//			value: 30000000
		//            credit: 0.00000000
		//            debit: 0.30000000




		Log::info('Get DEBIT > btcprice from Temp Holdings Table');
		// revisar que funcione el $btcprice number_format comparado con decimal de MySQL
		$solidusers = Blockchain::where('reason','=','last_month_holdings')->where('debit','>=',$btcprice)->get();
		//        7.3 CORRE EL METODO EXTERNO START2PLAYAUTOMATIC POR CADA USUARIO <-- MODIFICAR Y AGREGAR METODO start2playAutomatic()
		//			ESTE METODO DEBE DE AGREGAR EL DB::Lotto con el playnumber	 y ESTO ACTIVA AL USUARIO EN LA RIFA AL PAGAR SU BOLETO
		//			ESTE METODO AUTOMATICAMENTE GRABA EL CROWDFUNDING|ACUMULADO


		// EXTRA Rename Lotto and create Lotto
		// Schema::create('lottos', function(Blueprint $table)
		// 	{
		// 		$table->increments('id');
		// 		$table->integer('user_id')->nullable();
		// 		$table->integer('ticket_id')->nullable();
		// 		$table->integer('lotto')->nullable();
		// 		$table->integer('filllotto')->nullable();
		// 		$table->integer('playnumber')->nullable();
		// 		$table->timestamps();
		// 	});


		Log::info('For Each User');
		foreach($solidusers as $paidmember) {
			Log::info('New Lotto to enable user next month');

			// Put LOTTO Number and activate on Lotto
				$lotto = new Lotto;
				$lotto->user_id = $paidmember->user_id;
				$lotto->playnumber = $playnumber;

					// WTF para sacar el luckynumber y el parentid

					$user = User::where('id','=',$paidmember->user_id)->first();

					// if(!$user){
					// 	echo "user not exists " . $paidmember->user_id;
					// 	dd();
					// }
				$lotto->lotto = $user->luckynumber;
				$lotto->save();


				// Charge btcprice to user 

				/* --------------------------------------
				|  user_id		id Owner of Ticket || Upline parentid for payment
				|  Commission 	Ticket commission
				|  id 			id of user || name of Auth::user()
				|  username     id of username || name of Auth::user()
				|  level 		level of payment max 17
				|  playnumber   exclusive for monthly lotto || 
				|
				*/

				Log::info("--------------------------------");
				Log::info("PAY FEE");
				$crowdfunding = new Blockchain;
				$crowdfunding->user_id = $paidmember->user_id;
				$crowdfunding->reason =  'play';
				$crowdfunding->playnumber = $playnumber;
				$crowdfunding->credit = $btcprice;
				$crowdfunding->save();




				Log::info("PAY UPLINE");
				// Log::info("----------------");
				$level = Commissionhelper::payCommission3(
					$user->parentid,
					$crowdfunding->id,
					$playnumber,
					$commission,
					0);

				// Log::info("----------------");
				// Log::info("Stop Pay Commissions:");

				


				Log::info("Crowdfunding Level $level");
				Log::info("--------------------------------");
				$crowdfunding = new Blockchain;
				$crowdfunding->user_id = $paidmember->user_id;
				$crowdfunding->reason =  'crowdfunding';
				$crowdfunding->blockchain_id = $crowdfunding->id;
				$crowdfunding->playnumber = $playnumber;
				$crowdfunding->btcvalue =  $btcprice - ($level * $commission);
				$crowdfunding->level =  $level;
				$crowdfunding->save();


				// Myhelpers::acumulados($crowdfunding->btcvalue);
				

		}

	}

	// public static function main() {

	// 		Log::info("-----------------");
	// 		Log::info("RECURSIVE PAYMENT");
			
	// 		$playlevels = playlevels();
	// 		$btcprice = Btcusd::orderBy('id','desc')->first();
	// 		$btcprice = number_format($btcprice->btcprice,8);
	// 		$commission = $btcprice / $playlevels;
	// 		$sysvars = Sysvar::orderBy('id','desc')->first();
	// 		$playnumber = $sysvars->playnumber;



	// 		Viewhelper::recreateholdings();


	// 		$recursivepayments = Holding::where('debit','>=', $btcprice)->where('disabled','=',0)->where('user_id','>',0)->get();

	// 		// dd(count($recursivepayments));
	// 		foreach ($recursivepayments as $paidmember) {
	// 			# code...
	// 			Log::info("START RECURSION...");
	// 			$paidmember->disabled = 1;
	// 			$paidmember->save();

	// 			$lotto = Lotto::where('user_id','=',$paidmember->user_id)->first();
	// 			if(!$lotto) {


	// 				$lotto = new Lotto;
	// 				$lotto->user_id = $paidmember->user_id;
	// 				$lotto->playnumber = $playnumber;

	// 					// WTF para sacar el luckynumber y el parentid

	// 					$user = User::where('id','=',$paidmember->user_id)->first();

	// 					// if(!$user){
	// 					// 	echo "user not exists " . $paidmember->user_id;
	// 					// 	dd();
	// 					// }
	// 				$lotto->lotto = $user->luckynumber;
	// 				$lotto->save();


	// 			// Charge btcprice to user 

	// 				/* --------------------------------------
	// 				|  user_id		id Owner of Ticket || Upline parentid for payment
	// 				|  Commission 	Ticket commission
	// 				|  id 			id of user || name of Auth::user()
	// 				|  username     id of username || name of Auth::user()
	// 				|  level 		level of payment max 17
	// 				|  playnumber   exclusive for monthly lotto || 
	// 				|
	// 				*/

	// 				Log::info("--------------------------------");
	// 				Log::info("Pay Participation BTCPRICE");
	// 				$crowdfunding = new Blockchain;
	// 				$crowdfunding->user_id = $paidmember->user_id;
	// 				$crowdfunding->reason =  'play';
	// 				$crowdfunding->playnumber = $playnumber;
	// 				$crowdfunding->credit = $btcprice;
	// 				$crowdfunding->save();




	// 				Log::info("Pay Commissions:");
	// 				$level = Commissionhelper::payCommission3(
	// 					$user->parentid,
	// 					$crowdfunding->id,
	// 					$playnumber,
	// 					$commission,
	// 					0);

	// 				Log::info("Stop Pay Commissions:");

					


	// 				Log::info("Pay Crowdfunding and level $level");
	// 				Log::info("--------------------------------");
	// 				$crowdfunding = new Blockchain;
	// 				$crowdfunding->user_id = $paidmember->user_id;
	// 				$crowdfunding->reason =  'crowdfunding';
	// 				$crowdfunding->blockchain_id = $crowdfunding->id;
	// 				$crowdfunding->playnumber = $playnumber;
	// 				$crowdfunding->btcvalue =  $btcprice - ($level * $commission);
	// 				$crowdfunding->level =  $level;
	// 				$crowdfunding->save();

	// 			}


	// 		}




	// 		Viewhelper::recreateholdings();


	// 		$recursivepayments = Holding::where('debit','>=', $btcprice)->where('disabled','=',0)->where('user_id','>',0)->get();

	// 		foreach($recursivepayments as $paidmember) {

	// 			Log::info("NEXT RECURSION....");
	// 			$paidmember->disabled = 1;
	// 			$paidmember->save();

	// 			$lotto = Lotto::where('user_id','=',$paidmember->user_id)->first();
	// 			if(!$lotto) {
	// 				// Log::info("paidmember: $paidmember->user_id");
	// 				// Log::info($lotto->user_id);
	// 				// Log::info("Help");
	// 				Recursivepaymenthelper::main();
	// 			}
	// 		}

	// 		// dd("fuck off");

	// 	//        	7.4.2 SE OBTIENE EL VALOR DEL TICKET , EL ULTIMO OBTENIDO QUE YA ESTA GUARDADO EN LA BASE DE DATOS - Listo
	// 	//        	7.4.3 BUSCAMOS EN DB::Holdings where  debit >= btcprice y nos traemos los user_id


	// 	//        7.4 COMIENZA AHORA METODO RECURSIVO
	// 	//        	7.4.1 SE CREA TABLA TEMPORAL DB::Holdings , SE GRABA EL ACUMULADO DE CADA USUARIO EN DB::Holdings fields -- user_id  debit o wealth tabla temporal


	// 	//        	7.4.2 SE OBTIENE EL VALOR DEL TICKET , EL ULTIMO OBTENIDO QUE YA ESTA GUARDADO EN LA BASE DE DATOS - Listo
	// 	//        	7.4.3 BUSCAMOS EN DB::Holdings where  debit >= btcprice y nos traemos los user_id
	// 	//        	7.4.4 SI ENCUENTRA MARCAR UN FLAG DE RECURSIVIDAD
	// 	//        		7.4.4.1 CORRER EL METODO EXTERNO START2PLAYAUTOMATIC POR CADA USUARIO
	// 	//        				ESTE METODO DEBE DE AGREGAR EL DB::Lotto con el playnumber y ESTO ACTIVA AL USUARIO EN LA RIFA AL PAGAR SU BOLETO
	// 	//        				ESTE METODO AUTOMATICAMENTE GRABA EL CROWDFUNDING|ACUMULADO

		

	// }

}

?>