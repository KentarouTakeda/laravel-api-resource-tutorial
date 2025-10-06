<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    public function test_show(): void
    {
        Todo::factory()
            ->for($this->user)
            ->create(['id' => 42, 'title' => 'foo']);

        $this->json('GET', 'todos/42')
            ->assertStatus(200)
            ->assertJsonMissingPath('data.created_at')
            ->assertJsonPath('data.id', 42)
            ->assertJsonPath('data.title', 'foo');
    }

    public function test_index(): void
    {
        Todo::factory()
            ->for($this->user)
            ->createMany([
                ['id' => 42],
                ['id' => 23],
            ]);

        Todo::factory()->create();

        $this->json('GET', 'todos')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', 23)
            ->assertJsonPath('data.1.id', 42);
    }

    public function test_destroy(): void
    {
        Todo::factory()
            ->for($this->user)
            ->create(['id' => 42]);

        $this->json('DELETE', 'todos/42')
            ->assertStatus(200)
            ->assertJsonPath('data.id', 42);

        $this->assertDatabaseMissing(Todo::class, ['id' => 42]);
    }

    public function test_store(): void
    {
        $this->json('POST', 'todos', ['title' => 'foo'])
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'foo');

        $this->assertDatabaseHas(Todo::class, [
            'user_id' => $this->user->id,
            'title' => 'foo'
        ]);
    }
}
