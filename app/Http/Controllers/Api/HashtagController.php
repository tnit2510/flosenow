<?php

namespace App\Http\Controllers\Api;

use Auth;
use Str;
use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Http\Requests\HashtagRequest;
use App\Http\Resources\HashtagResource;

class HashtagController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('role_or_permission:supervisor, moderator|create hashtags', ['only' => 'store']);
        $this->middleware('role_or_permission:supervisor, moderator|edit hashtags', ['only' => 'update']);
        $this->middleware('role_or_permission:supervisor, moderator|delete hashtags', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\HashtagResource
     */
    public function index()
    {
        $hashtags = Hashtag::with(['posts' => function ($q) {
            $q->paginate(30);
        }])->paginate(60);

        return HashtagResource::collection($hashtags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HashtagRequest  $request
     * @return App\Http\Resources\HashtagResource
     */
    public function store(HashtagRequest $request)
    {
        $hashtag = Hashtag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return new HashtagResource($hashtag);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hashtag  $hashtag
     * @return App\Http\Resources\HashtagResource
     */
    public function show(Hashtag $hashtag)
    {
        return new HashtagResource($hashtag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HashtagRequest  $request
     * @param  \App\Models\Hashtag  $hashtag
     * @return App\Http\Resources\HashtagResource
     */
    public function update(HashtagRequest $request, Hashtag $hashtag)
    {
        $hashtag->update([
            'name' => $request->name ?? $hashtag->name,
            'slug' => Str::slug($request->name) ?? $hashtag->slug,
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hashtag  $hashtag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hashtag $hashtag)
    {
        $hashtag->delete();

        return response()->json(['message' => 'Xoá thành công!!!']);
    }
}
