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
        $this->middleware('auth', ['except' => ['list', 'listPhotographers', 'retrieve', 'retrieveFile']]);
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
		$perPage = 20;

		if ($request->query('per_page') !== null) {
			$perPage = $request->query('per_page');
		}

		// Complex searches require more info
		if (($request->query('busca') !== null) || ($request->query('tipo') !== null) || ($request->query('tag') !== null)) {
			$photos = $photos->with('posters');
		}

		if ($request->query('busca') !== null) {
			$queryString = '%' . $request->query('busca') . '%';
			$photos = $photos->orWhere('name', 'ilike', $queryString)
				->orWhere('city', 'ilike', $queryString)
				->orWhere('photographer', 'ilike', $queryString)
				->orWhereHas('posters', function($poster) use ($queryString) {
					$poster->with('tags')->whereRaw('unaccent(text) ILIKE unaccent(?)', $queryString)
						->orWhereHas('tags', function($tag) use ($queryString) {
							$tag->whereRaw('unaccent(text) ILIKE unaccent(?)', $queryString);
						});
				});
		}

		if ($request->query('tipo') !== null) {
			$queryType = $request->query('tipo');
			$photos = $photos->whereHas('posters', function($poster) use ($queryType) {
				$poster->whereIn('type', $queryType);
			});
		}

		if ($request->query('tag') !== null) {
			$queryTag = $request->query('tag');
			$photos = $photos->whereHas('posters', function($poster) use ($queryTag) {
				$poster->with('tags')->whereHas('tags', function($tag) use ($queryTag) {
						$tag->whereIn('text', $queryTag);
					});
			});
		}

		if ($request->query('cidade') !== null) {
			$queryCity = $request->query('cidade');
			$photos = $photos->whereIn('city', $queryCity);
		}


		if ($request->query('sortBy') !== null) {
			$sortOrder = $request->query('sortOrder') ? $request->query('sortOrder') : 'asc';
			$photos = $photos->orderBy($request->query('sortBy'), $sortOrder);
		}

		if ($request->query('groupBy') !== null) {
			$photos = $photos->select($request->query('groupBy'))->groupBy($request->query('groupBy'));
		}

		return $photos->paginate($perPage);
    }

	/**
	 * Return a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function listPhotographers(Request $request)
	{
		return Photo::select('photographer')->whereNotNull('photographer')->distinct('photographer')->get();
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
        return $photo::with('posters')->find($photo->id);
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



		$image = Image::cache(function($image) use($request, $file) {

			$image->make($file);
			$width = 0;

			if ($request->query('tamanho') == 'pequeno') {
				$width = 250;
			} else if ($request->query('tamanho') == 'medio') {
				$width = 500;
			} else if ($request->query('tamanho') == 'grande') {
				$width = 1000;
			}

			$image->widen($width);

			if($request->query('recortar') !== null) {
				$ratio = 2/3;
				$image->crop($width, floor($width * $ratio));
			}

		}, 1440, true);

		return $image->response()->header('Cache-Control', 'max-age=86400, public');

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
			'is_verified' => request('is_verified') !== null ? request('is_verified') : $photo->is_verified,
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
