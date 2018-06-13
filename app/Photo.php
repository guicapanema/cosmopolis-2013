<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Photo extends Model
{
	use Rememberable;
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

	public function posters() {
		return $this->belongsToMany(Poster::class)->withPivot('gender');
	}

}
