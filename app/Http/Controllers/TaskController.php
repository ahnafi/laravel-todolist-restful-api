<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function create(TaskCreateRequest $request): TaskResource
    {
        $data = $request->validated();

        Log::info("data due_date : " . $request["due_date"]);

        $user = Auth::user();

        $task = new Task($data);
        $task->user_id = $user->id;
        $task->save();

        return new TaskResource($task);
    }
}
