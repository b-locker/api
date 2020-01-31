<?php

namespace Tests\Feature\Locker\Claim;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateKeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_own_key()
    {
        $lockerClaim = factory(LockerClaim::class)->create([
            'key_hash' => bcrypt('123123'),
            'failed_attempts' => 0,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(7),
        ]);

        $newKey = 'abc123';

        $payload = [
            $lockerClaim->locker->guid,
            $lockerClaim->id,
            'key' => '123123',
            'new_key' => $newKey,
        ];

        $response = $this->json('POST', route('lockers.claims.update-key', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $responseData->id,
                ],
            ])
        ;

        $updatedLockerClaim = LockerClaim::findOrFail($responseData->id);
        $this->assertTrue(password_verify($newKey, $updatedLockerClaim->key_hash));
    }

    public function test_cannot_update_with_wrong_original_key()
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
            'key' => 'wrong key',
            'new_key' => 'abc123',
        ];

        $response = $this->json('POST', route('lockers.claims.update-key', $payload))
            ->assertStatus(400)
            ->assertJson([
                'message' =>
                    'The provided key does not work. You have ' .
                    (LockerClaim::MAX_FAILED_AUTH_ATTEMPTS - 1) . ' attempt(s) left.',
            ])
        ;
    }

    public function test_cannot_update_with_too_short_new_key()
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
            'new_key' => 'abc',
        ];

        $response = $this->json('POST', route('lockers.claims.update-key', $payload))
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'new_key' => [
                        'The new key must be at least 6 characters.',
                    ],
                ],
            ])
        ;
    }

    public function test_cannot_update_with_too_long_new_key()
    {
        $lockerClaim = factory(LockerClaim::class)->create([
            'key_hash' => bcrypt('123123'),
            'failed_attempts' => 0,
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(7),
        ]);

        $maxKeyLength = 100;

        $payload = [
            $lockerClaim->locker->guid,
            $lockerClaim->id,
            'key' => '123123',
            'new_key' => str_repeat('a', $maxKeyLength + 1),
        ];

        $response = $this->json('POST', route('lockers.claims.update-key', $payload))
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'new_key' => [
                        'The new key may not be greater than ' . $maxKeyLength . ' characters.',
                    ],
                ],
            ])
        ;
    }
}
