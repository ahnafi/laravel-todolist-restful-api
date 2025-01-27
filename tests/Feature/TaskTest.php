<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $user = User::where("token", "test")->first();

        $this->post("/api/tasks", [
            "title" => "test todo",
            "description" => "Test Description",
            "due_date" => now()->toDateTimeString(),
        ], [
            "Authorization" => "test"
        ])->assertStatus(201)->assertJson([
            "data" => [
                "title" => "test todo",
                "description" => "Test Description",
                "due_date" => now()->toDateTimeString(),
                "status" => false,
                "user_id" => $user->id
            ]
        ]);

        Log::info(json_encode($user->tasks, JSON_PRETTY_PRINT));

        self::assertCount(1, $user->tasks);
    }

    public function testCreateTaskFailedTittleRequired()
    {
        $this->seed(UserSeeder::class);

        $user = User::where("token", "test")->first();

        $this->post("/api/tasks", [

            "description" => "Test Description",
            "due_date" => now()->toDateTimeString()
        ], [
            "Authorization" => "test"
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

        $user = User::where("token", "test")->first();

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

        $user = User::where("token", "test")->first();

        $this->post("/api/tasks", [
            "title" => "a",
            "description" => "Test Description",
            "due_date" => "hello"
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "due_date" => [
                    "The due date field must match the format Y-m-d H:i:s."
                ]
            ]
        ]);
    }
}
