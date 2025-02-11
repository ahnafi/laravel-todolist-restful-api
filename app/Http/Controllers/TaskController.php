<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create(TaskCreateRequest $request): TaskResource
    {
        $data = $request->validated();

        $user = $request->user();
        $task = $user->tasks()->create($data);

        return new TaskResource($task);
    }

    public function get(int $id): TaskResource
    {

        $user = Auth::user();

        $task = $user->tasks()->where("id", $id)->first();

        if (!$task) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Task not found"
                    ]
                ]
            ], 404));
        }

        return new TaskResource($task);

    }

    function update(TaskUpdateRequest $request, int $id): TaskResource
    {
        $data = $request->validated();

        $user = $request->user();
        $task = $user->tasks->find($id);

        if (!$task) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Task not found"
                    ]
                ]
            ], 404));
        }

        $task->update($data);
        return new TaskResource($task);
    }

    public function remove(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $task = $user->tasks->find($id);

        if (!$task) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Task not found"
                    ]
                ]
            ], 404));
        }

        $task->delete();

        return response()->json([
            "data" => true
        ]);
    }
}
