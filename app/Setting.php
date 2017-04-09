<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    protected $fillable = ['name', 'value'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    protected $table = 'Setting';

    public $timestamps = false;

    // Relationships

}
