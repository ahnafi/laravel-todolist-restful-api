<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->first_name = "budi";
        $user->email = "budi@gmail.com";
        $user->password = Hash::make("budi12345");
        $user->token = "test";
//        $user->created_at =
        $user->save();
    }
}
