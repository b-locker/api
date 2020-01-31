<?php

namespace Tests\Feature\Locker\Claim;

use Tests\TestCase;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_collection()
    {
        $locker = factory(Locker::class)->create();

        $lockerClaims = factory(LockerClaim::class, 3)
            ->create()
            ->each(function (LockerClaim $lockerClaim) use ($locker) {
                $lockerClaim->locker_id = $locker->id;
                $lockerClaim->save();
            })
        ;

        $payload = [
            $locker->guid,
        ];

        $this->json('GET', route('lockers.claims.index', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $lockerClaims->map(function (LockerClaim $lockerClaim) {
                    return [
                        'id' => $lockerClaim->id,
                        'is_set_up' => $lockerClaim->isSetUp(),
                        'is_active' => $lockerClaim->isActive(),
                        'attempts' => [
                            'failed' => $lockerClaim->failed_attempts,
                            'left' => $lockerClaim->attemptsLeft(),
                        ],
                        'start_at' => $lockerClaim->start_at,
                        'end_at' => $lockerClaim->end_at,
                    ];
                })->toArray(),
            ])
        ;
    }
}
