<?php

namespace App\Http\Controllers;

use App\Photo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;
use Storage;

class PhotoController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function rules($photoCount = null)
	{
		$rules = [
			'date' => 'required|date_format:d/m/Y|before:today',
			'city' => 'required|string|max:255',
			'photographer' => 'required|string|max:255',
			'license' => 'required|string|max:255',
		];

		if($photoCount != null) {
			$rules['photos'] = 'required';
			foreach(range(0, $photoCount) as $index) {
				$rules['photos.' . $index] = 'image|mimes:jpeg,png|max:2000';
			}
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
        $photos = Photo::all();

		return view('photo.index', compact('photos'));

    }

	/**
     * Return a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return Photo::all();
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
			$pathPrefix = Storage::disk('local')->getAdapter()->getPathPrefix();
			$pathBase = 'photos/' . request('city') . '/' . request('photographer');

			$filePath = $photo->store($pathBase, 'public');

			// $parts = explode('/', $filePath);
			// $fileName = explode('.', end($parts))[0];
			// $fileExtension = explode('.', end($parts))[1];
			//
			// $bigPhoto = Image::make($photo)->widen(1000);
			// $bigPhoto->save($pathPrefix . $pathBase . '/' . $fileName . '-big.' . $fileExtension);
			//
			// $mediumPhoto = Image::make($photo)->widen(500);
			// $mediumPhoto->save($pathPrefix . $pathBase . '/' . $fileName . '-medium.' . $fileExtension);
			//
			// $smallPhoto = Image::make($photo)->widen(200);
			// $smallPhoto->save($pathPrefix . $pathBase . '/' . $fileName . '-small.' . $fileExtension);

            Photo::create([
				'path' => $filePath,
				'name' => $photo->getClientOriginalName(),
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
     * Retrieve the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function retrieve(Photo $photo)
    {
        return $photo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
		return view('photo.edit', compact('photo'));
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
		$this->validate(request(), $this->rules());

		$date = Carbon::createFromFormat('d/m/Y', request('date'));

		$photo->update([
			'name' => request('name'),
			'date' => $date,
			'city' => request('city'),
			'photographer' => request('photographer'),
			'license' => request('license'),
		]);

		return $photo;
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
