<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// PUT will handle exchanging the AES key using RSA
$app->put('/', [
	'middleware' => ['RSADecrypt', 'RSAEncrypt'],
	'uses'       => 'Main@handle'
]);


// POST will handle all other tasks where data is encrypted using AES
$app->post('/', [
	'middleware' => ['AESDecrypt', 'AESEncrypt'],
	'uses'       => 'Main@handle'
]);

$app->get('/', function(){
	return 'OK';
});