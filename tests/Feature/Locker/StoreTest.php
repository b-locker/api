<?php

namespace Tests\Feature\Locker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_1_record()
    {
        $payload = [
            'guid' => 'guidtest', // 8 chars
        ];

        $response = $this->json('POST', route('lockers.store', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => $responseData->id,
                    'guid' => $payload['guid'],
                    'active_claim' => null,
                ],
            ])
        ;
    }
}
