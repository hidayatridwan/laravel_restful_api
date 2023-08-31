<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'ridwan',
            'password' => '4377',
            'name' => 'Ridwan Nurul Hidayat'
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'username' => 'ridwan',
                    'name' => 'Ridwan Nurul Hidayat'
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => ['The username field is required.'],
                    'password' => ['The password field is required.'],
                    'name' => ['The name field is required.']
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();

        $this->post('/api/users', [
            'username' => 'ridwan',
            'password' => '4377',
            'name' => 'Ridwan Nurul Hidayat'
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => ['Username already registered']
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            'username' => 'ridwan',
            'password' => '4377'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'ridwan',
                    'name' => 'Ridwan Nurul Hidayat'
                ]
            ]);

        $user = User::where('username', 'ridwan')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailed()
    {
        $this->post('/api/users/login', [
            'username' => 'ridwan',
            'password' => '4377'
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Username or password wrong']
                ]
            ]);
    }

    public function testLoginPasswordWrong()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            'username' => 'ridwan',
            'password' => 'wrong'
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Username or password wrong']
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'ridwan',
                    'name' => 'Ridwan Nurul Hidayat'
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [
            'Authorization' => 'wrong'
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed(UserSeeder::class);

        $oldUser = User::where('username', 'ridwan')->first();

        $this->patch('/api/users/current', [
            'password' => 'baru'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'ridwan',
                    'name' => 'Ridwan Nurul Hidayat'
                ]
            ]);

        $newUser = User::where('username', 'ridwan')->first();

        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed(UserSeeder::class);

        $oldUser = User::where('username', 'ridwan')->first();

        $this->patch('/api/users/current', [
            'name' => 'Ridwan Baru'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'ridwan',
                    'name' => 'Ridwan Baru'
                ]
            ]);

        $newUser = User::where('username', 'ridwan')->first();

        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdateFailed()
    {
        $this->seed(UserSeeder::class);

        $this->patch('/api/users/current', [
            'name' => 'Ridwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan BaruRidwan Baru'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field must not be greater than 100 characters.']
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);

        $user = User::where('username', 'ridwan')->first();
        self::assertNull($user->token);
    }

    public function testLogoutFailed()
    {
        $this->seed(UserSeeder::class);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'wrong'
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }
}
