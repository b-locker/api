<?php

namespace Tests\Feature\Client;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_record()
    {
        $payload = [
            'email' => 'email@example.com',
        ];

        $response = $this->json('POST', route('clients.store', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => $responseData->id,
                    'email' => $payload['email'],
                ],
            ])
        ;
    }
}
