<?php

class Myhelpers{


	var $display;
	var $string;


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

	public static function acumulados($crowdfunding = 0){

		$res = Myhelpers::getacumulado();

		$res['Pesos'] = $b = str_replace( ',', '', $res['Pesos'] );
		// dd($res);
		$acumulados = new Acumulado;
		$acumulados->totalPesos = $res['Pesos'];
		$acumulados->totalBitcoin = $res['Bitcoin'];
		$acumulados->amount = $crowdfunding;
		$acumulados->save();

	}

		/*
		|
		| Obtener el acumulado del mes
		| Falta agregar lo del mes pasado, eso como se hacia? pero si se hace.... jejeje
		| Falta el precio correcto del Boleto!! esta anotado como algo urgente!!
		|
		*/
	public static function getacumulado(){

		$playnumber = Config::get('myvars.playnumber');

		
		$btcusd = Btcusd::where('id','>',0)->orderBy('id','desc')->first();

		$crowdIn = DB::table('blockchains')->where('playnumber','=', $playnumber)->where('reason','=', 'crowdfunding')->sum('btcvalue');
		
		$crowdInBitcoin = $crowdIn;
		$crowdInPesos =  number_format(($crowdIn / $btcusd->btcprice) * 100,2);


		return array('Pesos' => $crowdInPesos, 'Bitcoin' => $crowdInBitcoin);
	
	}



	public static function payCommission($mlmuserupline, $blockchain_id, $playnumber, $commission, $level)
	{

		Log::info("PayCommission start() Level: $level");
		$user = User::where('id','=',$mlmuserupline)->first();
		$lotto = Lotto::where('user_id', '=', $mlmuserupline)->where('playnumber','=',Config::get('myvars.playnumber'))->first();

		if(isset($user) && isset($lotto))
		{
			Log::info("PayCommission::user and lotto exist!");

			if(isset($user->parentid) && $user->parentid > 0 && $user->deleted == 0 )
			{
				Log::info("PayCommission::pay! with before level $level");

				$level = $level + 1;


				$buyticket = new Blockchain;
				$buyticket->user_id = $user->parentid;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->sender_id = Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->blockchain_id = $blockchain_id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->type = 'in';// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->reason = 'commission';
				$buyticket->payer_username = Auth::user()->username;
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

				return Myhelpers::payCommission($user->parentid, $blockchain_id, $playnumber, $commission, $level);
			
			}else{
				// echo $level;
				Log::info("PayCommission::stop payment and level $level");
				return $level;
			}

		}
			
	}


	public static function payCommission2($mlmuserupline, $blockchain_id, $playnumber, $commission, $level)
	{

		Log::info("PayCommission start() Level: $level");
		$user = User::where('id','=',$mlmuserupline)->first();
		$lotto = Lotto::where('user_id', '=', $mlmuserupline)->where('playnumber','=',Config::get('myvars.playnumber'))->first();

		if(!$user){
			Log::info("user NOT exists!");
			return $level;
		}

		if(isset($lotto))
		{
				Log::info("user and lotto exist!");

		
				Log::info("pay! with before level $level");

				$level = $level + 1;


				$buyticket = new Blockchain;
				$buyticket->user_id = $user->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->sender_id = Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->blockchain_id = $blockchain_id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->type = 'in';// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->reason = 'commission';
				$buyticket->payer_username = Auth::user()->username;
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

				Log::info("next username..");
				return Myhelpers::payCommission2($user->parentid, $blockchain_id, $playnumber, $commission, $level);
			
			}

		
				Log::info("username Not playing Lotto");
				return Myhelpers::payCommission2($user->parentid, $blockchain_id, $playnumber, $commission, $level);
			
	}


	public static function payCommissionExternal($invitedUser, $mlmuserupline, $blockchain_id, $playnumber, $commission, $level)
	{

		Log::info("PayCommissionExternal Start Level : $level");
		$user = User::where('id','=',$mlmuserupline)->first();
		$invitedUsermodel = User::where('id','=',$invitedUser)->first();


		if(!$user){
			return $level;
		}
		

		$lotto = Lotto::where('user_id', '=', $mlmuserupline)->where('playnumber','=',Config::get('myvars.playnumber'))->first();
		// echo "<pre>";
		// dd($user);


		if(isset($lotto))
		{
			Log::info("user and lotto exist:: " . $user->username);

			
				Log::info("pay! with before level : $level");

				$level = $level + 1;


				$buyticket = new Blockchain;
				$buyticket->user_id = $user->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
				$buyticket->sender_id = Auth::user()->id;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
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
				
				
				// echo $level;

				Log::info("PayCommission::stop payment at level: $level");
				return Myhelpers::payCommissionExternal($invitedUser, $user->parentid, $blockchain_id, $playnumber, $commission, $level);
			}

		
				return Myhelpers::payCommissionExternal($invitedUser, $user->parentid, $blockchain_id, $playnumber, $commission, $level);

	}


	public static function cashout($retiro_id, $amount, $fee){

		
		$playnumber = Config::get('myvars.playnumber');
		$rand = rand(100, 9999);

		$cashout = new Cashout;
		$cashout->user_id = Auth::user()->id;
		$cashout->blockchain_id = $retiro_id;
		$cashout->btcvalue = $amount + $fee;
		$cashout->amount = $amount;
		$cashout->secret = $rand;
		$cashout->playnumber = $playnumber;
		$cashout->fee = $fee;
		$cashout->destination_address = Auth::user()->btcaddress;
		$cashout->save();



		return Redirect::to('tickets');
	}

	public static function lottoview()
	{
		
			$lottos = DB::table('lottos')->where('playnumber','=',Config::get('myvars.playnumber'))->select('lotto')->get();
			
			$result = array();
			foreach ($lottos as $key => $value) {
			    $result[] = $value->lotto;
			}
			
			$lottoView = "<table class='lotto-table' id='lottoboard'><tbody>";
			
			$x=1;
			$y=1;
			$count=0;
			while($count < 9)
			{

					$lottoView .= "<tr>";					
				while($x <= 11)
				{
					if(in_array($y,$result))
					{
						// if($y == 93){
						// 	// echo count($result);
						// 	dd(count(array_keys($result,$y)));
						// 	dd();
						// }
						$lottoView .= "<td style='background-color:gray'><small style='font-size:11px;color:cyan;'>".count(array_keys($result,$y))."</small>".$y."</td>";
					}else
					{
						$lottoView .= "<td>".$y."</td>";
					}

					// $count++;
					$x++;
					$y++;

				}
				$lottoView .= "</tr>";

				$x=1;
				$count++;				
			}

			 $lottoView .= "</tbody></table>";

			 echo $lottoView;

	}

	public static function getlazyusers()
	{
		$users = User::where('parentid','=', Auth::user()->id)->orWhere('sponsorid','=', Auth::user()->id)->get();
		$display = '';

		foreach($users as $u){
			$children = User::where('parentid','=',$u->id)->count();

			if($children == 0){

				$lotto = Lotto::where('user_id','=',$u->id)->where('playnumber','=',Config::get('myvars.playnumber'))->first();
				
				if(!$lotto){
					$display .= "<form action='deleteUser/$u->id'  method='post'>";
					$display .= "<p style='background-color:#3fA;border-radius:5px; line-height:34px;width:300px;padding:15px'>".$u->username." <input type='submit' value='Borrar Usuario'> Esta desactivado de momento... lo activo? hablame..</p></form>";
				}
			}

		}

		return $display;
	}


	public static function getbalance($address)
	{
		return $balance = file_get_contents("http://blockexplorer.com/q/getreceivedbyaddress/$address");

	}

	public static function rebuild_shortmlm_tree($table, $parent_id, $left)
	{
		# Input - parent, and right id. 
		
    	// the right value of this node is the left value + 1   

	    $right = $left+1;   


	    // get all children of this node   

		// $result = DB::select('select * from users where id = ?', array(1));

		$result = DB::select('SELECT * FROM  '.$table.' WHERE parentid= ?', array($parent_id));   
		// dd("this");

		foreach($result as $res)
		{
			// dd("hey");
			$right = Myhelpers::rebuild_shortmlm_tree($table, $res->id, $right);

		}

		// while ($row = mysql_fetch_array($result)) {   

		// // recursive execution of this function for each   

		// // child of this node   

		// // $right is the current right value, which is   

		// // incremented by the rebuild_tree function   

		// 	$right = rebuild_tree($row['title'], $right);   

		// }

		// we've got the left value, and now that we've processed   

		// the children of this node we also know the right value   

		DB::update('UPDATE '.$table.' SET lft = ?, rgt= ? WHERE id = ? ', array($left, $right, $parent_id));

		// mysql_query('UPDATE tree SET lft='.$left.', rgt='.   

		// 	$right.' WHERE title="'.$parent.'";');   


		// return the right value of this node + 1   

		return $right+1;  
	}

	public static function getdomain(){

		$domain = $_SERVER['SERVER_NAME'];
		// $domain = $_SERVER['PHP_SELF'];
		// $domain = $_SERVER['HTTP_HOST'];
		// $domain = $_SERVER['HTTP_REFERER'];
		return $domain;
	    $domain_parts = explode('.', $domain);	
	    dd($domain);
	    if($domain_parts[0] == 'www')
	    {
	    	$domain_parts[0] =  $domain_parts[1];			

	    }

	    return $domain_parts[0];
	}


	public static function displaybasictree($user_id, $count = 0)
	{
		  // look up the parent of this node 

	    $result = DB::select('SELECT id, username, deleted FROM users '. 'WHERE parentid = ?',array($user_id)); 

	  
	    
	 	foreach($result as $res)
		{
			// echo $res->username . "<br>";

			$count++;
			$count = (int) $count;
			$display = '';

			$evolution = Lotto::where('user_id','=',$res->id)->first();
	        if(!$evolution){
	        	if($res->deleted == 1)
	        	{
	        		$thisusername = "------";
	        	}else{
	        		$thisusername = $res -> username ;
	        	}
	        	$display .= str_repeat("|=",$count).'<span style="background-color:#d3d3d3;color:black;padding:1px;border-radius:0px;"><img id="" style="margin:0px;padding:0;" alt="" src="img/page.png">'. $thisusername . "</span><br>" ;  

	        }else{
	        	if($res->deleted == 1)
	        	{
	        		$thisusername = "------";
	        	}else{
	        		$thisusername = $res -> username ;
	        	}
	        	$display .= str_repeat("|=",$count).'<span style="background-color:#BBFF99;color:black;padding:1px;border-radius:0px;"><img id="" style="margin:0px;padding:0;" alt="" src="img/page.png">'. $thisusername . "</span><br>" ;  
	        	
	        }

			// $display = str_repeat("|=", $count) . $res->username . "<br>";
			// $display =  str_repeat('|= ',count(5)) .  $res->username . "<br>" ;
			echo $display;
			// dd($count);


			Myhelpers::displaybasictree($res->id, $count);
			// dd("hey");
			// $right = Myhelpers::rebuild_shortmlm_tree($table, $res->id, $right);

		}


		// dd($result);
	    // return $path; 
	}



	public static function generateNewAddress (){


		$playnumber = Config::get('myvars.playnumber');

		$secret = rand(10000,99999);
		// $secret = 'ZzsMLGKe162CfA5EcG6j';

		$my_address = '1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw';


		$my_callback_url = 'http://dinerodigital.tk/callback?secret='.$secret;

		$root_url = 'https://blockchain.info/api/receive';

		$parameters = 'method=create&address=' . $my_address .'&callback='. urlencode($my_callback_url);

		// $url = $root_url . '?' . $parameters;

		// dd($url);
		Log::info('Create address' . $root_url . '?' . $parameters);
		
		$response = file_get_contents($root_url . '?' . $parameters);

		
		$object = json_decode($response);

		 
		 // ["callback_url"]=> string(44) "http://dinerodigital.tk/callback?secret=5135" 
		 // ["input_address"]=> string(34) "17fu6B88bHEQF37q36H3zwNz6KscTL1Mfg" 
		 // ["destination"]=> string(34) "1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw" 
		 // ["fee_percent"]=> int(0) 
		// dd($object); 

		if($object->input_address)
		{

			$newAddress = new Blockchain;
			$newAddress->user_id = Auth::user()->id;
			$newAddress->type = 'none';
			$newAddress->blockchainaddress = $object->input_address;
			$newAddress->secret = $secret;
			$newAddress->custom = 'destination: '.$object->destination.' callback: '.$object->callback_url;
			$newAddress->reason = 'new address';
			$newAddress->playnumber = $playnumber;
			$newAddress->save();
			
		}

		// $object->input_address;
		// echo 'Send Payment To : ' . $object->input_address;
	}


	public static function generateNewAddressBlockcypher (){

		$playnumber = Config::get('myvars.playnumber');

		$secret = rand(10000,99999);

		$url = 'http://api.blockcypher.com/v1/btc/main/payments';
		 
		//Initiate cURL.
		$ch = curl_init($url);
		 
		//The JSON data.
		$jsonData =  array(
	        'destination' => '1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw',
	        'callback_url' => 'http://dinerodigital.tk/callback?secret='.$secret,
	        'token' => 'e2276de4541ec9a231d01856dfa439a1'
		);
		//Encode the array into JSON.
		$jsonDataEncoded = json_encode($jsonData);
		 
		//Tell cURL that we want to send a POST request.
		curl_setopt($ch, CURLOPT_POST, 1);
		 
		//Attach our encoded JSON string to the POST fields.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		 
		//Set the content type to application/json
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//Execute the request
		 $result = curl_exec($ch);
		  curl_close($ch);

		  // echo "<br>-....";

		  $result  =json_decode($result, true);

		  // echo "<pre>";
		  // var_dump($result); 

		  // dd("END");
		 
		 // ["callback_url"]=> string(44) "http://dinerodigital.tk/callback?secret=5135" 
		 // ["input_address"]=> string(34) "17fu6B88bHEQF37q36H3zwNz6KscTL1Mfg" 
		 // ["destination"]=> string(34) "1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw" 
		 // ["fee_percent"]=> int(0) 
		// dd($object); 

		// { "id": "fd971cbc-06f6-4e3d-af51-0ca793dd5b23", 
		// "token": "e2276de4541ec9a231d01856dfa439a1", 
		// "destination": "1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw", 
		// "input_address": "1LfnPuMZ6v3Em5Me4MoLvQrASPZAkyqvR9", 
		// "callback_url": "http://dinerodigital.tk/callback?secret=8730" }

		if($result['input_address'])
		{

			$newAddress = new Blockchain;
			$newAddress->user_id = Auth::user()->id;
			$newAddress->type = 'none';
			$newAddress->blockchainaddress = $result['input_address'];
			$newAddress->secret = $secret;
			$newAddress->custom = 'token: '.$result['token'].' callback: '.$result['callback_url'];
			$newAddress->reason = 'new address';
			$newAddress->playnumber = $playnumber;
			$newAddress->save();
			
		}

		// $object->input_address;
		// echo 'Send Payment To : ' . $object->input_address;
	}

	public static function retrieveUserPaymentAddress(){

		$userPaymentAddress = Blockchain::where('user_id','=',Auth::user()->id)->whereNotNull('blockchainaddress')->where('callback','=',0)->first();

		// dd($userPaymentAddress);
		// dd("alkfd");

		if(!$userPaymentAddress){
			// dd("he");
			// Myhelpers::generateNewAddress();
			// Myhelpers::retrieveUserPaymentAddress();
			
			return NULL;
		}
		if($userPaymentAddress->blockchainaddress == NULL)
		{
			Myhelpers::generateNewAddress ();
		}

		// dd($userPaymentAddress->id);
		return $userPaymentAddress->blockchainaddress;
	}


	public static function propermoney($value){
		 return round($value * 1e8);
	}


	public static function userholdings2($user_id){

		$ins = Blockchain::where('user_id','=',$user_id)->where('type','=','in')->whereNotIn('reason', array('crowdfunding'))->sum('btcvalue');
		$outs = Blockchain::where('user_id','=',$user_id)->where('type','=','out')->whereNotIn('reason', array('crowdfunding'))->sum('btcvalue');

		$holdings = $ins - $outs;

		if($holdings == 0)
		{
			$holdings = '0.00';
		}
		return $holdings;
	}


	public static function userholdingsNETO(){

		$ins = Blockchain::sum('debit');
		$outs = Blockchain::sum('credit');

		$holdings = $ins - $outs;

		if($holdings == 0)
		{
			$holdings = '0.00';
		}
		return $holdings;
	}

	public static function userholdings($user_id){

		$ins = Blockchain::where('user_id','=',$user_id)->sum('debit');
		$outs = Blockchain::where('user_id','=',$user_id)->sum('credit');


		$holdings = $ins - $outs;

		if($holdings == NULL)
		{
			$holdings = '0.00';
		}else{
			$holdings = $ins - $outs;
		}

		
		return $holdings;
	}

	public static function userins($user_id){

		return $user = Blockchain::where('user_id','=',$user_id)->where('type','=','in')->where('btcvalue','>',0)->whereNotIn('reason', array('crowdfunding'))->sum('btcvalue');

	}

	public static function userouts($user_id){

		return $user = Blockchain::where('user_id','=',$user_id)->where('type','=','out')->where('btcvalue','<',0)->whereNotIn('reason', array('crowdfunding'))->sum('btcvalue');

	}



	public static function nextlottonowinner()
	{
		# take the crowdfunding and add it negative
		$nextlotto = Blockchain::where('reason','=', 'crowdfunding')->where('playnumber','=', Config::get('myvars.playnumber'))->sum('btcvalue');

		// dd($nextlotto);

		$newNoWinnerSum = new Blockchain;
		$newNoWinnerSum->reason = 'crowdfunding';
		$newNoWinnerSum->btcvalue = - $nextlotto;// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
		$newNoWinnerSum->playnumber = Config::get('myvars.playnumber');// The value of the payment received in satoshi. Divide by 100000000 to get the value in BTC.
		$newNoWinnerSum->save();

		$addCrowdfunding = new Blockchain;
		$addCrowdfunding->reason = 'crowdfunding';
		$addCrowdfunding->btcvalue = $nextlotto;
		$addCrowdfunding->playnumber = Config::get('myvars.playnumber') +1;
		$addCrowdfunding->save();

		# change the lotto number
		# add the crowdfunding to lotto, no user id... maybe extra info?
	}

	public static function displaytree($table, $user_id)
	{

		dd();
		$user_id = 1;
		# Start Display blank.
		$display = '';
	    // retrieve the left and right value of the $user_id node  
		// $result = DB::select('SELECT * FROM  '.$table.' WHERE sponsorid= ?', array($parent_id));   

		$result = DB::select('SELECT lft, rgt FROM '.$table.' WHERE id = ?', array($user_id));
		// dd();
		//$result = DB::select('SELECT lft, rgt, mlmbasic.username FROM mlmbasic left join socialpoints on mlmbasic.user_id = socialpoints.user_id');

	    // $result = mysql_query('SELECT lft, rgt FROM tree '.  

	    //                        'WHERE title="'.$user_id.'";');  

	    // $row = mysql_fetch_array($result);  

	  

	    // start with an empty $right stack  

	    $right = array();  

	  	// dd($result[0]->lft);

	    // dd(count($right));
	    // now, retrieve all descendants of the $user_id node  

	    // dd($result[0]->lft);

	    $result = DB::select('SELECT sponsorid, username, id, lft, rgt FROM '. $table .' WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));
	    	    // $result = DB::select('SELECT sponsorid, mlmbasic.username, socialpoints.points, mlmbasic.user_id, lft, rgt FROM '. $table .' left join socialpoints on mlmbasic.user_id = socialpoints.user_id WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));

	    // $result = mysql_query('SELECT title, lft, rgt FROM tree '.  

	    //                        'WHERE lft BETWEEN '.$row['lft'].' AND '.  

	    //                        $row['rgt'].' ORDER BY lft ASC;');  

	  

	    // display each row  

	    // dd(gettype($result));
	    // dd($result);
	    // $result = $result;
	    foreach ($result as $key => $value) 
	    {
	    	# code...
	    	// dd($value);
	    	// dd($right);
	    	// var_dump($value->username);
	    	// echo "hey <br>";
	    	if(count($right) > 0)
	    	{
	    	// echo "hey +<br>";

	    		// dd(gettype($right));
	    		// echo "<br>";
	    		// var_dump($right[count($right)-1]); 
	    		// echo "<br>";
	    		// dd($right[count($right)]); 
	    		// echo "<br>". $value->username;
	    		// var_dump($result[0]->rgt);
	    		// var_dump($right[count($right)-1]-1); 
	    		// dd($right[count($right)-1]-1); 
		    			// echo "<br>";
		    			// var_dump("this >" . $right[count($right)-1] . " is greater than " . $value->rgt . "<<<");
		    		while ($right[count($right)-1] < $value->rgt)
		    		{
		    			// var_dump("this >" . $right[count($right)-1] . " is less than " . $value->rgt . "<<<");
		    			// echo count($right);
		    			// dd("this");
		    			// $right[] = 'last';
		    			array_pop($right);
		    		}




	    	}
	    	// dd(count($right));
	    // }

	    // while ($row = mysql_fetch_array($result)) {  

	    //     // only check stack if there is one  

	    //     if (count($right)>0) {  

	    //         // check if we should remove a node from the stack  

	    //         while ($right[count($right)-1]<$row['rgt']) {  

	    //             array_pop($right);  

	    //         }  

	    //     }  

	  
	    	// $socialpoints = Socialpoint::where('username','=', $value->username)->sum('points');
		  	// if($value->username == "test1")
		  	// {
		  	// 	echo "<pre>";
		  	// 	dd($socialpoints);
		  	// }

	        // display indented node title  
	        // dd($result[0]->lft);
	        // dd($count($right));
	        // echo "count right: " . count($right) . "<br>";  "<div class='align'><i class='icon-cog-circled'></i></div> <div class='align'><i class='icon-cog-circled'></i></div>
	        $display .=  str_repeat('|= ',count($right)).  $value -> username;
	        $strcount =  str_repeat('|= ',count($right)).  $value -> username;


	        $strlen = 90 - strlen($strcount);

	        $strrepeat = str_repeat('-', $strlen);

	        // $display .= $strrepeat . " - " . $socialpoints . "<i> Puntos</i><br>" ;  
	        // $display .=  str_repeat('|= ',count($right)).  $value -> username . "------" . $value->points ."<br>" ;  

	  
	    	// dd("this");

	        // add this node to the stack  
	    // dd($result[0]->rgt);
	        $right[] = $value->rgt;  

    	} 

    	return $display; 

	}  




	public static function fastdisplaytree($parent, $level){


		// dd($parent);
		// $parent is the parent of the children we want to see 

		// $level is increased when we go deeper into the tree, 

		//        used to display a nice indented tree 

	    // retrieve all children of $parent 

	    // $result = mysql_query('SELECT title FROM users '. 

	    //                        'WHERE parent="'.$parent.'";'); 
		$result = DB::select('SELECT id, username, parentid FROM users WHERE parentid = ? ', array($parent));
		// dd($result);

	 

	    // display each child 


	 //    while ($row = mysql_fetch_array($result)) { 

	 //        // indent and display the title of this child 

	 //        echo str_repeat('  ',$level).$row['title']."n"; 

	 

	 //        // call this function again to display this 

	 //        // child's children 

	 //        fastdisplaytree($row['title'], $level+1); 

	    

		// } 
		// $display = "";

		foreach($result as $key)
		{
			// dd($key->username);
			// echo $level;
			$evolution = (bool) Lotto::where('user_id','=',$key->id)->first();

			if($evolution)
			{
		 echo $display =   "<span style='background-color:#AAFF00;border-radius:4px;padding:0px;margin:5px;'>||". str_repeat('|--> ',$level). $key->username  . "</span><br>" ;  
			}else{
		 echo $display =  "<span style='background-color:red;color:black;border-radius:4px;padding:0px;margin:5px;'>||".str_repeat('|--> ',$level).   $key->username. "</span><br>" ;  
				
			}

			// dd("hey");
	 //        // call this function again to display this 

	 //        // child's children 

		// dd($key->id);
	        Myhelpers::fastdisplaytree($key->id, $level+1); 

		}

		// return $display;


	}





	public static function fastdisplaybinarytree($parent, $level){


		// dd();
		$user_id = 1;
		# Start Display blank.
		$display = '';
	    // retrieve the left and right value of the $user_id node  
		// $result = DB::select('SELECT * FROM  '.$table.' WHERE sponsorid= ?', array($parent_id));   

		$result = DB::select("SELECT 'left', 'rigth' FROM users WHERE id = ?", array($user_id));
		// dd();
		//$result = DB::select('SELECT lft, rgt, mlmbasic.username FROM mlmbasic left join socialpoints on mlmbasic.user_id = socialpoints.user_id');

	    // $result = mysql_query('SELECT lft, rgt FROM tree '.  

	    //                        'WHERE title="'.$user_id.'";');  

	    // $row = mysql_fetch_array($result);  

	  

	    // start with an empty $right stack  

	    $right = array();  

	  	// dd($result[0]->lft);

	    // dd(count($right));
	    // now, retrieve all descendants of the $user_id node  

	    // dd($result[0]->lft);

	    $result = DB::select("SELECT 'id', 'sponsorid', 'username', 'left', 'rigth' FROM 'users' WHERE 'left' BETWEEN ? AND ? ORDER BY left ASC", array($result[0]->left, $result[0]->rigth));
	    	    // $result = DB::select('SELECT sponsorid, mlmbasic.username, socialpoints.points, mlmbasic.user_id, lft, rgt FROM '. $table .' left join socialpoints on mlmbasic.user_id = socialpoints.user_id WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));

	    // $result = mysql_query('SELECT title, lft, rgt FROM tree '.  

	    //                        'WHERE lft BETWEEN '.$row['lft'].' AND '.  

	    //                        $row['rgt'].' ORDER BY lft ASC;');  

	  

	    // display each row  

	    // dd(gettype($result));
	    // dd($result);
	    // $result = $result;
	    foreach ($result as $key => $value) 
	    {
	    	# code...
	    	// dd($value);
	    	// dd($right);
	    	// var_dump($value->username);
	    	// echo "hey <br>";
	    	if(count($right) > 0)
	    	{
	    	// echo "hey +<br>";

	    		// dd(gettype($right));
	    		// echo "<br>";
	    		// var_dump($right[count($right)-1]); 
	    		// echo "<br>";
	    		// dd($right[count($right)]); 
	    		// echo "<br>". $value->username;
	    		// var_dump($result[0]->rgt);
	    		// var_dump($right[count($right)-1]-1); 
	    		// dd($right[count($right)-1]-1); 
		    			// echo "<br>";
		    			// var_dump("this >" . $right[count($right)-1] . " is greater than " . $value->rgt . "<<<");
		    		while ($right[count($right)-1] < $value->right)
		    		{
		    			// var_dump("this >" . $right[count($right)-1] . " is less than " . $value->rgt . "<<<");
		    			// echo count($right);
		    			// dd("this");
		    			// $right[] = 'last';
		    			array_pop($right);
		    		}




	    	}
	    	// dd(count($right));
	    // }

	
	  
	  		// if($value->username == "test1")
		  	// {
		  	// 	echo "<pre>";
		  	// 	dd($socialpoints);
		  	// }
		  		// echo $level;
			$evolution = (bool) Lotto::where('user_id','=',$key->id)->first();

			if($evolution)
			{
		 $display =   "<span style='background-color:#AAFF00;border-radius:4px;padding:0px;margin:5px;'>||". str_repeat('|--> ',count($right)). $key->username  . "</span><br>" ;  
			}else{
		 $display =  "<span style='background-color:red;color:black;border-radius:4px;padding:0px;margin:5px;'>||".str_repeat('|--> ',count($right)).   $key->username. "</span><br>" ;  
				
			}

	        // display indented node title  
	        // dd($result[0]->lft);
	        // dd($count($right));
	        // echo "count right: " . count($right) . "<br>";  "<div class='align'><i class='icon-cog-circled'></i></div> <div class='align'><i class='icon-cog-circled'></i></div>
	        // $display .=  str_repeat('|=> ',count($right)).  $value -> username .  "<br>" ;  
	     
	        // add this node to the stack  
	        $right[] = $value->right;  

    	} 

    	echo $display; 

	}



	public static function displaytreeevolution($table, $user_id)
	{

		// dd();
		$user_id = 1;
		# Start Display blank.
		$display = '';
	    // retrieve the left and right value of the $user_id node  
		// $result = DB::select('SELECT * FROM  '.$table.' WHERE sponsorid= ?', array($parent_id));   

		$result = DB::select('SELECT lft, rgt FROM '.$table.' WHERE id = ?', array($user_id));
		// dd();
		//$result = DB::select('SELECT lft, rgt, mlmbasic.username FROM mlmbasic left join socialpoints on mlmbasic.user_id = socialpoints.user_id');

	    // $result = mysql_query('SELECT lft, rgt FROM tree '.  

	    //                        'WHERE title="'.$user_id.'";');  

	    // $row = mysql_fetch_array($result);  

	  

	    // start with an empty $right stack  

	    $right = array();  

	  	// dd($result[0]->lft);

	    // dd(count($right));
	    // now, retrieve all descendants of the $user_id node  

	    // dd($result[0]->lft);

	    $result = DB::select('SELECT sponsorid, username, id, lft, rgt, deleted FROM '. $table .' WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));
	    	    // $result = DB::select('SELECT sponsorid, mlmbasic.username, socialpoints.points, mlmbasic.user_id, lft, rgt FROM '. $table .' left join socialpoints on mlmbasic.user_id = socialpoints.user_id WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));

	    // $result = mysql_query('SELECT title, lft, rgt FROM tree '.  

	    //                        'WHERE lft BETWEEN '.$row['lft'].' AND '.  

	    //                        $row['rgt'].' ORDER BY lft ASC;');  

	  

	    // display each row  

	    // dd(gettype($result));
	    // dd($result);
	    // $result = $result;
	    foreach ($result as $key => $value) 
	    {
	    	# code...
	    	// dd($value);
	    	//dd($right);
	    	// var_dump($value->username);
	    	// echo "hey <br>";
	    	if(count($right) > 0)
	    	{
	    	// echo "hey +<br>";

	    		// dd(gettype($right));
	    		// echo "<br>";
	    		// var_dump($right[count($right)-1]); 
	    		// echo "<br>";
	    		// dd($right[count($right)]); 
	    		// echo "<br>". $value->username;
	    		// var_dump($result[0]->rgt);
	    		// var_dump($right[count($right)-1]-1); 
	    		// dd($right[count($right)-1]-1); 
		    			// echo "<br>";
		    			// var_dump("this >" . $right[count($right)-1] . " is greater than " . $value->rgt . "<<<");
		    		while ($right[count($right)-1] < $value->rgt)
		    		{
		    			// var_dump("this >" . $right[count($right)-1] . " is less than " . $value->rgt . "<<<");
		    			// echo count($right);
		    			// dd("this");
		    			// $right[] = 'last';
		    			array_pop($right);
		    		}




	    	}
	    	// dd(count($right));
	    // }

	    // while ($row = mysql_fetch_array($result)) {  

	    //     // only check stack if there is one  

	    //     if (count($right)>0) {  

	    //         // check if we should remove a node from the stack  

	    //         while ($right[count($right)-1]<$row['rgt']) {  

	    //             array_pop($right);  

	    //         }  

	    //     }  

	  
	    	// $socialpoints = Socialpoint::where('username','=', $value->username)->sum('points');
		  	// if($value->username == "test1")
		  	// {
		  	// 	echo "<pre>";
		  	// 	dd($socialpoints);
		  	// }

	        // display indented node title  
	        // dd($result[0]->lft);
	        // dd($count($right));
	        // echo "count right: " . count($right) . "<br>";  "<div class='align'><i class='icon-cog-circled'></i></div> <div class='align'><i class='icon-cog-circled'></i></div>
	        $evolution = (bool) Lotto::where('user_id','=',$value->id)->first();

				if($evolution && $value->deleted == 0)
				{
			 $display .=   "<span style='background-color:#AAFF00;border-radius:4px;padding:0px;margin:5px;'>||". str_repeat('|--> ',count($right)). $value->username  . "</span><br>" ;  
				}else{
			 $display .=  "<span style='background-color:red;color:black;border-radius:4px;padding:0px;margin:5px;'>||".str_repeat('|--> ',count($right)).   $value->username. "</span><br>" ;  
					
				}
	        // $display .=  str_repeat('|= ',count($right)).  $value -> username . "------" . $value->points ."<br>" ;  

	  
	    	// dd("this");

	        // add this node to the stack  
	    // dd($result[0]->rgt);
	        $right[] = $value->rgt;  

    	} 

    	// echo $display; 
    	return $display; 

	}  

	public static function displaydtree2ul($table, $user_id)
	{

		// $user_id = 1;
		// dd(Auth::user()->id);
		# Start Display blank.
		$display = '';
	    // retrieve the left and right value of the $user_id node  
		// $result = DB::select('SELECT * FROM  '.$table.' WHERE sponsorid= ?', array($parent_id));   

		$result = DB::select('SELECT lft, rgt FROM '.$table.' WHERE id = ?', array($user_id));
		// dd();
		//$result = DB::select('SELECT lft, rgt, mlmbasic.username FROM mlmbasic left join socialpoints on mlmbasic.user_id = socialpoints.user_id');

	    // $result = mysql_query('SELECT lft, rgt FROM tree '.  

	    //                        'WHERE title="'.$user_id.'";');  

	    // $row = mysql_fetch_array($result);  

	  

	    // start with an empty $right stack  

	    $right = array();  

	  	// dd($result[0]->lft);

	    // dd(count($right));
	    // now, retrieve all descendants of the $user_id node  

	    // dd($result[0]->lft);

	    $result = DB::select('SELECT parentid, deleted, username, id, lft, rgt FROM '. $table .' WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));
	    	    // $result = DB::select('SELECT sponsorid, mlmbasic.username, socialpoints.points, mlmbasic.user_id, lft, rgt FROM '. $table .' left join socialpoints on mlmbasic.user_id = socialpoints.user_id WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));

	    // $result = mysql_query('SELECT title, lft, rgt FROM tree '.  

	    //                        'WHERE lft BETWEEN '.$row['lft'].' AND '.  

	    //                        $row['rgt'].' ORDER BY lft ASC;');  

	  
	        // $evolution = Lotto::where('user_id','=',Auth::user()->id)->first();
	        // if(!$evolution){
	        // 	$display .=  '<tr><td style="background-color:red;color:#;padding:1px;border-radius:15px;">'. Auth::user()->username . "</td></tr>" ;  

	        // }else{
	        // 	$display .=  '<tr><td style="background-color:#BBFF99;color:black;padding:1px;border-radius:15px;">'. Auth::user()->username . "</td></tr>" ;  
	        	
	        // }
	    // display each row  

	    // dd(gettype($result));
	    // dd($result);
	    // $result = $result;
	    foreach ($result as $key => $value) 
	    {
	    	# code...
	    	// dd($value);
	    	// dd($right);
	    	// var_dump($value->username);
	    	// echo "hey <br>";
	    	if(count($right) > 0)
	    	{
	    	// echo "hey +<br>";

	    		// dd(gettype($right));
	    		// echo "<br>";
	    		// var_dump($right[count($right)-1]); 
	    		// echo "<br>";
	    		// dd($right[count($right)]); 
	    		// echo "<br>". $value->username;
	    		// var_dump($result[0]->rgt);
	    		// var_dump($right[count($right)-1]-1); 
	    		// dd($right[count($right)-1]-1); 
		    			// echo "<br>";
		    			// var_dump("this >" . $right[count($right)-1] . " is greater than " . $value->rgt . "<<<");
		    		while ($right[count($right)-1] < $value->rgt)
		    		{
		    			// var_dump("this >" . $right[count($right)-1] . " is less than " . $value->rgt . "<<<");
		    			// echo count($right);
		    			// dd("this");
		    			// $right[] = 'last';
		    			array_pop($right);
		    		}




	    	}
	    	// dd(count($right));
	    // }

	    // while ($row = mysql_fetch_array($result)) {  

	    //     // only check stack if there is one  

	    //     if (count($right)>0) {  

	    //         // check if we should remove a node from the stack  

	    //         while ($right[count($right)-1]<$row['rgt']) {  

	    //             array_pop($right);  

	    //         }  

	    //     }  

	  

	        // display indented node title  
	        // dd($result[0]->lft);
	        // dd($count($right));
	        // echo "count right: " . count($right) . "<br>";  "<div class='align'><i class='icon-cog-circled'></i></div> <div class='align'><i class='icon-cog-circled'></i></div>
	        $evolution = Lotto::where('user_id','=',$value->id)->first();
	        if(!$evolution){
	        	if($value -> deleted == 1)
	        	{
	        		$thisusername = "------";
	        	}else{
	        		$thisusername = $value -> username ;
	        	}
	        	$display .=  '<li><h3>'.str_repeat("-&nbsp;",count($right)).'<span style="background-color:#d3d3d3;color:black;padding:1px;border-radius:5px;"><img id="" style="margin:0px;padding:0;" alt="" src="img/page.png">'. $thisusername . "</span></h3></li>" ;  

	        }else{
	        	if($value -> deleted == 1)
	        	{
	        		$thisusername = "------";
	        	}else{
	        		$thisusername = $value -> username ;
	        	}
	        	$display .=  '<li><h3>'.str_repeat("-&nbsp;",count($right)).'<span style="background-color:#BBFF99;color:black;padding:1px;border-radius:5px;"><img id="" style="margin:0px;padding:0;" alt="" src="img/page.png">'. $thisusername . "</span></h3></li>" ;  
	        	
	        }
	        // $display .=  str_repeat('|= ',count($right)).  $value -> username . "------" . $value->points ."<br>" ;  

	  
	    	// dd("this");

	        // add this node to the stack  
	    // dd($result[0]->rgt);
	        $right[] = $value->rgt;  


    	} 

    	// echo "<pre>";
    	// dd($display);

    	return  "<ul style='list-style:none'>".$display."</ul>"; 

	}  


	public static function displaydtreedefault($table, $user_id){
		

		$users = User::where('id','>',0)->get();

		$str = "d = new dTree('d');
				d.add(0,-1,'<span style=border-radius:3px;font-size:22px;text-align:left;left:0;margin:6px;padding:1px;color:black;border-radius:11px;>Inicio</span>');";
		$num = 1;
		foreach($users as $u)
		{
			$evolution = Lotto::where('user_id','=',$u->id)->first();
				// $str .= "d.add($u->id,$u->parentid, '$u->username','#');";	
							
			if($evolution)
			{
				$str .= "d.add($u->id,$u->parentid, '<span style=border-radius:3px;font-size:22px;margin:6px;padding:1px;background-color:#BBFF99;border-radius:11px;>user</span>','#');";				
			}else{
				$str .= "d.add($u->id,$u->parentid, '<span style=border-radius:3px;font-size:22px;margin:6px;padding:1px;background-color:#D3D3D3;border-radius:11px;>user</span>','#');";				
			}
			$num++;
		}

	 // $str = "d = new dTree('d');
	 //    d.add(0,-1,'My example tree');";

	 //    d.add(1,0,'Node 1','example01.html');
	 //    d.add(2,0,'Node 2','example01.html');
	 //    d.add(3,1,'Node 1.1','example01.html');
	 //    d.add(4,0,'Node 3','example01.html');
	 //    d.add(5,3,'Node 1.1.1','example01.html');
	 //    d.add(6,5,'Node 1.1.1.1','example01.html');
	 //    d.add(7,0,'Node 4','example01.html');
	 //    d.add(8,1,'Node 1.2','example01.html');
	 //    d.add(9,0,'My Pictures','example01.html','Pictures I\'ve taken over the years','','','img/imgfolder.gif');
	 //    d.add(10,9,'The trip to Iceland','example01.html','Pictures of Gullfoss and Geysir');
	 //    d.add(11,9,'Mom\'s birthday','example01.html');
	 //    d.add(12,0,'Recycle Bin','example01.html','','','img/trash.gif');

	    $str .= "document.write(d);";
	    // dd($str);
    	return $str;
	}


	public static function displaydtreeauth($table, $user_id){
		

		$users = User::where('id','>',$user_id)->get();
		$user = User::where('id','=',$user_id)->first();

		$str = "d = new dTree('d');
				d.add(0,-1,'Inicio');";
		$num = 1;

		$userid = $user_id;
		$evolution = Lotto::where('user_id','=',$userid)->where('playnumber','=',Config::get('myvars.playnumber'))->first();
				// $str .= "d.add($u->id,$u->parentid, '$u->username','#');";	

			if($evolution)
			{
		$str .= "d.add($userid,0, '<span style=border-radius:3px;padding:1px;background-color:#BBFF99;>$user->username</span>','#');";				
			}else{
		$str .= "d.add($userid,0, '<span style=border-radius:3px;padding:1px;background-color:#D3d3D3;>$user->username</span>','#');";				
			}



		foreach($users as $u)
		{
			$evolution = Lotto::where('user_id','=',$u->id)->where('playnumber','=',Config::get('myvars.playnumber'))->first();
				// $str .= "d.add($u->id,$u->parentid, '$u->username','#');";	

			if($evolution)
			{
				$str .= "d.add($u->id,$u->parentid, '<span style=border-radius:3px;padding:1px;background-color:#BBFF99;>$u->username</span>','#');";				
			}else{
				$str .= "d.add($u->id,$u->parentid, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>$u->username</span>','#');";				
			}
			$num++;
		}

	 // $str = "d = new dTree('d');
	 //    d.add(0,-1,'My example tree');";

	 //    d.add(1,0,'Node 1','example01.html');
	 //    d.add(2,0,'Node 2','example01.html');
	 //    d.add(3,1,'Node 1.1','example01.html');
	 //    d.add(4,0,'Node 3','example01.html');
	 //    d.add(5,3,'Node 1.1.1','example01.html');
	 //    d.add(6,5,'Node 1.1.1.1','example01.html');
	 //    d.add(7,0,'Node 4','example01.html');
	 //    d.add(8,1,'Node 1.2','example01.html');
	 //    d.add(9,0,'My Pictures','example01.html','Pictures I\'ve taken over the years','','','img/imgfolder.gif');
	 //    d.add(10,9,'The trip to Iceland','example01.html','Pictures of Gullfoss and Geysir');
	 //    d.add(11,9,'Mom\'s birthday','example01.html');
	 //    d.add(12,0,'Recycle Bin','example01.html','','','img/trash.gif');

	    $str .= "document.write(d);";
	    // dd($str);
    	echo $str;
	}

	public static function displaydtree($table, $user_id){
		

		$users = User::where('id','>',0)->get();

		$str = "d = new dTree('d');
				d.add(0,-1,'<style='text-align:left'>Inicio</style>');";
				
		// echo count($users);
		// dd();
		foreach($users as $u)
		{
			// dd($u->id);
			// if($u->id => Auth::user()->id){

				$evolution = Lotto::where('user_id','=',$u->id)->first();
					// $str .= "d.add($u->id,$u->parentid, '$u->username','#');";				
				if($evolution)
				{
					$str .= "d.add($u->id,$u->parentid, '<span style=border-radius:3px;padding:1px;background-color:#BBFF99;>$u->username</span>','#');";				
				}else{
					$str .= "d.add($u->id,$u->parentid, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>$u->username</span>','#');";				
				}
			// }
			
		}

	 // $str = "d = new dTree('d');
	 //    d.add(0,-1,'My example tree');";

	 //    d.add(1,0,'Node 1','example01.html');
	 //    d.add(2,0,'Node 2','example01.html');
	 //    d.add(3,1,'Node 1.1','example01.html');
	 //    d.add(4,0,'Node 3','example01.html');
	 //    d.add(5,3,'Node 1.1.1','example01.html');
	 //    d.add(6,5,'Node 1.1.1.1','example01.html');
	 //    d.add(7,0,'Node 4','example01.html');
	 //    d.add(8,1,'Node 1.2','example01.html');
	 //    d.add(9,0,'My Pictures','example01.html','Pictures I\'ve taken over the years','','','img/imgfolder.gif');
	 //    d.add(10,9,'The trip to Iceland','example01.html','Pictures of Gullfoss and Geysir');
	 //    d.add(11,9,'Mom\'s birthday','example01.html');
	 //    d.add(12,0,'Recycle Bin','example01.html','','','img/trash.gif');

	    $str .= "document.write(d);";
	    // dd($str);
    	return $str;
	}


	public static function dtreegoodview($user_id){

		$user_id = 1;
		$display = "";
		$result = DB::select('SELECT lft, rgt FROM users WHERE id = ?', array($user_id));
	    $right = array();  
	    $result = DB::select('SELECT sponsorid, parentid, username, id, lft, rgt FROM users WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));

	   //  foreach ($result as $key => $value) 
	   //  {
	   //  	if(count($right) > 0)
	   //  	{
		  //   		while ($right[count($right)-1] < $value->rgt)
		  //   		{
		  //   			array_pop($right);
		  //   		}




	   //  	}

 			// // $evolution = Lotto::where('user_id','=',$value->id)->first();
	   //      // if(!$evolution){
	   //      // 	$display .=  '<tr>'.str_repeat('<td></td>',count($right)).'<td style="background-color:red;color:black;padding:1px;border-radius:15px;">'. $value -> username . "</td></tr>" ;  

	   //      // }else{
	   //      // 	$display .=  '<tr>'.str_repeat('<td></td>',count($right)).'<td style="background-color:#000000;color:black;padding:1px;border-radius:15px;">'. $value -> username . "</td></tr>" ;  
	        	
	   //      // }
  		 // 	} 


	   //  	$display = 'd = new dTree("d");
				// d.add(0,-1,';

	    	$display = "d = new dTree('d');
				d.add(0,-1,";

					$display .= '"<style=text-align:left>Inicio</style>");';

	    	foreach ($result as $key => $value) 
	   		{
		    	
			    		$evolution = Lotto::where('user_id','=',$value->id)->first();
						// $str .= "d.add($u->id,$u->parentid, '$u->username','#');";				
						if($evolution)
						{
							$display .= "d.add($value->id,$value->parentid, '<span style=border-radius:3px;padding:1px;background-color:#BBFF99;>$value->username</span>','#');";				
						}else{
							$display .= "d.add($value->id,$value->parentid, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>$value->username</span>','#');";				
						}

	    	}
	    
	    
	        	// $display .=  '<tr>'.str_repeat('<td></td>',count($right)).'<td style="background-color:#000000;color:black;padding:1px;border-radius:15px;">'. $value -> username . "</td></tr>" ;  
	        $right[] = $value->rgt;  


	        $display .= "document.write(d);";
    	return  $display; 
    	// echo "<table class='table'>".$display."</table>"; 
	}


}

?>