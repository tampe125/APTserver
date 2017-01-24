<?php

namespace App\Http\Middleware;

use App\Clients;
use Closure;
use phpseclib\Crypt\RSA;


class RSADecrypt
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
    	$priv_key = config('apt.priv_key');

    	$data = $request->input();

    	// If any required param is missing, stop here
    	if (!is_array($data) || ! isset($data[0]) || !isset($data[1]))
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    $signature = base64_decode($data[1]);
	    $encrypted = base64_decode($data[0]);

	    if (!$signature || !$encrypted)
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    // Decrypt
	    $rsa = new RSA();
	    $rsa->loadKey($priv_key);

	    $plaintext = $rsa->decrypt($encrypted);
	    $decoded = json_decode($plaintext, true);

	    // Check if the decoded data has all the things we need
	    if (!$decoded || !isset($decoded['client_id']) || !$decoded['client_id'])
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    $client = Clients::where('client_id', $decoded['client_id'])->first();

	    // No registered client? Stop here
	    if (!$client || !$client->pub_key)
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    // Verify sign
	    $rsa = new RSA();
	    $rsa->loadKey($client->pub_key);

	    if (!$rsa->verify($plaintext, $signature))
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    foreach ($decoded as $key => $value)
	    {
		    $request->json()->set($key, $value);
	    }

        return $next($request);
    }
}
