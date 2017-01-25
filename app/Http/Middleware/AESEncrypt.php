<?php

namespace App\Http\Middleware;

use App\Clients;
use Closure;
use Illuminate\Http\JsonResponse;
use phpseclib\Crypt\AES;
use phpseclib\Crypt\Random;
use phpseclib\Crypt\RSA;

class AESEncrypt
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

	    /** @var Clients $client */
	    $client = Clients::where('client_id', $client_id)->first();

	    // Again this should never happen...
	    if (!$client || !$client->aes_key)
	    {
		    return response()->json(['error' => 'Forbidden'], 403);
	    }

	    /** @var JsonResponse $response */
	    $response = $next($request);

	    // getData() will decode it, but we need a full string that will be encrypted
	    $raw_data = json_encode($response->getData());

	    // First of all sign the data
	    $rsa = new RSA();
	    $rsa->loadKey(config('apt.priv_key'));

	    $signature = base64_encode($rsa->sign($raw_data));

	    // Then encrypt it
	    $aes = new AES();
	    $aes->setKey(base64_decode($client->aes_key));
	    $iv = Random::string($aes->getBlockLength() >> 3);
	    $aes->setIV($iv);

	    $chipertext = base64_encode($iv.$aes->encrypt($raw_data));

	    $new_response = new JsonResponse(array(
		    $chipertext,
		    $signature
	    ));

	    return $new_response;
    }
}
