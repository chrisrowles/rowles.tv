<?php

namespace Rowles\Http\Controllers;

use Rowles\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $videos = Video::paginate(24);

        return view('video.index', compact('videos'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $videos = Video::search($request->all());

        $request->flash();

        return view('video.index', compact('videos'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function watch($id)
    {
        $video = Video::where('id', $id)->first();
        $related = Video::limit(6)->get();

        if (!$video) {
            abort(404);
        }

        return view('video.watch', compact('video', 'related'));
    }
}
