<?php

namespace Tests\Feature\Locker;

use Tests\TestCase;
use App\Models\Locker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_1_model()
    {
        $locker = factory(Locker::class)->create();

        $this->json('GET', route('lockers.show', $locker->id))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $locker->id,
                    'guid' => $locker->guid,
                ],
            ])
        ;
    }
}
