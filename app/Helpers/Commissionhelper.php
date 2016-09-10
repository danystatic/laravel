<?php
namespace App\Helpers;

use Log;
use App\Helpers\Viewhelper;
use App\User;
use App\Lotto;
use App\Blockchain;
use App\Holding;
use DB;
use Auth;
use App\Helpers\Commissionhelper;
	


class Commissionhelper {

	
	public static function start2playSingle($user_id) {

		Log::info('get::start2play');
			$playlevels = playlevels();
			$playnumber = DB::table('sysvars')->max('id');
			$btcprice = DB::table('btcusds')->orderBy('id','desc')->first();
			$btcprice = number_format($btcprice->btcprice,8);
			$commission = $btcprice / $playlevels;



		$holdings = Viewhelper::userholdings($user_id);

		$user = User::where('id','=',$user_id)->first();

		$lotto = Lotto::where('user_id','=',$user_id)->first();

		// This would only allow 1 number per player
			// if($lotto) {
			// 	return;
			// }
		
		if($holdings >= $btcprice){


				$lotto = new Lotto;
				$lotto->user_id = $user_id;
				$lotto->playnumber = $playnumber;
				$lotto->lotto = $user->luckynumber;
				$lotto->save();




				
				/* --------------------------------------
				|  user_id		id Owner || id to find Upline parentid for payment
				|  Commission 	Ticket commission
				|  id 			id of user || name of Auth::user()
				|  username     id of username || name of Auth::user()
				|  level 		level of payment max 14
				|  playnumber   exclusive for monthly lotto || 
				|
				*/

				Log::info("--------------------------------");
				Log::info("NEW BLOCKCHAIN - PAY");
				$crowdfunding = new Blockchain;
				$crowdfunding->user_id = $user_id;
				$crowdfunding->reason =  'play';
				$crowdfunding->playnumber = $playnumber;
				$crowdfunding->credit = $btcprice;
				$crowdfunding->save();




				Log::info("PAY COMMISSIONS");
				$level = Commissionhelper::payCommission3(
					$user->parentid,
					$crowdfunding->id,
					$playnumber,
					$commission,
					0);


				


				Log::info("Stop Pay Commissions");
				Log::info("CROWDFUNDING: Level - $level");
				Log::info("--------------------------------");
				$crowdfunding = new Blockchain;
				$crowdfunding->user_id = $user_id;
				$crowdfunding->reason =  'crowdfunding';
				$crowdfunding->blockchain_id = $crowdfunding->id;
				$crowdfunding->playnumber = $playnumber;
				$crowdfunding->btcvalue =  $btcprice - ($level * $commission);
				$crowdfunding->level =  $level;
				$crowdfunding->save();


				



			
		}
			
	//END
	}



	public static function start2playBulk() { 

			Log::info("Commissionhelper@start2playBulk");


			$playlevels = playlevels();
			$playnumber = DB::table('sysvars')->max('id');
			$btcprice = DB::table('btcusds')->orderBy('id','desc')->first();
			$btcprice = number_format($btcprice->btcprice,8);
			$commission = $btcprice / $playlevels;

			// dd($commission);

			// Viewhelper::recreateholdings();

			$holdings = Holding::where('debit','>=', $btcprice)->where('user_id','>',0)->get();


			// dd(count($holdings));
			// Log::info('get::start2play');
			foreach($holdings as $h) {

				$lotto = Lotto::where('user_id','=',$h->user_id)->first();

				if(!isset($lotto->user_id)) {


					$lotto = new Lotto;
					$lotto->user_id = $h->user_id;
					$lotto->playnumber = $playnumber;
					$lotto->lotto = $h->user_luckynumber;
					$lotto->save();


					$user = User::where('id','=',$h->user_id)->first();


					
					/* --------------------------------------
					|  user_id		id Owner of Ticket || Upline parentid for payment
					|  Commission 	Ticket commission
					|  id 			id of user || name of $h
					|  username     id of username || name of $h
					|  level 		level of payment max 17
					|  playnumber   exclusive for monthly lotto || 
					|
					*/

					Log::info("--------------------------------");
					Log::info("PAY FEE");
					$crowdfunding = new Blockchain;
					$crowdfunding->user_id = $h->user_id;
					$crowdfunding->reason =  'play';
					$crowdfunding->playnumber = $playnumber;
					$crowdfunding->credit = $btcprice;
					$crowdfunding->save();




					Log::info("PAY UPLINE..........");
					$level = Commissionhelper::payCommission3(
						$user->parentid,
						$crowdfunding->id,
						$playnumber,
						$commission,
						0);


					


					Log::info("CROWDFUNDING Level - $level");
					Log::info("--------------------------------");
					$crowdfunding = new Blockchain;
					$crowdfunding->user_id = $h->user_id;
					$crowdfunding->reason =  'crowdfunding';
					$crowdfunding->blockchain_id = $crowdfunding->id;
					$crowdfunding->playnumber = $playnumber;
					$crowdfunding->btcvalue =  $btcprice - ($level * $commission);
					$crowdfunding->level =  $level;
					$crowdfunding->save();

				}

			}


	}

	
	public static function payCommissionExternal($invitedUser, $mlmuserupline, $blockchain_id, $playnumber, $commission, $level)
	{

		Log::info("PayCommissionExternal Level : $level");
		$user = User::where('id','=',$mlmuserupline)->first();
		$invitedUsermodel = User::where('id','=',$invitedUser)->first();
		$playnumber = playnumber();

		if(!$user){
			return $level;
		}
		


				$level = $level + 1;


				$buyticket = new Blockchain;
				$buyticket->user_id = $user->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->sender_id = \Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->blockchain_id = $blockchain_id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->type = 'in';// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->reason = 'commission';
				$buyticket->payer_username = $invitedUsermodel->username;
				$buyticket->btcvalue = $commission;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->debit = $commission;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->playnumber = $playnumber;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->save();


		
			/* --------------------------------------
			|   Ticket Pay Commission
			|  id        	Ticket id
			|  user_id		id Owner of Ticket || Upline parentid for payment
			|  Commission 	Ticket commission
			|  id 			id of user || name of Auth::user()
			|  username     id of username || name of Auth::user()
			|  level 		level of payment max 17
			|  playnumber   exclusive for monthly lotto || 
			|
			*/
				
				

				// Log::info("next user...");
				return Commissionhelper::payCommissionExternal($invitedUser, $user->parentid, $blockchain_id, $playnumber, $commission, $level);
			
	}



	public static function payCommission3($mlmuserupline, $blockchain_id, $playnumber, $commission, $level)
	{

		$user = User::where('id','=',$mlmuserupline)->first();

		if(!$user){
			Log::info("END OF UPLINE ");
			return $level;
		}else
		{
			
			Log::info("PAY UPLINE COMMISSION Level: $level");
		}

				$level = $level + 1;


				$buyticket = new Blockchain;
				$buyticket->user_id = $user->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				// $buyticket->sender_id = Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->blockchain_id = $blockchain_id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->type = 'in';// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->reason = 'commission';
				// $buyticket->payer_username = Auth::user()->username;
				// $buyticket->btcvalue = $commission;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->debit = $commission;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->playnumber = $playnumber;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->save();


		
			/* --------------------------------------
			|   Ticket Pay Commission
			|  id        	Ticket id
			|  user_id		id Owner of Ticket || Upline parentid for payment
			|  Commission 	Ticket commission
			|  id 			id of user || name of Auth::user()
			|  username     id of username || name of Auth::user()
			|  level 		level of payment max 17
			|  playnumber   exclusive for monthly lotto || 
			|
			*/

				// Log::info("next user..");
				return Commissionhelper::payCommission3($user->parentid, $blockchain_id, $playnumber, $commission, $level);
			
			
	}


}
