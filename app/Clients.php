<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model {

    protected $fillable = ["client_id"];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    public function commands()
    {
        return $this->hasMany('App\Command');
    }
}
