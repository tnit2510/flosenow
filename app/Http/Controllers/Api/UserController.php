<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('role_or_permission:supervisor|create users', ['only' => 'store']);
        $this->middleware('role_or_permission:supervisor|edit users', ['only' => 'update']);
        $this->middleware('role_or_permission:supervisor|delete users', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\UserResource
     */
    public function index()
    {
        $user = User::paginate(60);

        return UserResource::collection($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @return App\Http\Resources\UserResource
     */
    public function store(UserRequest $request)
    {
        $data = User::create([
            'username' => Str::lower($request->username),
            'password' => bcrypt($request->password),
            'email'    => $request->email,
        ]);

        return new UserResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return App\Http\Resources\UserResource
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update([
            'username' => Str::lower($request->username) ?? $user->username,
            'password' => bcrypt($request->password) ?? $user->password,
            'email'    => $request->email ?? $user->email,
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
