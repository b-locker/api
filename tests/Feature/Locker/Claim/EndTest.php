<?php

namespace Tests\Feature\Locker\Claim;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EndTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_end_active_claim()
    {
        $lockerClaim = factory(LockerClaim::class)->create([
            'key_hash' => bcrypt('123123'),
            'failed_attempts' => 0,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(7),
        ]);

        $payload = [
            $lockerClaim->locker->guid,
            $lockerClaim->id,
            'key' => '123123',
        ];

        $response = $this->json('POST', route('lockers.claims.end', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $responseData->id,
                ],
            ])
        ;

        $updatedLockerClaim = LockerClaim::findOrFail($responseData->id);
        $this->assertFalse($updatedLockerClaim->isActive());
    }

    public function test_cannot_end_inactive_claim()
    {
        $lockerClaim = factory(LockerClaim::class)->create([
            'key_hash' => bcrypt('123123'),
            'failed_attempts' => 0,
            'start_at' => Carbon::now()->addDays(-14),
            'end_at' => Carbon::now()->addDays(-7),
        ]);

        $payload = [
            $lockerClaim->locker->guid,
            $lockerClaim->id,
            'key' => '123123',
        ];

        $response = $this->json('POST', route('lockers.claims.end', $payload));

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Locker is not claimed.',
            ])
        ;
    }
}
