<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Clients
 *
 * @property    int     $id
 * @property    string  $client_id
 * @property    string  $priv_key
 * @property    string  $pub_key
 * @property    string  $aes_key
 *
 * @package App
 */
class Clients extends Model {

    protected $fillable = ["client_id"];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    public function __construct(array $attributes = [])
    {
	    parent::__construct($attributes);

	    $this->timestamps = false;
    }

	public function commands()
    {
        return $this->hasMany('App\Commands');
    }
}
