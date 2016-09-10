<?php

use Illuminate\Database\Seeder;
use App\News;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('news')->delete();

        // User::create(array('email' => 'foo@bar.com'));

		News::create(array(
			'title' => 'First Good News',
			'content' =>'Lorem Ipsum Lorum Datakadina kadjfñaskdf'
		));
		News::create(array(
			'title' => 'Bitcoin Vive',
			'content' =>'Lorem Ipsum Lorum Datakadina kadjfñaskdf'
		));
		News::create(array(
			'title' => 'Juntos Hacemos',
			'content' =>'Lorem Ipsum Lorum Datakadina kadjfñaskdf'
		));
    }
}
