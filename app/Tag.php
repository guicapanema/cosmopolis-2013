<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Tag extends Model
{
	use Rememberable;
	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

	public function posters() {
		return $this->belongsToMany(Poster::class);
	}
}
