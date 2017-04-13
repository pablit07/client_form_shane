<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Roll extends Model {

    protected $fillable = ['timestamp', 'result'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];
    
    protected $table = 'Roll';

    public $timestamps = false;
    // Relationships

}
