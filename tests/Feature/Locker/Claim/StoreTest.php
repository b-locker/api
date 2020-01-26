<?php

namespace Tests\Feature\Locker\Claim;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_1_record()
    {
        $locker = factory(Locker::class)->create();

        $payload = [
            $locker->guid,
            'email' => factory(Client::class)->create()->email,
        ];

        $response = $this->json('POST', route('lockers.claims.store', $payload));
        $responseData = optional($response->getData())->data;

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => $responseData->id,
                    'is_set_up' => false,
                    'is_active' => false,
                    'attempts' => [
                        'failed' => 0,
                        'left' => LockerClaim::MAX_FAILED_AUTH_ATTEMPTS,
                    ],
                    'start_at' => $responseData->start_at,
                    'end_at' => $responseData->end_at,
                ],
            ])
        ;
    }
}
