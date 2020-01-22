<?php

namespace Tests\Feature\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_record()
    {
        $manager = factory(Manager::class)->create();

        $this->json('GET', route('managers.show', $manager->id))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $manager->id,
                    'first_name' => $manager->first_name,
                    'last_name' => $manager->last_name,
                    'email' => $manager->email,
                ],
            ])
        ;
    }
}
