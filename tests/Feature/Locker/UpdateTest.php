<?php

namespace Tests\Feature\Locker;

use Tests\TestCase;
use App\Models\Locker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_record()
    {
        $locker = factory(Locker::class)->create();

        $payload = [
            'lockerGuid' => $locker->guid,
            'guid' => 'new-guid', // 8 chars
        ];

        $this->json('PUT', route('lockers.update', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $locker->id,
                    'guid' => 'new-guid',
                    'active_claim' => null,
                ],
            ])
        ;
    }
}
