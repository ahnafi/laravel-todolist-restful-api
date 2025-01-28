<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("email", "budi@gmail.com")->first();

        $task = new Task();
        $task->title = "test";
        $task->description = "description test";
        $task->user_id = $user->id;
        $task->due_date = now()->toDateTimeString();
        $task->save();
    }
}
