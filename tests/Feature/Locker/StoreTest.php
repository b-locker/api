<?php

namespace Tests\Feature\Locker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_record()
    {
        $data = json_encode(array('guid' => 'testguid'));

        $this->json('POST', route('lockers.store', json_encode(['guid' => 'testguid'])))
            ->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id',
                    'guid' => $data->guid,
                ],
            ]);
    }
}
