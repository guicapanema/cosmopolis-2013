<?php

namespace App\Http\Controllers;

use App\Photo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

	public function rules($photoCount)
	{
		$rules = [
			'date' => 'required|date_format:d/m/Y|before:today',
			'city' => 'required|string|max:255',
			'photographer' => 'required|string|max:255',
			'license' => 'required|string|max:255',
			'photos' => 'required'
		];
		foreach(range(0, $photoCount) as $index) {
			$rules['photos.' . $index] = 'image|mimes:jpeg,png|max:2000';
		}

		return $rules;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('photo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$photoCount = request('photos') ? count(request('photos')) : 0;
		$this->validate(request(), $this->rules($photoCount));

		$date = Carbon::createFromFormat('d/m/Y', request('date'));

		foreach (request('photos') as $photo) {
            $path = $photo->store('photos/' . request('city') . '/' . request('photographer'));
            Photo::create([
				'path' => $path,
				'date' => $date,
				'city' => request('city'),
				'photographer' => request('photographer'),
				'license' => request('license')
            ]);
        }
		return view('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
