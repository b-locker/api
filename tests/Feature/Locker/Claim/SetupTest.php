<?php

namespace Tests\Feature\Locker\Claim;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_setup_available_locker_claim_with_valid_key()
    {
        $locker = factory(Locker::class)->create();
        $lockerClaim = factory(LockerClaim::class)->create([
            'locker_id' => $locker->id,
            'key_hash' => null,
        ]);

        $payload = [
            $locker->guid,
            $lockerClaim->id,
            'setup_token' => $lockerClaim->setup_token,
            'key' => '123123',
        ];

        $response = $this->json('POST', route('lockers.claims.setup', $payload));
        $responseData = optional($response->getData())->data;
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $responseData->id,
                    'is_set_up' => true,
                ],
            ])
        ;
    }

    public function test_cannot_setup_locker_claim_when_locker_active()
    {
        $locker = factory(Locker::class)->create();
        $activeLockerClaim = factory(LockerClaim::class)->create([
            'locker_id' => $locker->id,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(7),
        ]);
        $lockerClaimToSetUp = factory(LockerClaim::class)->create([
            'locker_id' => $locker->id,
            'key_hash' => null,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(7),
        ]);

        $payload = [
            $locker->guid,
            $lockerClaimToSetUp->id,
            'setup_token' => $lockerClaimToSetUp->setup_token,
            'key' => '123123',
        ];

        $response = $this->json('POST', route('lockers.claims.setup', $payload))
            ->assertStatus(400)
            ->assertJson([
                'message' => 'The locker is already claimed.',
            ])
        ;
    }

    public function test_cannot_setup_locker_claim_with_missing_key()
    {
        $locker = factory(Locker::class)->create();
        $lockerClaim = factory(LockerClaim::class)->create([
            'locker_id' => $locker->id,
            'key_hash' => null,
        ]);

        $payload = [
            $locker->guid,
            $lockerClaim->id,
            'setup_token' => $lockerClaim->setup_token,
        ];

        $response = $this->json('POST', route('lockers.claims.setup', $payload))
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key' => [
                        'The key field is required.',
                    ],
                ],
            ])
        ;
    }

    public function test_cannot_setup_locker_claim_with_too_short_key()
    {
        $locker = factory(Locker::class)->create();
        $lockerClaim = factory(LockerClaim::class)->create([
            'locker_id' => $locker->id,
            'key_hash' => null,
        ]);

        $payload = [
            $locker->guid,
            $lockerClaim->id,
            'setup_token' => $lockerClaim->setup_token,
            'key' => 'abc',
        ];

        $response = $this->json('POST', route('lockers.claims.setup', $payload))
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key' => [
                        'The key must be at least 6 characters.',
                    ],
                ],
            ])
        ;
    }

    public function test_cannot_setup_locker_claim_with_too_long_key()
    {
        $locker = factory(Locker::class)->create();
        $lockerClaim = factory(LockerClaim::class)->create([
            'locker_id' => $locker->id,
            'key_hash' => null,
        ]);

        $maxKeyLength = 100;

        $payload = [
            $locker->guid,
            $lockerClaim->id,
            'setup_token' => $lockerClaim->setup_token,
            'key' => str_repeat('a', $maxKeyLength + 1),
        ];

        $response = $this->json('POST', route('lockers.claims.setup', $payload))
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key' => [
                        'The key may not be greater than ' . $maxKeyLength . ' characters.',
                    ],
                ],
            ])
        ;
    }
}
