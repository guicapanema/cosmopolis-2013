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
			'date' => 'nullable|date_format:d/m/Y|before:today',
			'city' => 'nullable|string|max:255',
			'photographer' => 'nullable|string|max:255',
			'license' => 'nullable|string|max:255',
		];

		if($photoCount !== null) {
			$rules['photos'] = 'required';
			foreach(range(0, $photoCount) as $index) {
				$rules['photos.' . $index] = 'image|mimes:jpeg,png|max:100000';
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
        // $photos = Photo::all();

		return view('photo.index');

    }

	/**
     * Return a listing of the resource.
     *
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
		$photos =  Photo::withCount('posters');

		if ($request->query('busca') !== null) {
			$queryString = '%' . $request->query('busca') . '%';
			$photos = $photos->orWhere('name', 'ilike', $queryString)
							->orWhere('city', 'ilike', $queryString)
							->orWhere('photographer', 'ilike', $queryString);
		}

		if ($request->query('sortBy') !== null) {
			$sortOrder = $request->query('sortOrder') ? $request->query('sortOrder') : 'asc';
			$photos = $photos->orderBy($request->query('sortBy'), $sortOrder);
		}

		return $photos->paginate(20);
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

		$date = null;
		if(request('date') !== null) {
			$date = Carbon::createFromFormat('d/m/Y', request('date'));
		}
		$photos = array();

		foreach (request('photos') as $photo) {
			// $pathPrefix = Storage::disk('local')->getAdapter()->getPathPrefix();
			$pathBase = 'photos';

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

            $createdPhoto = Photo::create([
				'path' => $filePath,
				'name' => $photo->getClientOriginalName(),
				'date' => $date,
				'city' => request('city'),
				'photographer' => request('photographer'),
				'license' => request('license')
            ]);
			array_push($photos, $createdPhoto);
        }
		return view('photo.bulkEdit', compact('photos'));
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
     * Retrieve the specified resource's file.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function retrieveFile(Request $request, Photo $photo)
    {
		$file = Storage::disk('public')->get('/' . $photo->path);

		if ($request->query('tamanho') == 'pequeno') {
			return Image::make($file)->widen(250)->response();
		} else if ($request->query('tamanho') == 'medio') {
			return Image::make($file)->widen(500)->response();
		} else if ($request->query('tamanho') == 'grande') {
			return Image::make($file)->widen(1000)->response();
		}

		return $file;
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

		$date = null;

		if(request('date') !== null) {
			$date = Carbon::createFromFormat('d/m/Y', request('date'));
		}

		$photo->update([
			'name' => request('name'),
			'date' => $date ? $date : $photo->date,
			'city' => request('city'),
			'photographer' => request('photographer'),
			'license' => request('license'),
			'is_verified' => request('is_verified') ? request('is_verified') : $photo->is_verified,
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
		$photo->posters()->detach();
        $photo->delete();
		return $photo;
    }
}
