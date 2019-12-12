<?php

namespace Tests\Feature\Locker;

use Tests\TestCase;
use App\Models\Locker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_record()
    {
        $locker = factory(Locker::class)->create();

        $payload = [
            'id' => $locker->id,
            'guid' => 'new-guid-test',
        ];

        $this->json('PUT', route('lockers.update', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $locker->id,
                    'guid' => 'new-guid-test',
                ],
            ])
        ;
    }
}
