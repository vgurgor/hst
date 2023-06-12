<?php
namespace Tests\Unit;

use App\Models\Branch;
use App\Models\Campus;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();

        Grade::factory()->create(['name' => 'Grade 1']);
        Grade::factory()->create(['name' => 'Grade 2']);

        $response = $this->actingAs($user)->get('/api/grades');


        $response->assertStatus(200)
            ->assertJson([
                ['name' => 'Grade 1'],
                ['name' => 'Grade 2']
            ]);
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Grade 3'
        ];

        $response = $this->actingAs($user)->post('/api/grades', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Düzey başarıyla oluşturuldu',
                'data' => ['name' => 'Grade 3']
            ]);

        $this->assertDatabaseHas('grades', ['name' => 'Grade 3']);
    }

    public function testShow()
    {
        $user = User::factory()->create();

        $grade = Grade::factory()->create(['name' => 'Grade 4']);

        $response = $this->actingAs($user)->get("/api/grades/{$grade->id}");

        $response->assertStatus(200)
            ->assertJson(['name' => 'Grade 4']);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();

        $grade = Grade::factory()->create(['name' => 'Grade 5']);

        $data = [
            'name' => 'Updated Grade 5'
        ];

        $response = $this->actingAs($user)->put("/api/grades/{$grade->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Düzey bilgileri başarıyla güncellendi',
                'data' => ['name' => 'Updated Grade 5']
            ]);

        $this->assertDatabaseHas('grades', ['name' => 'Updated Grade 5']);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $grade = Grade::factory()->create();

        $response = $this->actingAs($user)->delete("/api/grades/{$grade->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('grades', ['id' => $grade->id]);
    }
}
