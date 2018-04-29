<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
	public function photos() {
		return $this->belongsToMany(Photo::class);
	}
}
