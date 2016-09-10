<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('users')->delete();

        // User::create(array('email' => 'foo@bar.com'));

		User::create(array(

		'avatar' => 'https://lh6.googleusercontent.com/-Jg6rcjVoaSw/AAAAAAAAAAI/AAAAAAAAAFw/tH-CS4ahfak/photo.jpg?sz=50',
		'email' => 'danielvt@gmail.com',
		'btcaddress' => '1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw',
		'realsponsorid' => NULL,
		'sponsorid' => NULL,
		'password' => bcrypt('123123'),
		'parentid' => '0',
		'lft' => '1',
		'rgt' => '6',
		'ancestors' => 'NULL',
		'limitchildren' => '3',
		'luckynumber' => '66',
		'representante' => '1',
		'confirmed' => '1',
		'confirmation_code' => 'NULL',
		'remember_token' => 'LEhlypDzaESqk8rbwGQkT3UnAgTyu8LiSD85rjPIJNwbunLZrYXv9cMpNVD',
		));


		User::create(array(
		'avatar' => 'https://graph.facebook.com/v2.4/10153613928517770/picture?type=normal',
		'email' => 'danystatic@hotmail.com',
		'btcaddress' => '1MossjcXq4SKP4SVXgE4qj28G1ZdMUA2sw',
		'realsponsorid' => '1',
		'sponsorid' => '1',
		'password' => bcrypt('123123'),
		'parentid' => '1',
		'lft' => NULL,
		'rgt' => NULL,
		'ancestors' => ',1',
		'limitchildren' => '3',
		'luckynumber' => '62',
		'representante' => '1',
		'confirmed' => '1',
		'confirmation_code' => 'GRqebkNidaOwSiMwjdt3qPDUmpvbg',
		'remember_token' => 'iSCIvdcWp3Z8Afpm4R00fvTXjdlb285emuPCMY6x5nDGgNZF0ShN4f7wv1q',
		));

    }
}
