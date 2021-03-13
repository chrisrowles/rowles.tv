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
        $availableTasks = array_keys($this->mapper->getAvailableCommands());

        return view('processing.index', compact('videos', 'availableTasks'));
    }

    /**
     * @param string $namespace
     * @param string $command
     * @param Request $request
     * @return JsonResponse
     */
    public function run(string $namespace, string $command, Request $request): JsonResponse
    {
        $task = $this->mapper->getCommand($namespace, $command);

        if (!$task) {
            abort(404);
        }

        try {
            $process = $this->mapper->execute($task, $request->all());

            $request->session()->flash('success', $command . ' task executed successfully.');

            return response()->json(['result' => $process]);
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
