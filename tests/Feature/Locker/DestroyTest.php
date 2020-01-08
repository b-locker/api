<?php

namespace Tests\Feature\Locker;

use Tests\TestCase;
use App\Models\Locker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyTest extends TestCase
{
    public function test_can_destroy_record()
    {
        $locker = factory(Locker::class)->create();

        $this->json('DELETE', route('lockers.destroy', $locker->id))
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'OK.',
            ])
        ;
    }
}
