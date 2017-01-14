<?php

namespace App\Http\Middleware;

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
    	$whitelist_clients = array();

    	if (!in_array($request->json()->get('client_id'), $whitelist_clients))
	    {
		    abort(403, 'Forbidden');
	    }

        return $next($request);
    }
}
