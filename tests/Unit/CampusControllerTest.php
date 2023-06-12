<?php

use App\Http\Controllers\API\CampusController;
use App\Models\Campus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CampusControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndexReturnsCampuses()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Önceden birkaç kampüs kaydedelim
        Campus::factory()->count(3)->create();

        // index metoduyla tüm kampüsleri alalım
        $response = $this->getJson(route('campuses.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3) // Dönen JSON'da 3 kampüs olmalı
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'created_by',
                    'created_at',
                    'updated_by',
                    'updated_at',
                ],
            ]);
    }

    public function testStoreCreatesNewCampus()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Geçerli verilerle bir istek gönderelim
        $data = [
            'name' => $this->faker->name,
            'status' => 'active',
        ];

        // İstek gönderme
        $response = $this->postJson(route('campuses.store'), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Kampüs başarıyla oluşturuldu',
                'data' => [
                    'name' => $data['name'],
                    'status' => $data['status'],
                    'created_by' => $user->id,
                ],
            ]);

        // Veritabanında yeni kampüs oluşturulduğunu doğrulayalım
        $this->assertDatabaseHas('campuses', $data);
    }

    public function testStoreFailsWithInvalidData()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Geçersiz verilerle bir istek gönderelim
        $data = [
            'name' => '', // name alanı boş
            'status' => 'active',
        ];

        // İstek gönderme
        $response = $this->postJson(route('campuses.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testShowReturnsCampus()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Bir kampüs kaydedelim
        $campus = Campus::factory()->create();

        // show metoduyla kampüsü alalım
        $response = $this->getJson(route('campuses.show', ['campus' => $campus->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $campus->id,
                'name' => $campus->name,
                'status' => $campus->status,
                'created_by' => $campus->created_by,
            ]);
    }

    public function testShowFailsWithInvalidId()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Geçersiz bir kampüs ID'siyle bir istek gönderelim
        $response = $this->getJson(route('campuses.show', ['campus' => 999]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['error' => 'Kampüs bulunamadı']);
    }

    public function testUpdateUpdatesExistingCampus()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Bir kampüs kaydedelim
        $campus = Campus::factory()->create();

        // Geçerli verilerle bir istek gönderelim
        $data = [
            'name' => $this->faker->name,
            'status' => 'inactive',
        ];

        // İstek gönderme
        $response = $this->putJson(route('campuses.update', ['campus' => $campus->id]), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Kampüs bilgileri başarıyla güncellendi',
                'data' => [
                    'name' => $data['name'],
                    'status' => $data['status'],
                    'updated_by' => $user->id,
                ],
            ]);

        // Veritabanında kampüsün güncellendiğini doğrulayalım
        $this->assertDatabaseHas('campuses', $data);
    }

    public function testUpdateFailsWithInvalidId()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Geçersiz bir kampüs ID'siyle bir istek gönderelim
        $response = $this->putJson(route('campuses.update', ['campus' => 999]), []);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['error' => 'Kampüs bulunamadı']);
    }

    public function testUpdateFailsWithInvalidData()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Bir kampüs kaydedelim
        $campus = Campus::factory()->create();

        // Geçersiz verilerle bir istek gönderelim
        $data = [
            'name' => '', // name alanı boş
            'status' => 'inactive',
        ];

        // İstek gönderme
        $response = $this->putJson(route('campuses.update', ['campus' => $campus->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testDestroyDeletesExistingCampus()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Bir kampüs kaydedelim
        $campus = Campus::factory()->create();

        // destroy metoduyla kampüsü silelim
        $response = $this->deleteJson(route('campuses.destroy', ['campus' => $campus->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        // Veritabanında kampüsün silindiğini doğrulayalım
        $this->assertDatabaseMissing('campuses', ['id' => $campus->id]);
    }

    public function testDestroyFailsWithInvalidId()
    {
        // Bir kullanıcı oluşturalım ve oturum açalım
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Geçersiz bir kampüs ID'siyle bir istek gönderelim
        $response = $this->deleteJson(route('campuses.destroy', ['campus' => 999]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['error' => 'Kampüs bulunamadı']);
    }
}
