<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_collection()
    {
        $clients = factory(Client::class, 3)->create();

        $this->json('GET', route('clients.index'))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $clients->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'email' => $client->email,
                    ];
                })->toArray(),
            ])
        ;
    }
}
