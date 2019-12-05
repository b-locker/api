<?php

namespace Tests\Feature\Locker;

use Tests\TestCase;
use App\Models\Locker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_get_collection()
    {
        $lockers = factory(Locker::class, 3)->create();

        $this->json('GET', route('lockers'))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $lockers->map(function ($locker) {
                    return [
                        'id' => $locker->id,
                        'guid' => $locker->guid,
                    ];
                })->toArray(),
            ])
        ;
    }
}
