<?php

namespace Tests\Unit;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassroomControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        Classroom::factory()->count(5)->create();

        $response = $this->get('/api/classrooms');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $grade = Grade::factory()->create();
        $branch = Branch::factory()->create();

        $response = $this->actingAs($user)->post('/api/classrooms', [
            'name' => $this->faker->name,
            'grade_id' => $grade->id,
            'branch_id' => $branch->id,
            'status' => 'active'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Sınıf başarıyla oluşturuldu'
            ]);

        $this->assertDatabaseCount('classes', 1);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create();

        $response = $this->actingAs($user)->get('/api/classrooms/' . $classroom->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $classroom->id
            ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create();
        $grade = Grade::factory()->create();
        $branch = Branch::factory()->create();

        $response = $this->actingAs($user)->put('/api/classrooms/' . $classroom->id, [
            'name' => $this->faker->name,
            'grade_id' => $grade->id,
            'branch_id' => $branch->id,
            'status' => 'active'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Sınıf bilgileri başarıyla güncellendi'
            ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create();

        $response = $this->actingAs($user)->delete('/api/classrooms/' . $classroom->id);

        $response->assertStatus(204);
        $this->assertDatabaseCount('classes', 0);
    }
}
