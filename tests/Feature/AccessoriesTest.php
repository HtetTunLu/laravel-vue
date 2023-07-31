<?php

namespace Tests\Feature;

use App\Models\Accessory;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class AccessoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_accessories_api(): void
    {
        $user = User::factory()->create();
        $accessories = Accessory::factory(3)->create();
        $expected_count = count($accessories);
        $response = $this->actingAs($user)->json('get', '/api/accessories');
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $count = count(json_decode($response->getContent())->data);
        $this->assertEquals($expected_count, $count);
        $this->assertEquals('Accessories retrieved successfully.', $actual);
    }

    public function test_create_accessories_api(): void
    {
        $user = User::factory()->create();
        $payload = [
            'name' => fake()->text(maxNbChars: 20),
            'image'  => UploadedFile::fake()->image('test.jpg')
        ];
        $response = $this->actingAs($user)->json('post', '/api/accessories', $payload);

        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory created successfully.', $actual);
    }

    public function test_show_accessory_api(): void
    {
        $user = User::factory()->create();
        $accessory = Accessory::factory()->create();
        $response = $this->actingAs($user)->json('get', "/api/accessories/$accessory->id");
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory retrieved successfully.', $actual);
    }

    public function test_update_accessory_api(): void
    {
        $user = User::factory()->create();
        $accessory = Accessory::factory()->create();
        $payload = [
            'name' => fake()->text(maxNbChars: 20),
            'image'  => UploadedFile::fake()->image('test.jpg')
        ];
        $response = $this->actingAs($user)->json('patch', "/api/accessories/$accessory->id", $payload);
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory updated successfully.', $actual);
    }

    public function test_delete_accessory_api(): void
    {
        $user = User::factory()->create();
        $accessory = Accessory::factory()->create();
        $response = $this->actingAs($user)->json('delete', "/api/accessories/$accessory->id");
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory deleted successfully.', $actual);
    }
}
