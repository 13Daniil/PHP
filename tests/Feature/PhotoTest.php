<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Photo;

class PhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAddPhoto()
    {
        Storage::fake('images');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/photos/add', [
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'description' => 'Test photo description',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['photo' => ['id', 'path', 'description', 'user_id', 'folder_id']]);

        $photo = Photo::first();
        $this->assertNotNull($photo);
        Storage::assertExists($photo->path);
    }

    public function testAddPhotoWithoutDescription()
    {
        Storage::fake('images');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/photos/add', [
            'photo' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['photo' => ['id', 'path', 'description', 'user_id', 'folder_id']]);
    }

    public function testAddPhotoWithoutFile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/photos/add', [
            'description' => 'Test photo description',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Invalid or missing file.']);
    }

    public function testShowPhotos()
    {
        Photo::factory()->count(3)->create();

        $response = $this->get('/api/photos');

        $response->assertStatus(200)
                 ->assertJsonStructure(['photos']);
        $this->assertCount(3, $response->json('photos'));
    }
}
