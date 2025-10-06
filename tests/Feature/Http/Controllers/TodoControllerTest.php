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
            ->assertJsonPath('data.id', 42)
            ->assertJsonPath('data.title', 'foo');
    }
}
