<?php

namespace Tests\Feature\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_record()
    {
        $manager = factory(Manager::class)->make();

        $payload = [
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name,
            'email' => $manager->email,
            'password' => $manager->password,
        ];

        $response = $this->json('POST', route('managers.store', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => $responseData->id,
                    'first_name' => $payload['first_name'],
                    'last_name' => $payload['last_name'],
                    'email' => $payload['email'],
                ],
            ])
        ;
    }

    public function test_validates_email_type()
    {
        $manager = factory(Manager::class)->make();

        $payload = [
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name,
            'email' => 'INVALID_EMAIL_ADDRESS',
            'password' => $manager->password,
        ];

        $this->json('POST', route('managers.store', $payload))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.',
                    ],
                ],
            ])
        ;
    }

    public function test_validates_email_length_too_long()
    {
        $maxLength = 254;

        $manager = factory(Manager::class)->make();

        $payload = [
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name,
            'email' => str_repeat('a', $maxLength - strlen('_long@email.com') + 1) . '_long@email.com',
            'password' => $manager->password,
        ];

        $this->json('POST', route('managers.store', $payload))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        'The email may not be greater than ' . $maxLength . ' characters.',
                    ],
                ],
            ])
        ;
    }
}
