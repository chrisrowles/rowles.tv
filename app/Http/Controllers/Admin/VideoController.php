<?php

namespace Rowles\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Rowles\Http\Requests\UpdateVideoRequest;
use Rowles\Models\Video;
use Illuminate\Http\Request;
use Rowles\Http\Controllers\Controller;

class VideoController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $limit = 24;
        if ($request->get('limit')) {
            $limit = (int) $request->get('limit');
        }

        if ($request->get('title') || $request->get('genre') || $request->get('producer')) {
            $videos = Video::search($request->all(), [], $limit);
        } else {
            $videos = Video::paginate($limit);
        }

        $all = Video::all();

        $request->flash();

        return view('admin.video-management', compact('videos', 'all'));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $video = Video::with('metadata')->where('id', $id)->first();

        if (!$video) {
            abort(404);
        }

        return response()->json($video);
    }

    /**
     * @param $id
     * @param UpdateVideoRequest $request
     * @return JsonResponse
     */
    public function update($id, UpdateVideoRequest $request): JsonResponse
    {
        $video = Video::where('id', $id)->first();

        if (!$video) {
            abort(400);
        }

        $validated = $request->validated();

        $video->title = $validated['title'];
        $video->producer = $validated['producer'];
        $video->genre = $validated['genre'];
        $video->description = $validated['description'];

        if (!$video->save()) {
            abort(500);
        }

        return response()->json($video);
    }
}
