<?php
namespace App\Console\Commands;

use Illuminate\Http\Request;
use PhpImap\Mailbox;
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
	 * @return void
	 */
	public function handle()
	{
		// We're not going to use SSL since it requires PHP being compiled with SSL, something that not all hosts do
		$mailbox = new Mailbox('{'.config('mail.imap.host').':143/imap}INBOX', config('mail.imap.user'), config('mail.imap.password'));
		$ids     = $mailbox->searchMailbox('ALL');

		foreach ($ids as $id)
		{
			//$mailbox->deleteMail($id);

			$mail = $mailbox->getMail($id);

			$subject = base64_decode($mail->subject);
			$body    = $mail->textPlain;

			$parts = explode('~~~~~~~~~~~~~~~~~~~~', $body);

			if (!$parts)
			{
				continue;
			}

			$count = substr_count($body, '~~~~~~~~~~~~~~~~~~~~');

			switch ($count)
			{
				// Ping request
				case 2:
					$request = Request::create('/', 'PUT', [$parts[0], $parts[1]]);
					$app = app();
					$app->handle($request);
					break;
				// Command execution
				case 3:
					$request = Request::create('/', 'POST', [$parts[0], $parts[1], $parts[2]]);
					$app = app();
					$app->handle($request);
					break;
			}
		}

		$mailbox->expungeDeletedMails();
	}
}