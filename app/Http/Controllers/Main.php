<?php

namespace App\Http\Controllers;

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
		$task = strtolower($request->json()->get('task'));

		switch ($task)
		{
			case 'ping':
				return response()->json('');
			case 'get_job':
				break;
			case 'report_job':
				break;
		}

		return response()->json(['error' => 'Forbidden'], 403);
	}
}