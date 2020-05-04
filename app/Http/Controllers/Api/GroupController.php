<?php

namespace App\Http\Controllers\Api;

use Auth;
use Str;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Post;
use App\Models\Hashtag;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;

class GroupController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'post', 'listPosts']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\GroupResource
     */
    public function index()
    {
        $groups = Group::simplePaginate(40);

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @return \App\Http\Resources\GroupResource
     */
    public function store(GroupRequest $request)
    {
        $group = Auth::user()->groups()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'privacy' => $request->privacy,
        ]);

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \App\Http\Resources\GroupResource
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \App\Http\Resources\GroupResource
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'privacy' => $request->privacy,
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
