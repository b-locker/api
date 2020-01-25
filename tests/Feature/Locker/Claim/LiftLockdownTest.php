<?php

namespace Tests\Feature\Locker\Claim;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiftLockdownTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_lift_lockdown_with_valid_setup_token()
    {
        $lockerClaim = factory(LockerClaim::class)->create([
            'key_hash' => bcrypt('123123'),
            'setup_token' => Str::random(),
            'failed_attempts' => 5,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(7),
        ]);

        $payload = [
            $lockerClaim->locker->guid,
            $lockerClaim->id,
            'setup_token' => $lockerClaim->setup_token,
        ];

        $response = $this->json('POST', route('lockers.claims.lift-lockdown', $payload));
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

    public function test_cannot_lift_without_lockdown_in_place()
    {
        $lockerClaim = factory(LockerClaim::class)->create([
            'key_hash' => bcrypt('123123'),
            'setup_token' => null,
            'failed_attempts' => 0,
            'start_at' => Carbon::now()->addDays(-14),
            'end_at' => Carbon::now()->addDays(-7),
        ]);

        $payload = [
            $lockerClaim->locker->guid,
            $lockerClaim->id,
            'setup_token' => 'abc',
        ];

        $response = $this->json('POST', route('lockers.claims.lift-lockdown', $payload));

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'setup_token' => [
                        'The setup token is invalid.',
                    ],
                ],
            ])
        ;
    }
}
