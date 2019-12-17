<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_record()
    {
        $client = factory(Client::class)->create();

        $payload = [
            'id' => $client->id,
            'email' => 'new@example.com',
        ];

        $this->json('PUT', route('clients.update', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $client->id,
                    'email' => 'new@example.com',
                ],
            ])
        ;
    }
}
