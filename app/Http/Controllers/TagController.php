<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['list', 'count']]);
    }

	public function rules()
	{
		$rules = [
			'text' => 'required|unique:tags|string|max:255',
		];

		return $rules;
	}

	public function merge(Request $request) {

		$new_tag = Tag::create(['text' => request('new_tag')]);

		$merged_tags = Tag::find(request('tag_ids'));

		foreach ($merged_tags as $merged_tag) {
			$new_tag->posters()->attach($merged_tag->posters()->get()->pluck('id'));
		}

		return $new_tag;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('tag.index');
    }

	/**
	 * Return a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function list(Request $request)
	{
		$tags =  Tag::withCount('posters');

		if($request->query('mostrarCartazes') !== null) {
			$tags = $tags->with('posters');
		}

		if ($request->query('busca') !== null) {
			$queryString = '%' . $request->query('busca') . '%';
			$tags = $tags->orWhere('text', 'ilike', $queryString);
		}

		if ($request->query('limite') !== null) {
			$tags = $tags->limit($request->query('limite'));
		}

		return $tags->get();

	}

	public function count(Request $request)
	{

		if($request->query('tag') !== null) {
			$count = 0;

			$queryTags = $request->query('tag');
			$tags = Tag::remember(1440)->withCount('posters');
			foreach ($queryTags as $queryTag) {
				$tags->remember(1440)->orWhereRaw('unaccent(text) ILIKE unaccent(?)', $queryTag);
			}
			$tags = $tags->get();
			foreach($tags as $tag) {
				$count += $tag->posters_count;
			}
			return $count;
		}

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

		$tag = Tag::create(request(['text']));

		return $tag;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
		return view('tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
		$this->validate(request(), $this->rules());

		$tag = $tag->update(request(['text']));

		return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
		$tag->posters()->detach();
		$tag->delete();

		return $tag;
    }
}
