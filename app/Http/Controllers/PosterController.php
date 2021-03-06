<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Poster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PosterController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['list', 'listTypes', 'filterByPhoto']]);
    }

	public function rules()
	{
		$rules = [
			'text' => 'required|string|max:255',
			'type' => 'nullable|string|max:255'
		];

		return $rules;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('poster.index');
    }

	/**
	 * Return a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function list(Request $request)
	{
		$posters =  Poster::withCount('photos');
		$perPage = 20;

		if ($request->query('per_page') !== null) {
			$perPage = $request->query('per_page');
		}

		if($request->query('mostrarFotos') !== null) {
			$posters = $posters->with('photos');
		}

		if($request->query('mostrarTags') !== null) {
			$posters = $posters->with('tags');
		}

		if($request->query('esconderVazios') !== null) {
			$posters = $posters->has('photos');
		}

		if ($request->query('busca') !== null) {
			$queryString = '%' . $request->query('busca') . '%';

			$posters = $posters->where(function($query) use ($queryString) {
				$query->whereRaw('unaccent(text) ILIKE unaccent(?)', $queryString)
					->orWhere('type', 'ilike', $queryString);
			});
		}

		if ($request->query('cidade') !== null) {
			$queryCity = $request->query('cidade');
			$posters = $posters->whereHas('photos', function($photo) use ($queryCity) {
				$photo->whereIn('city', $queryCity);
			});
		}

		if ($request->query('data') !== null) {
			$queryDate = $request->query('data');
			$posters = $posters->whereHas('photos', function($photo) use ($queryDate) {
				$photo->whereIn('date', $queryDate);
			});
		}

		if ($request->query('tag') !== null) {
			$queryTags = $request->query('tag');
			$posters = $posters->whereHas('tags', function($tag) use ($queryTags) {
				$tag->whereRaw('unaccent(text) ILIKE unaccent(?)', $queryTags[0]);
				foreach ($queryTags as $queryTag) {
					$tag->orWhereRaw('unaccent(text) ILIKE unaccent(?)', $queryTag);
				}
			});
		}

		if ($request->query('tipo') !== null) {
			$queryType = $request->query('tipo');
			$posters = $posters->whereIn('type', $queryType);
		}

		if ($request->query('limite') !== null) {
			$posters = $posters->limit($request->query('limite'));
		}

		if ($request->query('sortBy') !== null) {
			$sortOrder = $request->query('sortOrder') ? $request->query('sortOrder') : 'asc';
			$posters = $posters->orderBy($request->query('sortBy'), $sortOrder);
			$posters = $posters->orderBy('id', $sortOrder);
		}

		if ($request->query('groupBy') !== null) {
			$posters = $posters->select($request->query('groupBy'))->groupBy($request->query('groupBy'));
		}

		return $posters->paginate($perPage);

	}

	public function listTypes(Request $request) {
		$types = Poster::remember(1440)->whereNotNull('type')
					->select('type', DB::raw('count(*) as total'))
					->groupBy('type')
					->orderBy('type')
					->get();
		return $types;
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate(request(), $this->rules());

		$poster = Poster::create(request(['text', 'type']));

		return $poster;
    }

	/**
     * Attach existing resource to Photo relationship.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function attachToPhoto(Request $request, Photo $photo)
    {

		$poster = Poster::find(request('id'));

		if (!$poster->photos->contains($photo->id)) {
			$poster->photos()->attach($photo->id);
		}

		return $photo->posters()->with('tags')->find($poster->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function show(Poster $poster)
    {
        //
    }

	/**
     * Retrieve the specified resource.
     *
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function retrieve(Request $request, Poster $poster)
    {
		return Poster::with('photos', 'tags')->find($poster->id);
    }

	/**
     * Filter the specified resource.
     *
	 * @param  \Illuminate\Http\Request  $request
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function filterByPhoto(Request $request, Photo $photo)
    {
		if($request->query('showTags') == 'true') {
			return $photo->posters()->with('tags')->get();
		}

        return $photo->posters()->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function edit(Poster $poster)
    {
        return view('poster.edit', compact('poster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poster $poster)
    {
		if(request('tags') !== null) {
			$tagIds = array_column(request('tags'), 'id');
			$poster->tags()->sync($tagIds);
		}
		if(request('text') !== null) {
			$poster->update(request(['text', 'type']));
		}

		return $poster;

    }

	/**
     * Update Photo relationship pivot table data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhotoRelationship(Request $request, Photo $photo, Poster $poster)
    {
		$poster->photos()->updateExistingPivot($photo->id, request(['gender']));

		return $poster;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poster $poster)
    {
        $poster->photos()->detach();
		$poster->delete();

		return $poster;
    }

	/**
     * Attach existing resource to Photo relationship.
     *
     * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Photo  $photo
	 * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function detachFromPhoto(Request $request, Photo $photo, Poster $poster)
    {

		$poster->photos()->detach($photo->id);

		return $poster;
    }

}
