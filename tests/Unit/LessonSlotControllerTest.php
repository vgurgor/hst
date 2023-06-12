<?php
use App\Models\Campus;
use App\Models\LessonSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LessonSlotControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        LessonSlot::factory()->count(5)->create();

        $response = $this->get('/api/lesson-slots');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $campus = Campus::factory()->create();

        $response = $this->post('/api/lesson-slots', [
            'campus_id' => $campus->id,
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '10:00'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Ders slotu başarıyla oluşturuldu'
            ]);

        $this->assertDatabaseCount('lesson_slots', 1);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $lessonSlot = LessonSlot::factory()->create();

        $response = $this->get('/api/lesson-slots/' . $lessonSlot->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $lessonSlot->id
            ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $lessonSlot = LessonSlot::factory()->create();
        $campus = Campus::factory()->create();

        $response = $this->put('/api/lesson-slots/' . $lessonSlot->id, [
            'campus_id' => $campus->id,
            'day' => 'Tuesday',
            'start_time' => '10:00',
            'end_time' => '11:00'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Ders slotu başarıyla güncellendi'
            ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $lessonSlot = LessonSlot::factory()->create();

        $response = $this->delete('/api/lesson-slots/' . $lessonSlot->id);

        $response->assertStatus(204);
        $this->assertDatabaseCount('lesson_slots', 0);
    }
}
