<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_1_record()
    {
        $client = factory(Client::class)->create();

        $this->json('GET', route('clients.show', $client->id))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $client->id,
                    'email' => $client->email,
                ],
            ])
        ;
    }
}
