<?php

namespace App\Http\Controllers\Api;

use Auth;
use Str;
use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Http\Resources\BookmarkResource;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\BookmarkResource
     */
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()->simplePaginate(40);

        return BookmarkResource::collection($bookmarks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\BookmarkResource
     */
    public function store(Request $request)
    {
        $bookmark = Auth::user()->bookmarks()->create([
            'name' => Str::title($request->name),
            'slug' => Str::slug($request->name),
        ]);

        return new BookmarkResource($bookmark);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \App\Http\Resources\BookmarkResource
     */
    public function show(Bookmark $bookmark)
    {
        $data = Auth::user()->bookmarks()->with(['posts' => function ($q) {
            $q->simplePaginate(40);
        }])->simplePaginate(40);

        return new BookmarkResource($bookmark);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \App\Http\Resources\BookmarkResource
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        Auth::user()->bookmarks()->update([
            'name' => Str::title($request->name),
            'slug' => Str::slug($request->name),
        ]);

        return response()->json(['message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();

        return response()->json(['message' => 'Xóa thành công']);
    }
}
