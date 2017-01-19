<?php namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany('App\Command');
    }
}
