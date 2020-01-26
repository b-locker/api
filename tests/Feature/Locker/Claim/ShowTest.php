<?php

namespace Tests\Feature\Locker\Claim;

use Tests\TestCase;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_1_record()
    {
        $locker = factory(Locker::class)->create();

        $lockerClaim = factory(LockerClaim::class)->create();
        $lockerClaim->locker_id = $locker->id;
        $lockerClaim->save();

        $payload = [
            $locker->guid,
            $lockerClaim->id,
        ];

        $this->json('GET', route('lockers.claims.show', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $lockerClaim->id,
                    'is_set_up' => $lockerClaim->isSetUp(),
                    'is_active' => $lockerClaim->isActive(),
                    'attempts' => [
                        'failed' => $lockerClaim->failed_attempts,
                        'left' => $lockerClaim->attemptsLeft(),
                    ],
                    'start_at' => $lockerClaim->start_at,
                    'end_at' => $lockerClaim->end_at,
                ],
            ])
        ;
    }
}
