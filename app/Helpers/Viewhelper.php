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
use App\Holding;
use Myhelpers;
use App\Helpers\Viewhelper;


use Illuminate\Http\Request;

class Viewhelper {
	


	public static function wallet()
	{
		// $last_month_payment = 100;
		// $userholdings = 100;
		$last_month_payment = Viewhelper::last_month_payment();
		$userholdings = Viewhelper::userholdings(Auth::user()->id);
		$userBtcaddress = Viewhelper::retrieveUserPaymentAddress();
// exit;
			// dd($userBtcaddress);
		return array(
			'last_month_payment' => $last_month_payment, 
			'holdings' => $userholdings,
			'userBtcaddress' => $userBtcaddress
		);

	}

	public static function last_month_payment()
	{
		$amount = Blockchain::where('reason','=','last_month_payment')->where('user_id','=',Auth::user()->id)->first();

		if(!$amount)
			return 0;

		return $amount;
	}

	public static function performance($id)
	{
		$created_at = Auth::user()->created_at;

		$created_at= $created_at->format('m/d/Y');

		$downline = Viewhelper::countdownline();

		return $downline;
	}


	public static function countdownline()
	{
		Viewhelper::rebuild_shortmlm_tree('users',1,1);

		$right = Auth::user()->rgt;
		$left = Auth::user()->lft;

		$downline = (($right - $left) - 1) / 2;

		if(!$downline)
			$downline = 0;

		// dd($downline);
		return $downline;

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
			Log::info('Viewhelper inside foreach');
			$right = Viewhelper::rebuild_shortmlm_tree($table, $res->id, $right);

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

	
	public static function generateNewAddressBlockcypher()
	{
		Log::info('Systemhelper rebuild_shortmlm_tree');

		$playnumber = playnumber();

		$secret = rand(10000,99999);

		$url = 'http://api.blockcypher.com/v1/btc/main/payments';
		 
		//Initiate cURL.
		$ch = curl_init($url);
		 
		//The JSON data.
		$jsonData =  array(
	        'destination' => '1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw',
	        'callback_url' => 'http://www.clubdigital.tk/callback?secret='.$secret,
	        'token' => '8bcedb4675804ecf31297ed9165bbf31'
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
		 
		 // ["callback_url"]=> string(44) "http://clubdigital.tk/callback?secret=5135" 
		 // ["input_address"]=> string(34) "17fu6B88bHEQF37q36H3zwNz6KscTL1Mfg" 
		 // ["destination"]=> string(34) "1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw" 
		 // ["fee_percent"]=> int(0) 
		// dd($object); 

		// { "id": "fd971cbc-06f6-4e3d-af51-0ca793dd5b23", 
		// "token": "e2276de4541ec9a231d01856dfa439a1", 
		// "destination": "1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw", 
		// "input_address": "1LfnPuMZ6v3Em5Me4MoLvQrASPZAkyqvR9", 
		// "callback_url": "http://clubdigital.tk/callback?secret=8730" }

		if($result['input_address'])
		{

			$newAddress = new Blockchain;
			$newAddress->user_id = Auth::user()->id;
			$newAddress->blockchainaddress = $result['input_address'];
			$newAddress->secret = $secret;
			$newAddress->custom = 'token: '.$result['token'].' callback: '.$result['callback_url'];
			$newAddress->reason = 'new address';
			$newAddress->playnumber = $playnumber;
			$newAddress->save();
			
		}

		// $object->input_address;
		// echo 'Send Payment To : ' . $object->input_address;
		return $result['input_address'];
	}


	

	public static function getdomain() { 
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


	public static function dtreegoodview($user_id){

		$user_id = $user_id;
		$display = "";
		$result = DB::select('SELECT lft, rgt FROM users WHERE id = ?', array($user_id));
	    $right = array();  
	    $result = DB::select('SELECT sponsorid, parentid, username, email, id, lft, rgt FROM users WHERE lft BETWEEN ? AND ? ORDER BY lft ASC', array($result[0]->lft, $result[0]->rgt));

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
							if($value->username)
							{
								$display .= "d.add($value->id,$value->parentid, '<span style=border-radius:3px;padding:1px;background-color:#BBFF99;>$value->username</span>','#');";				
							}else
							{
								$display .= "d.add($value->id,$value->parentid, '<span style=border-radius:3px;padding:1px;background-color:#BBFF99;>$value->email</span>','#');";				
								
							}
						}else{
							if($value->username)
							{

								$display .= "d.add($value->id,$value->parentid, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>$value->username</span>','#');";				
							}else{
								
								$display .= "d.add($value->id,$value->parentid, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>$value->email</span>','#');";				
							}
						}

	    	}
	    
	    	
	        	// $display .=  '<tr>'.str_repeat('<td></td>',count($right)).'<td style="background-color:#000000;color:black;padding:1px;border-radius:15px;">'. $value -> username . "</td></tr>" ;  
	        $right[] = $value->rgt;  


	        $display .= "document.write(d);";
    	return  $display; 
    	// echo "<table class='table'>".$display."</table>"; 
	}

	public static function lottotable()
	{
		
			// $playnumber = playnumber();

			$lottos = DB::table('lottos')->select('lotto')->get();
			
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

			 return $lottoView;

	}
	

	public static function lottoviewecho()
	{
		
			// $playnumber = playnumber();

			$lottos = DB::table('lottos')->select('lotto')->get();
			
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
	

	public static function lazyusers()
	{
		$users = User::where('parentid','=', \Auth::user()->id)->orWhere('sponsorid','=', \Auth::user()->id)->get();
		$display = '';

		foreach($users as $u){
			$children = User::where('parentid','=',$u->id)->count();

			if($children == 0){
				
				$lotto = Lotto::where('user_id','=',$u->id)->first();
				
				if(!$lotto){
					$display .= "<form action='deleteUser/$u->id'  method='post'>";
					$display .= "<p style='background-color:#3fA;border-radius:5px; line-height:34px;width:300px;padding:15px'>".$u->username." <input type='submit' value='Borrar Usuario'> Esta desactivado de momento... lo activo? hablame..</p></form>";
				}
			}

		}
		// dd($display);
		return $display;
	}


	public static function retrieveUserPaymentAddress(){

		// dd(Auth::user()->id);

		$userPaymentAddress = Blockchain::where('user_id','=', Auth::user()->id)->whereNotNull('blockchainaddress')->where('callback','=',0)->first();

		// dd($userPaymentAddress->blockchainaddress);
		// dd("alkfd");

		if(!$userPaymentAddress)
			return NULL;
		

		if($userPaymentAddress->blockchainaddress == NULL)
			return NULL;


		//Systemhelper::generateNewAddressBlockcypherTextOrJSON();

		// dd($userPaymentAddress->id);
		return $userPaymentAddress->blockchainaddress;
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




	//Auth::user()->id
	public static function userholdings($user_id){

		// dd($user_id);
		Log::info('Viewhelper::userholdings');

		$ins = floatval(Blockchain::where('user_id','=',$user_id)->sum('debit'));
		$outs = floatval(Blockchain::where('user_id','=',$user_id)->sum('credit'));
		$jackpot = floatval(Blockchain::where('user_id', '=', $user_id)->where('reason','=','jackpot')->sum('debit'));
		// dd($ins);
		// dd(gettype($jackpot));
		$holdings = $ins - $outs;

		if($holdings == NULL)
		{
			$holdings = floatval('0.00000000');
		}else{
			$holdings = $ins - $outs + $jackpot;
		}

		
		return $holdings;
	}



	public static function recreateholdings(){

		Schema::drop('holdings');
		Schema::create('holdings', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id')->nullable();			
				$table->decimal('debit',16,8)->default(0);
				$table->boolean('disabled')->default(0);
				$table->timestamps();
			});


		$users = User::all();

		// create holdings without playnumber

		// en que tabla guardar´e el acumulado?

		foreach ($users as $u) {
			# code...
			// echo $u->username . '--';
			$holding = Viewhelper::userholdings($u->id);
			// echo $holding ."<br>";
			// echo gettype($holding) ."<br>";
			if($holding > 0)
			{

				$userholding = new Holding;
				$userholding->user_id = $u->id;
				$userholding->debit = $holding;
				$userholding->save();
			}

		}

			// CROWDFUNDING 
		$totalcrowdfunding = DB::table('blockchains')->where('reason', '=', 'crowdfunding')->sum('btcvalue');
		$userholding = new Holding;
		$userholding->user_id = 0;
		// echo "crowdfunding" .$userholding->debit = $totalcrowdfunding;
		$userholding->debit = $totalcrowdfunding;
		$userholding->save();

	}


}



