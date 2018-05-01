<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

	public function photos() {
		return $this->belongsToMany(Photo::class)->withPivot('gender', 'type');
	}

	public function tags() {
		return $this->belongsToMany(Tag::class);
	}
}
