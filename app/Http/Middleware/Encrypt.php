<?php

namespace App\Http\Middleware;

use App\Clients;
use Closure;
use Illuminate\Http\JsonResponse;
use phpseclib\Crypt\RSA;

class Encrypt
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

    	// This should never happen, however better be safe than sorry
    	if (!$client_id)
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    $client = Clients::where('client_id', $client_id)->first();

    	// Again this should never happen...
    	if (!$client || !$client->pub_key)
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    /** @var JsonResponse $response */
	    $response = $next($request);

	    // getData() will decode it, but we need a full string that will be encoded
	    $raw_data = json_encode($response->getData());

	    // First of all sign the data
	    $rsa = new RSA();
	    $rsa->loadKey(config('apt.priv_key'));

	    $signature = base64_encode($rsa->sign($raw_data));

	    // Then encrypt it
	    $rsa = new RSA();
	    $rsa->loadKey($client->pub_key);

	    $chipertext = base64_encode($rsa->encrypt($raw_data));

	    $new_response = new JsonResponse(array(
	    	$chipertext,
	    	$signature
	    ));

	    return $response;
    }
}
