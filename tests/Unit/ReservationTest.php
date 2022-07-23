<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_list_reservations()
    {
        User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this->get('api/reservation');
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['start_time' => $reservation->start_time->format('Y-m-d H:i:s')]);

    }

    public function test_create_reservation()
    {
        $response = $this->post('api/reservation', [
            "user_id" => User::factory()->create()->id,
            "description" => "desc",
            "status" => "pending",
            "start_time" => "2022-07-25 14:30:00"
        ]);
        
        $response->assertStatus(201);
    }

    public function test_create_reservation_with_duplicate_start_time()
    {

        User::factory()->create();
        $response = $this->post('api/reservation', [
            "user_id" => User::factory()->create()->id,
            "description" => "desc",
            "status" => "pending",
            "start_time" => "2022-07-24 14:30:00"
        ]);
        $response = $this->post('api/reservation', [
            "user_id" => User::factory()->create()->id,
            "description" => "desc",
            "status" => "pending",
            "start_time" => "2022-07-24 14:30:00"
        ]);

        $response->assertStatus(422);
    }

    
    public function test_show_reservations()
    {
        User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this->get('api/reservation/'.$reservation->id );
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $reservation->id,
                'start_time' => $reservation->start_time->format('Y-m-d H:i:s')
            ]);

    }

    
    public function test_update_reservations()
    {
        User::factory()->create();
        $reservation = Reservation::factory()->create();

        $update = [
            "user_id" => User::factory()->create()->id,
            "description" => "desc edit",
            "status" => "completed",
            "start_time" => "2022-07-24 14:30:00"
        ];

        $response = $this->put('api/reservation/'.$reservation->id, $update);
        $response
            ->assertStatus(200)
            ->assertJsonFragment($update);

    }

    public function test_update_reservations_with_invalid_status()
    {
        User::factory()->create();
        $reservation = Reservation::factory()->create();

        $update = [
            "user_id" => User::factory()->create()->id,
            "description" => "desc edit",
            "status" => "complete",
            "start_time" => "2022-07-24 14:30:00"
        ];

        $response = $this->put('api/reservation/'.$reservation->id, $update);
        $response->assertStatus(422);

    }

    
    public function test_delete_reservations()
    {
        User::factory()->create();
        $reservation = Reservation::factory()->create();


        $response = $this->delete('api/reservation/'.$reservation->id);
        $response->assertStatus(204);

        $this->assertEquals(Reservation::count(), 0);

    }
}
