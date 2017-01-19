<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property    int     $id
 * @property    string  $module
 * @property    string  $command
 *
 */
class Commands extends Model {

    protected $fillable = [];

    protected $dates = [
    	'created_at',
	    'sent_at'
    ];

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
        return $this->belongsTo('App\Client');
    }
}
