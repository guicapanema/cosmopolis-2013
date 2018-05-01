<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Poster;
use Illuminate\Http\Request;

class PosterController extends Controller
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

	public function rules()
	{
		$rules = [
			'text' => 'required|unique:posters|string|max:255'
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
        //
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

		$poster = Poster::create(request(['text']));

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
    public function retrieve(Poster $poster)
    {
        //
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
        //
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
		$tagIds = array_column(request('tags'), 'id');
		$poster->tags()->sync($tagIds);
    }

	/**
     * Update Photo relationship pivot table data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhotoRelationship(Request $request, Photo $photo, Poster $poster)
    {
		$poster->photos()->updateExistingPivot($photo->id, request(['gender', 'type']));

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
        //
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

	/**
     * Search all resources.
     *
     * @param  \App\Poster  $poster
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
		$queryString = '%' . $request->query('query') . '%';

        return Poster::where('text', 'ilike', $queryString)->get();
    }
}
