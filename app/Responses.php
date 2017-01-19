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

    protected $fillable = [];

    protected $dates = ["created_at"];

    public static $rules = [
        // Validation rules
    ];

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
