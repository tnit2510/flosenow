<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Title;
use App\Http\Resources\TitleResource;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('role_or_permission:supervisor|create titles', ['only' => 'store']);
        $this->middleware('role_or_permission:supervisor|edit titles', ['only' => 'update']);
        $this->middleware('role_or_permission:supervisor|delete titles', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\TitleResource
     */
    public function index()
    {
        $titles = Title::simplePaginate(60);

        return TitleResource::collection($titles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TitleResource
     */
    public function store(Request $request)
    {
        $title = Title::create([
            'label' => $request->label,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return new TitleResource($title);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Title  $title
     * @return \App\Http\Resources\TitleResource
     */
    public function show(Title $title)
    {
        return new TitleResource($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Title  $title
     * @return \App\Http\Resources\TitleResource
     */
    public function update(Request $request, Title $title)
    {
        $title->update([
            'label' => $request->label,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Title  $title
     */
    public function destroy(Title $title)
    {
        $title->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
