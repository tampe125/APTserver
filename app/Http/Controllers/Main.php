<?php

namespace App\Http\Controllers;

use App\Commands;
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

		switch ($task)
		{
			case 'ping':
				return response()->json('');

			case 'get_job':
				$response = [];
				$ids = [];
				$commands = Commands::where('client_id', $client_id)
									->where('result', '')
									->where('sent_at', '')
									->orderBy('id', 'asc')
									->get();

				/** @var Commands $command */
				foreach ($commands as $command)
				{
					$ids[] = $command->id;
					$response[] = $command->module.'|'.$command->command;
				}

				if ($ids)
				{
					Commands::whereIn('id', $ids)->update(['sent_at' => date('Y-m-d H:M:s')]);
				}

				return response()->json($response);

			case 'report_job':
				return response()->json('');
		}

		return response()->json(['error' => 'Forbidden'], 403);
	}
}