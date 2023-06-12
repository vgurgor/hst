<?php

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Teacher::factory()->count(5)->create();

        $response = $this->get('/api/teachers');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/api/teachers', [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'status' => 'active',
            'majors' => ['Math', 'Science']
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Öğretmen başarıyla oluşturuldu'
            ]);

        $this->assertDatabaseCount('teachers', 1);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $teacher = Teacher::factory()->create();

        $response = $this->get('/api/teachers/' . $teacher->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $teacher->id
            ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $teacher = Teacher::factory()->create();

        $response = $this->put('/api/teachers/' . $teacher->id, [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'status' => 'active',
            'majors' => ['Math', 'Science']
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Öğretmen bilgileri başarıyla güncellendi'
            ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $teacher = Teacher::factory()->create();

        $response = $this->delete('/api/teachers/' . $teacher->id);

        $response->assertStatus(204);
        $this->assertDatabaseCount('teachers', 0);
    }
}
