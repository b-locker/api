<?php

namespace Tests\Feature\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_destroy_record()
    {
        $manager = factory(Manager::class)->create();

        $this->json('DELETE', route('managers.destroy', $manager->id))
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'OK.',
            ])
        ;
    }
}
