<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class Mail extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Interacts with remote mail server';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->line('Hello world');
	}
}