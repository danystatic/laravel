<?php

use Illuminate\Database\Seeder;
use App\Sysvar;

class SysvarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('sysvars')->delete();

        // User::create(array('email' => 'foo@bar.com'));

		Sysvar::create(array(
			'playnumber' => 1,
			'disabled' => 0,
			'luckynumber' => NULL,
			'minimumpayment' => 0
		));
    }
}
