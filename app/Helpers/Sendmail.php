<?php 
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;


use Log;
use App\Helpers\Systemhelper;
use DB;
use Auth;

	class Sendmail {



		public static function verify($email, $confirmation_code, $username, $sponsoremail){

			// die("hey die!");
			$message = View::make('emails.welcome');//->with('confirmation_code', $confirmation_code);
			$subject = "Bitcoin, una nueva realidad digital." . $username;

			$headers = "From: " . "do-not-reply@clubdigital.tk" . "\r\n";
			$headers .= "Reply-To: ". "do-not-reply@clubdigital.tk" . "\r\n";
			$headers .= "CC: do-not-reply@clubdigital.tk\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				  // $headers = 'From: do-not-reply@clubdigital.tk' . "\r\n" . 'Reply-To: do-not-reply@clubdigital.tk' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); 
				  // $headers = 'From: admin@clubdigital.tk' . "\r\n" . 'Reply-To: admin@clubdigital.tk' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); 
				$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
				  // mail($to, $subject, $message, $headers, 'admin@clubdigital.tk'); 
				$mail_sent = @mail( $email, $subject, $message, $headers );
				  // mail($to, $subject, $message, $headers, 'do-not-reply@clubdigital.tk'); 
				$mail_sent = @mail( $sponsoremail, $subject, $message, $headers );
				  // mail($email, $subject, $message, $headers, 'do-not-reply@clubdigital.tk'); 
			// ////////////////////////

			
			// $headers = "From: no-reply@clubdigital.tk\r\nReply-To: danielvt@gmail.com";
			// // $headers = "From: danielvt@gmail.com\r\nReply-To: danielvt@gmail.com";
			// $headers .= "Reply-To: danielvt@gmail.com\r\n";
			// $headers .= "MIME-Version: 1.0\r\n";
			// $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			
			// $mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			// $mail_sent = @mail( $email, $subject, $message, $headers );
			// $mail_sent = @mail( $sponsoremail, $subject, $message, $headers );
			// // dd( $res );
			return "Mail Sent";
			Log::info("3 verification emails sent!");
		
		}


		public static function visitor(){

			// die("hey die!");
			mail( "danielvt@gmail.com", "Visitor" , "Visitor" );
			mail( "danystatic@hotmail.com", "Visitor" , "Visitor" );
			// dd( $res );
		
		}


		public static function sendmail1(){

			// die("hey die!");
			$res = mail( "danystatic@hotmail.com", "Test from de lappie..." , "Hello World!" );
			dd( $res );
		
		}


		public static function visitcomment($id, $email, $message){
			//define the receiver of the email
			
			
			//define the subject of the email
			$subject = "Evolution::Comment"; 
			//define the message to be sent. Each line should be separated with \n

			$message = "<h1>".$id."</h1><h1>". $email . "</h1>" . $message; 
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: dany@laevolucionsocial.com\r\nReply-To: dany@laevolucionsocial.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: dany@laevolucionsocial.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}

		public static function userlogin1(){
			//define the receiver of the email
			
			$username = Auth::user()->username;
			//define the subject of the email
			$subject = "Welcome, $username"; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<html><body style="background-color:#EDEDED;text-align:center;">';
			$message .= "<div style='background-color:#EDEDED;padding:2em;border-radius:20px;'>";
			// $message .= "<img src='http://laevolucionsocial.com/img/laevolucionsocial.jpg' alt='Evolución' />";
			$message .= "<h1>User Login<h1>"; 
			$message .= "<br>$username<br>"; 
			$message .= "</div>";
			$message .= "</body></html>";
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: dany@laevolucionsocial.com\r\nReply-To: dany@laevolucionsocial.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: dany@laevolucionsocial.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}

		public static function userlogin($email, $username){
			//define the receiver of the email
			
			$to = $email;
			//define the subject of the email
			$subject = "Welcome, $username"; 
			//define the message to be sent. Each line should be separated with \n

			// $message = '<html><body style="background-color:#EDEDED;text-align:center;">';
			// $message .= "<div style='background-color:#EDEDED;padding:2em;border-radius:20px;'>";
			// $message .= "<img src='http://laevolucionsocial.com/img/laevolucionsocial.jpg' alt='Evolución' />";
			// $message .= "<br><br><h3 style='text-shadow: 0 3px 3px #AFAFAF' >Hola !</h3><br><br>\n\n\n\n\n\nBienvenido a Evolución.\n\n<br><br>\n\nProbamos el poder del internet, multnivel y del bitcoin. Creamos juntos un sistema para ayudarnos a nosotros y a la comunidad. \n\n<br><br> Agreganos como mail seguro.<br> Esta es nuestra oportunidad de juntos crear nuestro proyecto/negocio digital."; 
			// $message .= "<br><br><h3 style='text-shadow: 0 2px 2px white'>Cordial y Atentamente. <br> </h3><h2 style='text-shadow: 0 3px 3px white'>Evolución</h2>"; 
			// $message .= "</div>";
			// $message .= "</body></html>";
			$message  = "user login: $username";
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: danielvt@gmail.com\r\nReply-To: danielvt@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: danielvt@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}


		
		public static function mailnewmember($newmember, $username){
			//define the receiver of the email
			
			$to = $newmember;
			//define the subject of the email
			$subject = "Welcome, $username"; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<html><body style="background-color:#EDEDED;text-align:center;">';
			$message .= "<div style='background-color:#EDEDED;padding:2em;border-radius:20px;'>";
			$message .= "<img width='300px' src='http://dinerodigital.tk.com/img/bit_change_world.png' alt='Evolución' />";
			$message .= "<br><br><h3 style='text-shadow: 0 3px 3px #AFAFAF' >Hola !</h3><br><br>\n\n\n\n\n\nBienvenido a Evolución.\n\n<br><br>\n\nProbamos el poder del internet, multnivel y del bitcoin. Creamos juntos un sistema para ayudarnos a nosotros y a la comunidad. \n\n<br><br> Agreganos como mail seguro.<br> Esta es nuestra oportunidad de juntos crear nuestro proyecto/negocio digital."; 
			$message .= "<br><br><h3 style='text-shadow: 0 2px 2px white'>Cordial y Atentamente. <br> </h3><h2 style='text-shadow: 0 3px 3px white'>Evolución</h2>"; 
			$message .= "<h1><a href='http://dinerodigital.tk'>Video Cursos</h1>";
			$message .= "</div>";
			$message .= "</body></html>";
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: danielvt@gmail.com\r\nReply-To: danielvt@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: danielvt@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}



		public static function mailimage($newmember, $username, $url, $msg){
			//define the receiver of the email
			
			$to = $newmember;
			//define the subject of the email
			$subject = "Hola, $username"; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<html><body style="background-color:#EDEDED;text-align:center;">';
			$message .= "<div style='background-color:#EDEDED;padding:2em;border-radius:20px;'>";
			$message .= "<img src='$url' alt='Evolución' />";
			$message .= "<br><br><h3 style='text-shadow: 0 3px 3px #AFAFAF' >Hola! ( dale click en 'display images para que veas la imagen' )</h3><br><br>\n\n\n\n\n\n $msg \n\n<br><br> Agreganos como mail seguro.<br> Esta es nuestra oportunidad de juntos crear nuestro proyecto/negocio digital."; 
			$message .= "<br><br><h3 style='text-shadow: 0 2px 2px white'>Cordial y Atentamente. <br> </h3><h2 style='text-shadow: 0 3px 3px white'>Evolución</h2>"; 
			$message .= "</div>";
			$message .= "</body></html>";
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: danielvt@gmail.com\r\nReply-To: danielvt@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: danielvt@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}

		public static function mailnewtempmember($newmember, $username){
			//define the receiver of the email
			
			$to = $newmember;
			//define the subject of the email
			$subject = "Welcome, $username"; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<html><body style="background-color:#EDEDED;text-align:center;">';
			$message .= "<div style='background-color:#EDEDED;padding:2em;border-radius:20px;'>";
			// $message .= "<img src='http://laevolucionsocial.com/img/laevolucionsocial.jpg' alt='Evolución' />";
			$message .= "<br><br><h3 style='text-shadow: 0 3px 3px #AFAFAF' >Hola !</h3><br><br>\n\n\n\n\n\nBienvenido a Evolución.\n\n<br><br>\n\n Probamos el poder del internet, multnivel y del bitcoin. Creamos juntos un sistema para ayudarnos a nosotros y a la comunidad. \n\n<br><br> Agreganos como mail seguro.<br> Esta es nuestra oportunidad de juntos crear nuestro proyecto/negocio digital."; 

			$message .= "<br><br><h1 style='text-shadow: 0 2px 2px white;color:red'>Por el momento eres un usuario temporal pidele a tu patrocinador que te agregue. <br> </h1>"; 
			$message .= "<br><br><h3 style='text-shadow: 0 2px 2px white'>Cordial y Atentamente. <br> </h3><h2 style='text-shadow: 0 3px 3px white'>Evolución</h2>"; 
			$message .= "</div>";
			$message .= "</body></html>";
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: danielvt@gmail.com\r\nReply-To: danielvt@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: danielvt@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}


		public static function getBitcoinAddress($email, $username){
			//define the receiver of the email
			
			$to = $email;
			//define the subject of the email
			$subject = "Welcome, $username"; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<html><body style="background-color:#EDEDED;text-align:center;">';
			$message .= "<div style='background-color:#EDEDED;padding:2em;border-radius:20px;'>";
			// $message .= "<img src='http://laevolucionsocial.com/img/laevolucionsocial.jpg' alt='Evolución' />";
			$message .= "<br><br><h3 style='text-shadow: 0 3px 3px #AFAFAF' >Hola !</h3><br><br>\n\n\n\n\n\nBienvenido a Evolución.\n\n<br><br>\n\n Favor de confirmar que usted desea una dirección de Bitcon para adquirir tickets. \n\n<br><br> Este mensaje es solo al inicio del proyecto.<br> Esta es nuestra oportunidad de juntos crear nuestro proyecto/negocio digital."; 
			$message .= "<br><br><h3 style='text-shadow: 0 2px 2px white'>Cordial y Atentamente. <br> </h3><h2 style='text-shadow: 0 3px 3px white'>Evolución</h2>"; 
			$message .= "</div>";
			$message .= "</body></html>";
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: danielvt@gmail.com\r\nReply-To: danielvt@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: danielvt@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			$mail_sent = @mail( "danystatic@hotmail.com", $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}


		public static function mailerror2($newmember = NULL){


			// dd($newmember->email);
			$to = $newmember->email;
			//define the subject of the email
			$subject = 'Actualizaciones'; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<!DOCTYPE html><html><body>';

			$message .= '<img src="http://guiseppelidonnici.com/img/socialevolution.png" alt="Evolución" />';
			$message .= "</body></html>";
			$message .= "<h1 style='font-weight:bold'>Importante: Hola, gracias por atreverte y participar.</h1><h2>Favor de ahora usar tu email para entrar</h2>";
			$message .= "<h2 style='font-weight:bold'>username: $newmember->email</h2>";
			$message .= "<h2 style='font-weight:bold'>password: $newmember->passwordstring</h2>";
			$message .= "<h1 style='font-weight:bold>Para entrar al sistema es necesario usar tu e-mail y password.</h1>";
			$message .= "<h2 style='font-weight:bold;border-bottom:dashed gray 2px'>Cualquier mensaje porfavor responde este mensaje o escribe <a mailto:'311evolution@gmail.com'>311evolution@gmail.com</a></h2>";
			$message .= "<br><br>";
			$message .= "<h1 style='font-weight:bold;border-bottom:dashed gray 2px'>Noticias del sistema:</h1>";
			$message .= "<ul>";
			$message .= "<li>El sistema ha migrado a una nueva plataforma.</li>";
			$message .= "<li>Ahora podrá correr múltiples sistemas del mismo tipo.</li>";
			$message .= "<li>Podrás ínvitar a personas por e-mail, muy pronto te diremos como.</li>";
			$message .= "<li>Por el momento es necesario utilizar su e-mail y password</li>";
			$message .= "</ul>";
				
			$message .= "<br><br>";
			$message .= "<h2 style='background-color:#ADFF2F;padding:1em;border-radius:9px'>Tu opinión y sugerencia es bienvenida</h2>"; 
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: 311evolution@gmail.com\r\nReply-To: 311evolution@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: 311evolution@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";
			return $sentmail;

		}

		public static function startemail($newmember = NULL, $data = NULL){

			if($newmember == NULL){
				$newmember = new StdClass;
				$newmember->email = 'danystatic@hotmail.com';
				$newmember->passwordstring = 'testing';
			}
			if($data == NULL){
				$data['systemupdates'] = array(
					'Extensión de encuestas 70%',
					'Extensión del Foro 80% (Funcional)',
					'Por este medio se enviaran actualizaciones y noticias');
				$data['todos'] = array('Automatización de Respaldo',
					'Las encuestas son parte del sistema y la idea de todos tener una opinión',
					'El Foro se utilizará para comunicarse con otros miembros',
					'La meta del Foro es que sea más sencillo la comunicación para crecer el sistema',
					'Sus sugerencias son bienvenidas');
			}

			$to = $newmember->email;
			//define the subject of the email
			$subject = 'Actualizaciones'; 
			//define the message to be sent. Each line should be separated with \n

			$message = '<!DOCTYPE html><html><body>';

			// $message .= '<img src="http://laevolucionsocial.com/img/socialevolution.png" alt="Evolución" />';
			$message .= "</body></html>";
			$message .= "<h1 style='font-weight:bold;border-bottom:dashed gray 2px'> Hola: $newmember->username</h1>";
			$message .= "<br><br>";
			$message .= "<h1 style='font-weight:bold;border-bottom:dashed gray 2px'>Noticias del sistema:</h1>";
			foreach($data['systemupdates'] as $systemupdates){
				$message .= "<h3 style='padding-left:1em'>- $systemupdates</h3>";
			}
			$message .= "<br><br>";
			
			$message .= "<h1 style='font-weight:bold;border-bottom:dashed gray 2px'>Avisos:</h1>";
			// $message .= "<p>";
			foreach($data['todos'] as $todo){
				$message .= "<h3 style='padding-left:1em'>- $todo</h3>";
			}
			// $message .= "</p>";
			$message .= "<br><br>";
			$message .= "<h2 style='background-color:#ADFF2F;padding:1em;border-radius:9px'>Tu opinión y sugerencia es bienvenida <br> Ya tengo una lista de gente que va entrar.</h2>"; 
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "From: 311evolution@gmail.com\r\nReply-To: 311evolution@gmail.com";
			//send the email
			// $headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: 311evolution@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			$sentmail = $mail_sent ? "Mail sent" : "Mail failed";

			return $sentmail;
		}

		public static function sendmail2($useremail) {  
            // PREPARE THE BODY OF THE MESSAGE

            // $useremail = array('danystatic@hotmail.com','danystatic@hotmail.com','danystatic@hotmail.com');

			$count = 0;

			foreach ($useremail as $email) {
				# code...
				// dd("hey die");
				// dd($email);


				// var_dump($email->email_id);


		


				$data ="data";
				$to = $email->invited_email;
				// $to = "danystatic@hotmail.com";
				// $to = $email->email_id;
				// $sendtoemail = "danystatic@hotmail.com";

				// dd($sendtoemail);
				$message = '<html><body>';
				$message .= '<img src="http://guiseppelidonnici.com/img/socialevolution.png" alt="Evolución" />';
				$message .= '<table rules="all" width="60%" border="0" cellpadding="10">';
				$message .= "<tr><td><strong>Hola familia:</strong> </td></tr>";
				$message .= "<tr><td><strong>Talvez les gustaría saber que </br>";
				$message .= "ya es momento de inscribirnos nosotros.</td></tr>";
				$message .= "<tr><td><strong>CON CUIDADO QUE PODRIA OCURRIR ALGUN ERROR</br> </td></tr>";
				$message .= "<tr><td><strong>YA NO QUIERO CAMBIAR LAS IMAGENES DE ABAJO.</br> </td></tr>";
				$message .= "<tr><td><strong>DORMIRÉ PORQUE ME HACE FALTA.</br> </td></tr>";
				$message .= "</table>";
				$message .= '<img src="http://sp7.fotolog.com/photo/39/50/96/dvoladavoy/1153203392_f.jpg" alt="Friends last forever." />';
				$message .= '<img src="http://guiseppelidonnici.com/img/socialfeedback.png" alt="Please porfavor, dime la verdad." />';
				$message .= "</body></html>";
				
				
				
		
	            
	            //   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
	             
	            $cleanedFrom = "evolution311@gmail.com";
				// $to = $sendtoemail;
				
				$subject = 'Evolucion Social, una vez mas.';
				
				$headers = "From: " . $cleanedFrom . "\r\n";
				$headers .= "Reply-To: ". strip_tags($cleanedFrom) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=utf-8\r\n";

	              // echo "Your message has been sent. to => $to <br>";
	            if (mail($to, $subject, $message, $headers)) {
	              echo "Your message has been sent. to => $to <br>";
	            } else {
	              echo 'There was a problem sending the email.';
	            }

	              // echo 'Your message has been sent.';
	            sleep(1);

	            $count++;
			}

	        
            // dd("hey die!");
            return $count;

        }

	

		public static function sendmail2_old($useremail) {  
            // PREPARE THE BODY OF THE MESSAGE

            // $useremail = array('danystatic@hotmail.com','danystatic@hotmail.com','danystatic@hotmail.com');

			$count = 0;

			foreach ($useremail as $email) {
				# code...
				// dd("hey die");
				// dd($email);


				// var_dump($email->email_id);


		


				$data ="data";
				// $sendtoemail = $useremail->email_id;
				$to = "danystatic@hotmail.com";
				// $to = $email->email_id;
				// $sendtoemail = "danystatic@hotmail.com";

				// dd($sendtoemail);
				$message = '<html><body>';
				$message .= '<img src="http://guiseppelidonnici.com/img/socialevolution.png" alt="Evolución" />';
				$message .= '<table rules="all" width="60%" border="0" cellpadding="10">';
				$message .= "<tr><td><strong>Hola!:</strong> </td></tr>";
				$message .= "<tr><td><strong>Talvez te gustaría saber que este email sí es automático</td></tr>";
				$message .= "<tr><td><strong>Lo envié desde mi casa solo a pocas 33 personas.</td></tr>";
				$message .= "<tr><td><strong>Y como has estado? tengo tiempo de no platicar contigo.</td></tr>";
				$message .= "<tr><td><strong>te mando un saludo y mis mejores deseos.</td></tr>";
				$message .= "<tr><td><strong>No estoy haciendo un 'negocio' de Emails eh, no me ínteresa eso de mandar emails y cobrar.</td></tr>";
				$message .= "<tr><td><strong>Talvez ya sabes que ando haciendo, talvez no. Pero si quieres saber más....</td></tr>";
				$message .= "<tr><td><strong>Si no quieres recibir mis mensajes, respondemé y dime que ya no te mande y punto.</td></tr>";
				$message .= "<tr><td><strong>Estoy trabajando en un documento para intentar explicarte(con dibujos) lo que quiero hacer, no todos me entienden.</td></tr>";
				$message .= "<tr><td><strong>El que me ayude le doy las gracias, y a la que me ayude también.</td></tr>";
				$message .= "<tr><td><strong>Los amigos verdaderos duran toda la vida.</td></tr>";
				$message .= "</table>";
				$message .= '<img src="http://sp7.fotolog.com/photo/39/50/96/dvoladavoy/1153203392_f.jpg" alt="Friends last forever." />';
				$message .= '<img src="http://guiseppelidonnici.com/img/socialfeedback.png" alt="Please porfavor, dime la verdad." />';
				$message .= "</body></html>";
				
				
				
		
	            
	            //   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
	             
	            $cleanedFrom = "evolution311@gmail.com";
				// $to = $sendtoemail;
				
				$subject = 'Evolucion Social, una vez mas.';
				
				$headers = "From: " . $cleanedFrom . "\r\n";
				$headers .= "Reply-To: ". strip_tags($cleanedFrom) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=utf-8\r\n";

	              // echo "Your message has been sent. to => $to <br>";
	            if (mail($to, $subject, $message, $headers)) {
	              echo "Your message has been sent. to => $to <br>";
	            } else {
	              echo 'There was a problem sending the email.';
	            }

	              // echo 'Your message has been sent.';
	            sleep(1);

	            $count++;
			}

	        
            // dd("hey die!");
            return $count;

        }

	

		public static function mailnewmember_OLD($newmember) {  
            // PREPARE THE BODY OF THE MESSAGE

			$data ="data";
			$sendtoemail = "danystatic@hotmail.com";
			$message = '<html><body>';
			$message .= '<img src="http://css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" />';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>hello</td></tr>";
			$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($data) . "</td></tr>";
			$message .= "<tr><td><strong>Type of Change:</strong> </td><td>" . strip_tags($data) . "</td></tr>";
			$message .= "<tr><td><strong>Urgency:</strong> </td><td>" . strip_tags($data) . "</td></tr>";
			$message .= "<tr><td><strong>URL To Change (main):</strong> </td><td>" . $data . "</td></tr>";
			// $addURLS = $_POST['addURLS'];
			// if (($addURLS) != '') {
			//     $message .= "<tr><td><strong>URL To Change (additional):</strong> </td><td>" . strip_tags($addURLS) . "</td></tr>";
			// }
			// $curText = htmlentities($_POST['curText']);           
			// if (($curText) != '') {
			//     $message .= "<tr><td><strong>CURRENT Content:</strong> </td><td>" . $curText . "</td></tr>";
			// }
			$message .= "<tr><td><strong>NEW Content:</strong> </td><td>" . htmlentities($data) . "</td></tr>";
			$message .= "</table>";
			$message .= "</body></html>";
			
			
			
			
			//  MAKE SURE THE "FROM" EMAIL ADDRESS DOESN'T HAVE ANY NASTY STUFF IN IT
			
			$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i"; 
            if (preg_match($pattern, trim(strip_tags($sendtoemail)))) { 
                $cleanedFrom = trim(strip_tags($sendtoemail)); 
            } else { 
                return "The email address you entered was invalid. Please try again!"; 
            } 
			
			
            
            
            //   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
             
            $cleanedFrom = "evolution311@gmail.com";
			$to = $sendtoemail;
			
			$subject = 'Website Change Reqest';
			
			$headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: ". strip_tags($sendtoemail) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			Log::write('SENDMAIL ::', 'Start()');
			dd($to);
            if (mail($to, $subject, $message, $headers)) {
				Log::write('SENDMAIL ::', 'success');
              echo 'Your message has been sent.';
            } else {
				Log::write('SENDMAIL ::', 'fail');
              echo 'There was a problem sending the email.';
            }

            // die("hey die!");
            return true;

        }
            
    }		// DON'T BOTHER CONTINUING TO THE HTML...
            // die();

 ?>