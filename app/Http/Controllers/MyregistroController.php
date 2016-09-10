<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
////use Log;
use App\User;

use Illuminate\Support\Facades\Auth;
use App\Lotto;
use App;
use DB;
use App\Blockchain;
use App\Helpers\Viewhelper;
use App\Helpers\Sendmail;


class MyregistroController extends Controller
{
    //
    public function index(){
    	echo "register contr";
    }

      public function registro(Request $request)
    {

        //dd(Input::all());


        //  $messages = [
        //     'required'    => 'Es requerido y con al menos 4 caracteres.',
        //     'email'    => 'Su :attribute se requiere.',
        //     'sponsor' => 'Quien lo invito a unirse a este sitio? :attribute .',
        //     'password'      => 'La :attribute debe de ser al menos 4 caracteres.',
        // ];

 // create custom validation messages ------------------


         $rules = array(
               // 'username' => 'required|min:4',
                'email' => 'required|email|unique:users',
                'sponsor' => 'exists:users,email',
                'password' => 'required|min:5'
                );
    

    $messages = array(
        'username.required' => 'El :attribute si se requiere.',
        'email.required' => 'El :attribute es muy muy importante.',
        'username.min' => 'El :attribute debe tener 4 caracteres.',
        'password.min' => 'El :attribute debe tener 5 caracteres.',
        'exists'  => 'El Sponsor | Patrocinador | Invitador debe de existir.',
        'min.username' => 'El minimo son :others caracteres!!',
        'min.password' => 'El minimo son :others caracteres!!',
        'unique' => 'Este usuario ya existe!'
    );

         $validator = Validator::make($request->all(),$rules, $messages);


        //   $rules = array(
        //     'username' => 'required|min:4|unique:users',
        //     'email' => 'required|email|unique:users',
        //     'sponsor' => 'exists:users,email',
        //     'password' => 'required|min:4'
        // );

        //  dd($validator);

        if ($validator->fails()) {
            return redirect('/auth/register')
                        ->withErrors($validator)
                        ->withInput();
        }


        DB::transaction(function () {

        // echo var_dump($validator);

       // dd("After Validation");
        # Estos son los 4 inputs requeridos para esta importante parte que es el registro
        //Log::info('RegisterController@register After Validation');
        $username = Input::get('username');
        $email = Input::get('email');
        $password = Input::get('password');
        $sponsor = Input::get('sponsor');

        //Log::info('get::register Verify Success');
        # Check for Sponsor , for Parent and for limit


        $sponsor = User::where('email','=',$sponsor)->first();
        $countchild = User::where('sponsorid','=',$sponsor->id)->count();
        // dd($countchild);
        # Agregar limite de hijos, pero que si ha llegado al limite lo deje agregarlo, 
        # pero queda el sponsor pendiente
        // if($countchild >= $sponsor->limitchildren)
        // {
        //      //Log::info('get::register Children Limit Reached');
        //      Session::flash('msg', '<h1 style="color:red">Esta persona ya invito a muchos otros. Ya no puede invitar a mas. Dile!</h1>');
        //      return Redirect::to('/');
        // }

        # Confirmation Code
        $confirmation_code = str_random(30);


            $luckynumber = rand(1,100);
            if($luckynumber == 100){
                $luckynumber = 00;
            }


            # Regiser Temporary User
            if($countchild >= 3){
                //Log::info('Register Temporary User');
                $user = new User;
                $user->username = $username;
                $user->email = $email; 
                $user->password = Hash::make($password);
                $user->sponsor = $sponsor->username;
                $user->sponsorid = $sponsor->id;
                $user->realsponsorid = $sponsor->id;
                $user->passwordstring =$password;
                $user->btcaddress = 'pending';
                $user->representante = 1;
                $user->luckynumber = $luckynumber;
                $user->confirmation_code = $confirmation_code;
                $user->limitchildren = $sponsor->limitchildren;
                $user->save();


                    // Sendmail::verify($email, $confirmation_code, $username, $sponsor->email);
                    //Log::info('Email sent successful');
                    Session::flash('msg', '<span style="color:red">Felicidades!!   <br><center>' . $username .'</center><br> Comunicate con tu sponsor, el require colocarte. </span>');
                    Viewhelper::rebuild_shortmlm_tree('users',1,1);
                    return redirect()->route('homepage');

            }


            # Register Users
            $ancestors = explode(',', $sponsor->ancestors);
            $ancestors[] = $sponsor->id;
            $ancestors = implode(',', $ancestors);


            $user = new User;
            $user->username = $username;
            $user->email = $email; 
            $user->password = Hash::make($password);
            $user->sponsor = $sponsor->username;
            $user->parent = $sponsor->username;
            $user->sponsorid = $sponsor->id;
            $user->realsponsorid = $sponsor->id;
            $user->parentid = $sponsor->id;
            $user->passwordstring =$password;
            $user->representante = 1;
            $user->btcaddress = 'pending';
            $user->confirmation_code = $confirmation_code;
            $user->parentid = $sponsor->id;
            $user->luckynumber = $luckynumber;
            $user->ancestors = $ancestors;  
            $user->limitchildren = $sponsor->limitchildren;
            $user->save();

        
            //Sendmail::verify($email, $confirmation_code, $username, $sponsor->email);
            //Log::info('Email sent successful');
            Viewhelper::rebuild_shortmlm_tree('users',1,1);

            #save/Backup DB 

            Auth::login($user, true);
            // echo shell_exec('sh /var/www/html/dinerodigital/app/libraries/MySQLdump.sh');

            //Log::info('register successful');
        });
            return redirect('home')->with('msg', "<p class='well' style='margin-right:1em;color:orange;'>Bienvenido! <br>Ingresa con tus datos!!</p>");
    }

}
