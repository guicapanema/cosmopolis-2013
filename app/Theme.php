<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Theme extends Model
{
	use Rememberable;

	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

	public function tags() {
		return $this->belongsToMany(Tag::class);
	}
}
