<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function testDatabaseConnection()
    {
        $this->assertEquals('sqlite', config('database.default'));
        $this->assertEquals(':memory:', config('database.connections.sqlite.database'));
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');
        Log::info('Current Database Connection:', ['connection' => config('database.default')]);

        $response->assertStatus(200);
    }

    public function test_user_can_be_created()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe Test',
            'email' => 'johntest1@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe Test',
            'email' => 'johntest1@example.com',
        ]);
    }
}
