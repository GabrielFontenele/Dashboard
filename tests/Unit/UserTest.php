<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_list_users()
    {
        $user = User::factory()->create();

        $response = $this->get("api/user");
        $response
            ->assertStatus(200)
            ->assertJsonFragment(["id" => $user->id]);

    }

    public function test_create_user()
    {
        $response = $this->post("api/user", [
            "name" => "John smith",
            "document" => "1234567890",
            "birthday" => "1989-12-06 00:00:00",
            "plan" => "basic",
        ]);
        
        $response->assertStatus(201);
        $this->assertEquals(User::count(), 1);
    }
    
    public function test_create_user_with_invalid_plan()
    {
        $response = $this->post("api/user", [
            "name" => "John smith",
            "document" => "1234567890",
            "birthday" => "1989-12-06 00:00:00",
            "plan" => "invalid",
        ]);

        $response->assertStatus(422);
        $this->assertEquals(User::count(), 0);
    }

    public function test_create_user_with_duplicate_document()
    {
        $this->post("api/user", [
            "name" => "John smith",
            "document" => "1234567890",
            "birthday" => "1989-12-06 00:00:00",
            "plan" => "basic",
        ]);

        $response = $this->post("api/user", [
            "name" => "Doctor",
            "document" => "1234567890",
            "birthday" => "1963-11-23 00:00:00",
            "plan" => "basic",
        ]);

        $response->assertStatus(422);
        $this->assertEquals(User::count(), 1);
    }

    public function test_show_user()
    {
        $user = User::factory()->create();

        $response = $this->get("api/user/".$user->id );
        $response
            ->assertStatus(200)
            ->assertJsonFragment(["id" => $user->id]);
        
    }
    
    public function test_update_user()
    {
        $user = User::factory()->create();

        $update = [
            "name" => "Doctor",
            "document" => "1234567890",
            "birthday" => "1963-11-23 00:00:00",
            "plan" => "basic",
        ];

        $response = $this->put("api/user/".$user->id, $update);
        $response
            ->assertStatus(200)
            ->assertJsonFragment($update);

    }

    public function test_update_user_with_invalid_plan()
    {
        $user = User::factory()->create();

        $update = [
            "name" => "Doctor",
            "document" => "1234567890",
            "birthday" => "1963-11-23 00:00:00",
            "plan" => "invalid",
        ];

        $response = $this->put("api/user/".$user->id, $update);
        $response->assertStatus(422);

    }

    public function test_delete_reservations()
    {
        $user = User::factory()->create();

        $response = $this->delete("api/user/".$user->id);
        $response->assertStatus(204);

        $this->assertEquals(User::count(), 0);

    }
}
