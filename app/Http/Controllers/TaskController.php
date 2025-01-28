<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function create(TaskCreateRequest $request): TaskResource
    {
        $data = $request->validated();

        $user = Auth::user();

        $task = new Task($data);
        $task->user_id = $user->id;
        $task->save();

        return new TaskResource($task);
    }

    public function get(int $id): TaskResource
    {

        $user = Auth::user();

        $task = Task::query()->where("user_id", $user->id)->where("id", $id)->first();

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
}
