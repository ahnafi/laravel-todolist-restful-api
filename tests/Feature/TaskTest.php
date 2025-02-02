<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Database\Seeders\TaskSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TaskTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Task::query()->forceDelete();
        User::query()->delete();
    }

    public function testCreateTaskSuccess()
    {
        $this->seed(UserSeeder::class);

        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->post("/api/tasks", [
            "title" => "test todo",
            "description" => "Test Description",
            "due_date" => now()->toDateTimeString(),
        ], [
            "Authorization" => "Bearer $token"
        ])->assertStatus(201)->assertJson([
            "data" => [
                "title" => "test todo",
                "description" => "Test Description",
                "status" => false,
                "user_id" => $user->id
            ]
        ]);

        Log::info(json_encode($user->load("tasks"), JSON_PRETTY_PRINT));

        self::assertCount(1, $user->tasks);
    }

    public function testCreateTaskFailedTittleRequired()
    {
        $this->seed(UserSeeder::class);

        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->post("/api/tasks", [

            "description" => "Test Description",
            "due_date" => now()->toDateTimeString()
        ], [
            "Authorization" => "Bearer $token"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "title" => [
                    "The title field is required."
                ]
            ]
        ]);
    }

    public function testCreateTaskFailedUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->post("/api/tasks", [
            "title" => "a",
            "description" => "Test Description",
            "due_date" => now()->toDateTimeString()
        ], [
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => [
                    "Unauthorized"
                ]
            ]
        ]);
    }

    public function testCreateTaskFailedDatetime()
    {
        $this->seed(UserSeeder::class);

        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->post("/api/tasks", [
            "title" => "a",
            "description" => "Test Description",
            "due_date" => "hello"
        ], [
            "Authorization" => "Bearer $token"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "due_date" => [
                    "The due date field must match the format Y-m-d H:i:s."
                ]
            ]
        ]);
    }

    function testGetTaskSuccess()
    {
        $this->seed([UserSeeder::class, TaskSeeder::class]);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $task = Task::query()->where("title", "test")->first();

        $this->get("/api/tasks/$task->id", [
            "Authorization" => "Bearer $token"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "title" => "test",
                "description" => "description test",
            ]
        ]);
    }

    function testGetTaskUnauthorized()
    {
        $this->seed([UserSeeder::class, TaskSeeder::class]);

        $task = Task::query()->where("title", "test")->first();

        $this->get("/api/tasks/$task->id")->assertStatus(401)->assertJson([
            "errors" => [
                "message" => [
                    "Unauthorized"
                ]
            ]
        ]);
    }

    function testGetTaskNotFound()
    {
        $this->seed([UserSeeder::class, TaskSeeder::class]);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $task = Task::query()->where("title", "test")->first();

        $this->get("/api/tasks/" . $task->id + 100, [
            "Authorization" => "Bearer $token"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "Task not found"
                ]
            ]
        ]);
    }
}
