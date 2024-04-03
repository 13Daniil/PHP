<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistration()
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'success']);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function testUniqueEmailRegistration()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post('/api/register', [
            'name' => 'Another Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }

    public function testUserLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson("Успешная аутентификация.");
    }

    public function testInvalidLoginCredentials()
    {
        $response = $this->post('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson("Неверные учетные данные.");
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/api/delete/' . $user->id);

        $response->assertStatus(200)
                 ->assertJson("Пользователь успешно удален.");

        $this->assertDeleted($user);
    }

    public function testDeleteNonExistentUser()
    {
        $response = $this->delete('/api/delete/999');

        $response->assertStatus(404)
                 ->assertJson("Пользователь не найден.");
    }

    public function testDeleteUnauthorizedUser()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $response = $this->actingAs($user)->delete('/api/delete/' . $anotherUser->id);

        $response->assertStatus(403)
                 ->assertJson("У вас нет прав для удаления этого пользователя.");
    }
}
