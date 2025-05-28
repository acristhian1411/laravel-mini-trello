<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Boards;
use App\Models\ListModel; // Ajustá según el nombre real
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        // Crea usuario y obtiene token
        $user = User::factory()->create();
        $this->token = auth('api')->login($user);
    }

    protected function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_can_get_authenticated_user()
    {
        $response = $this->getJson('/api/me', $this->headers());
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'user']);
    }

    public function test_can_create_board()
    {
        $response = $this->postJson('/api/boards?wantsJson=true', [
            'name' => 'Test Board',
        ], $this->headers());

        $response->assertStatus(201)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_boards()
    {
        Boards::factory()->count(3)->create();

        $response = $this->getJson('/api/boards?wantsJson=true', $this->headers());
        $response->assertStatus(200);
    }

    public function test_can_get_single_board()
    {
        $board = Boards::factory()->create();

        $response = $this->getJson("/api/boards/{$board->id}?wantsJson=true", $this->headers());
        $response->assertStatus(200)
                 ->assertJson(['data' => ['id' => $board->id]]);
    }

    public function test_can_update_board()
    {
        $board = Boards::factory()->create();

        $response = $this->putJson("/api/boards/{$board->id}?wantsJson=true", [
            'name' => 'Updated Board'
        ], $this->headers());

        $response->assertStatus(200)
                 ->assertJson(['data' => ['name' => 'Updated Board']]);
    }

    public function test_can_delete_board()
    {
        $board = Boards::factory()->create();

        $response = $this->deleteJson("/api/boards/{$board->id}?wantsJson=true", [], $this->headers());
        $response->assertStatus(200); // o el código que devuelvas
    }

    // Repetí la lógica para ListController
}
