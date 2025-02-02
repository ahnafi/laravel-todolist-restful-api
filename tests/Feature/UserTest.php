<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Faker\Core\File;
use Faker\Provider\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        User::query()->delete();
        Storage::deleteDirectory("profiles");
    }

    public function testUserRegisterSuccess()
    {
        $this->post("/api/users/register", [
            "first_name" => "budi",
            "last_name" => "siregar",
            "email" => "budionosiregar@gmail.com",
            "password" => "Budiono44"
        ])->assertStatus(201)->assertJson(
            [
                "data" => [
                    "user" => [
                        "first_name" => "budi",
                        "last_name" => "siregar",
                        "email" => "budionosiregar@gmail.com",
                    ],
                    "token_type" => "Bearer"
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
            "password" => "Budi12345"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "user" => [
                    "first_name" => "budi",
                    "email" => "budi@gmail.com"
                ],
                "token_type" => "Bearer"
            ]
        ]);
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
            "password" => "Budi12345"
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => ["Email or Password is wrong"],
            ]
        ]);
    }

    public function testUserCurrentSuccess()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;
        $this->get("/api/users/current", [
            'Authorization' => "Bearer $token"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "budi",
                    "email" => "budi@gmail.com",
                    "photo" => null,
                    "last_name" => null,
                ]
            ]);
    }

    public function testUserCurrentUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->get("/api/users/current", [
//            'Authorization' => "test"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }

    function testUserCurrentInvalidToken()
    {
        $this->seed(UserSeeder::class);

        $this->get("/api/users/current", [
            'Authorization' => "test1"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }

    public function testUpdateUserNameSuccess()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->patch("/api/users/current", [
            "first_name" => "alex",
            "last_name" => "kusnandar"
        ], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "alex",
                    "last_name" => "kusnandar"
                ]
            ]);
    }

    public function testUpdateUserPhotoSuccess()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $img = UploadedFile::fake()->image("budi.jpg")->size(200);

        Storage::fake('public');

        $response = $this->patch("/api/users/current", [
            "photo" => $img
        ], [
            "Authorization" => "Bearer $token"
        ]);

        $response->assertStatus(200);

        $user = User::query()->where("email", "budi@gmail.com")->first();

        $response->assertJson([
            "data" => [
                "first_name" => "budi",
                "last_name" => null,
                "photo" => asset("/storage/" . $user->photo)
            ]
        ]);

        Storage::disk('public')->assertExists($user->photo);
    }


    public function testUpdateUserPasswordSuccess()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->patch("/api/users/current", [
            "password" => "Alexxxxxxxx69"
        ], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "budi",
                    "last_name" => null
                ]
            ]);

        $user = User::where("email", "budi@gmail.com")->first();
        self::assertTrue(Hash::check('Alexxxxxxxx69', $user->password));
    }

    public function testUpdateUserFailedUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->patch("/api/users/current", [
            "password" => "alexxxxxXXxx69"
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }

    public function testUpdateUserFailedNoBody()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->patch("/api/users/current", [], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "At least one field must be present."
                    ]
                ]
            ]);
    }

    public function testUpdateUserFailedPassword()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->patch("/api/users/current", [
            "password" => "test"
        ], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "password" => [
                        "The password field must be at least 8 characters."
                    ]
                ]
            ]);
    }

    public function testUpdateUserFailedFirstName()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->patch("/api/users/current", [
            "first_name" => "te"
        ], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "first_name" => [
                        "The first name field must be at least 3 characters."
                    ]
                ]
            ]);
    }

    public function testUpdateUserFailedInvalidSizePhoto()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $file = UploadedFile::fake()->image("hello.jpg")->size(10);

        $this->patch("/api/users/current", [
            "photo" => $file
        ], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "photo" => [
                        "The photo field must be between 25 and 2000 kilobytes.",
                    ]
                ]
            ]);
    }

    public function testUpdateUserFailedInvalidPhoto()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $file = UploadedFile::fake()->image("hello")->size(200);
//        $file = UploadedFile::fake()->create("hello.txt","200","image/jpeg");

        $this->patch("/api/users/current", [
            "photo" => $file
        ], [
            "Authorization" => "Bearer $token"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "photo" => [
                        "The photo field must be an image."
                    ]
                ]
            ]);
    }

    public function testLogoutUserSuccess()
    {
        $this->seed(UserSeeder::class);
        $user = User::query()->where("email", "budi@gmail.com")->first();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->post("/api/users/logout", [],
            [
                "Authorization" => "Bearer $token"
            ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);

        $user = User::where("email", "budi@gmail.com")->first();
        self::assertEquals(null, $user->token);
    }

    public function testLogoutUserFailedUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->post("/api/users/logout")->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);

        $this->post("/api/users/logout", [],
            [
                "Authorization" => "testss1"
            ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }

    function testToken()
    {
        $this->seed(UserSeeder::class);
        $user = User::where("email", "budi@gmail.com")->first();

        $token = $user->createToken("auth_token")->plainTextToken;

        self::assertNotNull($token);
        Log::info("token : " . $token);
    }

}
