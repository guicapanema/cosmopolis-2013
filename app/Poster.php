<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Poster extends Model
{
	use Rememberable;
	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

	public function photos() {
		return $this->belongsToMany(Photo::class)->withPivot('gender');
	}

	public function tags() {
		return $this->belongsToMany(Tag::class);
	}
}
