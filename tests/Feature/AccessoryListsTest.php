<?php

namespace Tests\Feature;

use App\Models\Accessory;
use App\Models\User;
use App\Models\AccessoryList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessoryListsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_accessory_lists_api(): void
    {
        $user = User::factory()->create();
        AccessoryList::factory(10)->create();
        $expected_count = 5; //pagination count
        $response = $this->actingAs($user)->json('get', '/api/accessory_lists?search=1'); //search=1 is pagination number
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $count = count(json_decode($response->getContent())->data);
        $this->assertEquals($expected_count, $count);
        $this->assertEquals('Lists retrieved successfully.', $actual);
    }

    public function test_create_accessory_lists_api(): void
    {
        $user = User::factory()->create();
        $payload = [
            'accessory_id' => Accessory::factory()->create()->id,
            'floor' => rand(1, 4),
            'quantity' => 100,
            'remind_limit' => 20
        ];
        $response = $this->actingAs($user)->json('post', '/api/accessory_lists', $payload);

        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory List created successfully.', $actual);
    }

    public function test_show_accessory_lists_api(): void
    {
        $user = User::factory()->create();
        $accessory = AccessoryList::factory()->create();
        $response = $this->actingAs($user)->json('get', "/api/accessory_lists/$accessory->id");
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory lists retrieved successfully.', $actual);
    }

    public function test_update_accessory_lists_api(): void
    {
        $user = User::factory()->create();
        $accessory = AccessoryList::factory()->create();
        $payload = [
            'accessory_id' => Accessory::factory()->create()->id,
            'floor' => rand(1, 4),
            'quantity' => 100,
            'remind_limit' => 20
        ];
        $response = $this->actingAs($user)->json('patch', "/api/accessory_lists/$accessory->id", $payload);
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory list updated successfully.', $actual);
    }

    public function test_delete_accessory_lists_api(): void
    {
        $user = User::factory()->create();
        $accessory = AccessoryList::factory()->create();
        $response = $this->actingAs($user)->json('delete', "/api/accessory_lists/$accessory->id");
        $response->assertStatus(200);
        $actual = json_decode($response->getContent())->message;
        $this->assertEquals('Accessory list deleted successfully.', $actual);
    }
}
