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

	public function consolidateTags() {
		$tags = Tag::all();


		foreach ($tags as $tag) {

			// Make all tags lowercase
			$tag->update(['text' => strtolower($tag->text)]);

			// Merge repeated tags
			if ($tags->where('text', $tag->text)->count() > 1) {
				$referenceTag = $tags->where('text', $tag->text)->first();
				$repeatedTags = $tags->where('text', $tag->text)->slice(1);

				foreach ($repeatedTags as $repeatedTag) {
					$referenceTag->posters()->syncWithoutDetaching($repeatedTag->posters()->get());
					$repeatedTag->posters()->detach();
					$repeatedTag->delete();
				}

			}

		}

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
			$tags = $tags->orWhere('text', 'like', $queryString);
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
				$tags->remember(1440)->orWhereRaw('text LIKE ?', $queryTag);
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
