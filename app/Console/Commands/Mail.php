<?php
namespace App\Console\Commands;

use App\Clients;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail as SendMail;
use PhpImap\Mailbox;

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
		$this->info('Trying to connect to the email address');
		// We're not going to use SSL since it requires PHP being compiled with SSL, something that not all hosts do
		$mailbox = new Mailbox('{'.config('imap.imap.host').':143/imap}INBOX', config('imap.imap.user'), config('imap.imap.password'));

		try
		{
			$ids = $mailbox->searchMailbox('ALL');
		}
		catch (\Exception $e)
		{
			$this->error('Could not connect to email inbox. Quitting.');
			return;
		}

		foreach ($ids as $id)
		{
			$mail = $mailbox->getMail($id);

			$subject = base64_decode($mail->subject);

			// That's an email sent by the server, skip it
			if (substr($subject, 0, 2) == 'R:')
			{
				continue;
			}

			// If I'm here, that's a junk email or an email that I must process. In any case, I'll have to delete it once I'm done
			//$mailbox->deleteMail($id);
			$body = $mail->textPlain;

			$client = Clients::where('client_id', $subject)->first();

			// Invalid client
			if (!$client)
			{
				$this->warn(sprintf('Email with subject %s points to an invalid client id', $subject));
				continue;
			}

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

					/** @var \Illuminate\Http\JsonResponse $response */
					$response = $app->handle($request);
					break;
				// Command execution
				case 3:
					$request = Request::create('/', 'POST', [$parts[0], $parts[1], $parts[2]]);
					$app = app();

					/** @var \Illuminate\Http\JsonResponse $response */
					$response = $app->handle($request);
					break;
				// Unhandled case, let's stop here and log the exception
				default:
					$this->warn('Email with invalid body content');
					return;
			}

			$data = $response->getData();

			$contents = implode("\n~~~~~~~~~~~~~~~~~~~~\n", $data);
			$contents .= "\n~~~~~~~~~~~~~~~~~~~~\n";

			SendMail::raw($contents, function($msg) use($subject) {
				$msg->to([env('MAIL_FROM_ADDRESS')]);
				$msg->from([env('MAIL_FROM_ADDRESS')]);
				$msg->subject('R:'.base64_encode($subject));
			});
		}
	}
}