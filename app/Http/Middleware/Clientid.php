<?php

namespace App\Http\Middleware;

use App\Clients;
use Closure;

class Clientid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $client_id = $request->json()->get('client_id');
	    $row = Clients::where('client_id', $client_id)->first();

		if (!$row)
	    {
	    	return response()->json(['error' => 'Forbidden'], 403);
	    }

        return $next($request);
    }
}
