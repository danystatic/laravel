<?php

use Illuminate\Database\Seeder;
use App\Lotto;

class LottosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Lotto::create(array(
			'user_id' => '1',
			'lotto' => '66',
			'playnumber' => '1',
		));
        
		Lotto::create(array(
			'user_id' => '2',
			'lotto' => '62',
			'playnumber' => '1',
		));
        
		Lotto::create(array(
			'user_id' => '3',
			'lotto' => '70',
			'playnumber' => '1',
		));
    }
}
