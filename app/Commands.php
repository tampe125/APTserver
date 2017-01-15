<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Commands extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    public function clients()
    {
        return $this->belongsTo('App\Client');
    }
}
