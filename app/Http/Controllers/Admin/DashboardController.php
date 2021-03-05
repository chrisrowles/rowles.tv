<?php

namespace Rowles\Http\Controllers\Admin;

use Rowles\Models\Video;
use Illuminate\Http\Request;
use Rowles\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $limit = 10;
        if ($request->get('limit')) {
            $limit = (int) $request->get('limit');
        }

        if ($request->get('title') || $request->get('genre')) {
            $videos = Video::search($request->all(), ['id', 'title', 'producer'], $limit);
        } else {
            $videos = Video::paginate($limit, ['id', 'title', 'producer']);
        }

        $all = Video::all('id', 'title', 'producer');

        $request->flash();

        return view('dashboard', compact('videos', 'all'));
    }
}
