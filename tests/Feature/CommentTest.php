<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateComment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->post('/api/comments/create', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test comment',
        ]);

        $response->assertStatus(200)
                 ->assertJson("Комментарий успешно создан.");

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test comment',
        ]);
    }

    public function testDeleteComment()
    {
        $comment = Comment::factory()->create();

        $response = $this->delete('/api/comments/delete/' . $comment->id);

        $response->assertStatus(200)
                 ->assertJson("Комментарий успешно удален.");

        $this->assertDeleted($comment);
    }

    public function testDeleteNonExistentComment()
    {
        $response = $this->delete('/api/comments/delete/999');

        $response->assertStatus(404)
                 ->assertJson("Комментарий не найден.");
    }
}
