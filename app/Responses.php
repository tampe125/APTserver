<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property    int     $id
 * @property    string  $client_id
 * @property    string  $module
 * @property    int     $command_id
 * @property    string  $response
 * @property    string  $created_at
 *
 */
class Responses extends Model {

    protected $fillable = [
    	'client_id',
	    'module',
	    'command_id',
	    'response',
	    'created_at'
    ];

    protected $dates = ["created_at"];

    public function __construct(array $attributes = [])
    {
	    parent::__construct($attributes);

	    $this->timestamps = false;
    }

	public function clients()
    {
        return $this->belongsTo('App\Clients');
    }
}
