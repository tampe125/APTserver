<?php

namespace App\Http\Middleware;

use App\Clients;
use Closure;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use phpseclib\Crypt\AES;

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

	    $client_id = 'WD-WCC3F7XK9Y9P';
	    $client = Clients::where('client_id', $client_id)->first();
	    $aes_key = base64_decode($client->aes_key);

	    // Decrypt
	    $cipher = new AES();
	    $cipher->setKey($aes_key);

	    $iv = mb_substr($encrypted, 0, 16);
	    $ciphertext = mb_substr($encrypted, 16);
	    $cipher->setIV($iv);

	    $x = $cipher->decrypt($ciphertext);

        return $next($request);
    }
}
