<?php

namespace Tests\Feature\Locker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_record()
    {
        $payload = [
            'guid' => 'guid-test',
        ];

        $response = $this->json('POST', route('lockers.store', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => $responseData->id,
                    'guid' => $payload['guid'],
                ],
            ])
        ;
    }
}
