<?php

namespace Tests\Feature;

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
}
