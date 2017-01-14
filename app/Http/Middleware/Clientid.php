<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

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
	    $row = DB::table('clients')->where('client_id', $client_id)->first();

    	if (!$row)
	    {
		    abort(403, 'Forbidden');
	    }

        return $next($request);
    }
}
