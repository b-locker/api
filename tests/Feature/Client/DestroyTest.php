<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyTest extends TestCase
{
    public function test_can_destroy_record()
    {
        $client = factory(Client::class)->create();

        $this->json('DELETE', route('clients.destroy', $client->id))
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'OK.'
            ]);
    }
}
