<?php

namespace Rowles\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rowles\MapperInterface;
use Rowles\Http\Controllers\Controller;
use Rowles\Models\Video;

class ProcessingController extends Controller
{
    /** @var MapperInterface */
    protected MapperInterface $mapper;

    /**
     * ProcessingController constructor.
     * @param MapperInterface $mapper
     */
    public function __construct(MapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $videos = Video::all();
        $availableTasks = array_keys($this->mapper->getAvailable());

        return view('processing.index', compact('videos', 'availableTasks'));
    }

    /**
     * @param string $namespace
     * @param string $signature
     * @param Request $request
     * @return JsonResponse
     */
    public function run(string $namespace, string $signature, Request $request): JsonResponse
    {
        $task = $this->mapper->map($namespace, $signature);

        if (!$task) {
            abort(404);
        }

        try {
            $process = $this->mapper->execute($task, $request->all());

            $request->session()->flash('success', $signature . ' task executed successfully.');

            return response()->json(['result' => $process]);
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
