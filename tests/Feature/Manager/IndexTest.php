<?php

namespace Tests\Feature\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_collection()
    {
        $managers = factory(Manager::class, 3)->create();

        $this->json('GET', route('managers.index'))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $managers->map(function ($manager) {
                    return [
                        'id' => $manager->id,
                        'first_name' => $manager->first_name,
                        'last_name' => $manager->last_name,
                        'email' => $manager->email,
                    ];
                })->toArray(),
            ])
        ;
    }
}
