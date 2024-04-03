<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_posts()
    {
        $response = $this->post('api/post/create');

        $response->assertJsonFragment(['Пост успешно создан.']);
    }
}
