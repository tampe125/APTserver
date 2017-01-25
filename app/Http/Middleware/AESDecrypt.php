<?php

namespace App\Http\Middleware;

use App\Clients;
use Closure;
use phpseclib\Crypt\AES;
use phpseclib\Crypt\RSA;

class AESDecrypt
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
	    if (!is_array($data) || ! isset($data[0]) || !isset($data[1]) || !isset($data[2]))
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    $signature = base64_decode($data[1]);
	    $encrypted = base64_decode($data[0]);
	    $client_id = base64_decode($data[2]);

	    if (!$signature || !$encrypted || !$client_id)
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    $rsa = new RSA();
	    $rsa->loadKey($priv_key);

	    $client_id = $rsa->decrypt($client_id);

	    $client = Clients::where('client_id', $client_id)->first();
	    $aes_key = base64_decode($client->aes_key);

	    // Decrypt
	    $cipher = new AES();
	    $cipher->setKey($aes_key);

	    $plaintext = $cipher->decrypt($encrypted);
	    $plaintext = substr($plaintext, 16);
	    $decoded   = json_decode($plaintext, true);

	    // Check if the decoded data has all the things we need
	    if (!$decoded || !isset($decoded['client_id']) || !$decoded['client_id'])
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    if ($decoded['client_id'] != $client_id)
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
