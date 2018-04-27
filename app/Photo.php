<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
	protected $dates = [
        'date'
    ];

}
