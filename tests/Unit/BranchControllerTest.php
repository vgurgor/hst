<?php

namespace Tests\Unit;

use App\Models\Branch;
use App\Models\Campus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_can_get_all_branches()
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $branches = Branch::factory()->count(3)->create(['campus_id' => $campus->id]);

        $response = $this->actingAs($user)
            ->getJson('/api/branches');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'type', 'campus_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at']
            ]);
    }

    public function test_can_create_branch()
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();

        $data = [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement(['AO', 'LS']),
            'campus_id' => $campus->id,
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/branches', $data);


        $response->assertJson([
                'message' => 'Şube başarıyla oluşturuldu',
                'data' => $data
            ]);

        $this->assertDatabaseHas('branches', $data);
    }

    public function test_can_get_branch()
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $branch = Branch::factory()->create(['campus_id' => $campus->id]);

        $response = $this->actingAs($user)
            ->getJson('/api/branches/' . $branch->id);

        $response->assertOk()
            ->assertJson($branch->toArray());
    }

    public function test_can_update_branch()
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $branch = Branch::factory()->create(['campus_id' => $campus->id]);

        $data = [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement(['Type 1', 'Type 2']),
            'campus_id' => $campus->id,
        ];

        $response = $this->actingAs($user)
            ->putJson('/api/branches/' . $branch->id, $data);

        $response->assertOk()
            ->assertJson([
                'message' => 'Şube bilgileri başarıyla güncellendi',
                'data' => $data
            ]);

        $this->assertDatabaseHas('branches', $data);
    }

    public function test_can_delete_branch()
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $branch = Branch::factory()->create(['campus_id' => $campus->id]);

        $response = $this->actingAs($user)
            ->deleteJson('/api/branches/' . $branch->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('branches', $branch->toArray());
    }
}
