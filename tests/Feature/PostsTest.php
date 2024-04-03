<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePost()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/api/posts/create', [
            'content' => 'Test content',
        ]);

        $response->assertStatus(200)
                 ->assertJson("Пост успешно создан.");

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'Test content',
        ]);
    }

    public function testDeletePost()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->delete('/api/posts/delete/' . $post->id);

        $response->assertStatus(200)
                 ->assertJson("Пост успешно удален.");

        $this->assertDeleted($post);
    }

    public function testDeleteNonExistentPost()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/api/posts/delete/999');

        $response->assertStatus(404)
                 ->assertJson("Пост не найден.");
    }

    public function testDeleteUnauthorizedPost()
    {
        $user = User::factory()->create();
        $postOwner = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $postOwner->id]);

        $response = $this->delete('/api/posts/delete/' . $post->id);

        $response->assertStatus(403)
                 ->assertJson("У вас нет прав для удаления этого поста.");
    }

    public function testShowPosts()
    {
        $posts = Post::factory(3)->create();

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
