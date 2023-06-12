<?php

namespace Tests\Unit;

use App\Models\Major;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MajorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create majors
        Major::factory()->create(['name' => 'Major 1', 'status' => 'active']);
        Major::factory()->create(['name' => 'Major 2', 'status' => 'inactive']);

        $response = $this->get('/api/majors');

        $response->assertStatus(200)
            ->assertJson([
                ['name' => 'Major 1', 'status' => 'active'],
                ['name' => 'Major 2', 'status' => 'inactive']
            ]);
    }

    public function testStore()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        $response = $this->post('/api/majors', [
            'name' => 'New Major',
            'status' => 'active'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Major created successfully',
                'data' => [
                    'name' => 'New Major',
                    'status' => 'active'
                ]
            ]);

        $this->assertDatabaseHas('majors', [
            'name' => 'New Major',
            'status' => 'active'
        ]);
    }

    // Rest of the test methods...
}
