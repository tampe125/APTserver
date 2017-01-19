<?php

use App\Clients;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
	public function run()
	{
		Clients::create([
			'client_id' => 'WD-WCC3F7XK9Y9P'
		]);
	}
}