<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegisterSuccess()
    {
        $this->post("/api/users/register", [
            "first_name" => "budi",
            "last_name" => "siregar",
            "email" => "budionosiregar@gmail.com",
            "password" => "bud10n0s1r3g4r"
        ])->assertStatus(201)->assertJson(
            [
                "data" => [
                    "first_name" => "budi",
                    "last_name" => "siregar",
                    "email" => "budionosiregar@gmail.com",
                ]
            ]
        );
    }

    public function testUserRegisterValidationError()
    {
        $this->post("/api/users/register", [
            "first_name" => "bu",
            "last_name" => "siregar",
            "email" => "budionosiregar",
            "password" => "bud10"
        ])->assertStatus(400)->assertJson(
            [
                "errors" => [
                    "first_name" => [
                        "The first name field must be at least 3 characters."
                    ],
                    "email" => [
                        "The email field must be a valid email address."
                    ],
                    "password" => [
                        "The password field must be at least 8 characters."
                    ]
                ]
            ]
        );
    }

    public function testUserRegisterEmailHasAlreadyTaken()
    {
        $this->testUserRegisterSuccess();

        $this->post("/api/users/register", [
            "first_name" => "budisanjaya",
            "email" => "budionosiregar@gmail.com",
            "password" => "bud10n0s1r3g4r"
        ])->assertStatus(400)->assertJson(
            [
                "errors" => [
                    "email" => [
                        "The email has already been taken."
                    ]
                ]
            ]
        );
    }

    public function testUserLoginSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post("/api/users/login", [
            "email" => "budi@gmail.com",
            "password" => "budi12345"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "first_name" => "budi",
                "email" => "budi@gmail.com"
            ]
        ]);

        $user = User::where("email", "budi@gmail.com")->first();
        self::assertNotNull($user->token);
    }

    public function testUserLoginEmailPasswordRequired()
    {
        $this->seed(UserSeeder::class);

        $this->post("/api/users/login", [
//            "email" => "budi@gmail.com",
//            "password" => "budi12345"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "password" => ["The password field is required."],
                "email" => ["The email field is required."]
            ]
        ]);
    }

    public function testUserLoginEmailPasswordWrong()
    {
        $this->seed(UserSeeder::class);

        $this->post("/api/users/login", [
            "email" => "budi1@gmail.com",
            "password" => "budi112345"
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => ["Email or Password is wrong"],
            ]
        ]);
    }

}
