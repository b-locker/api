<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_record()
    {
        $payload = [
            'email' => 'email@example.com',
        ];

        $response = $this->json('POST', route('clients.store', $payload));
        $responseData = $response->getData()->data;

        $response->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => $responseData->id,
                    'email' => $payload['email'],
                ],
            ])
        ;
    }

    public function test_validates_email_type()
    {
        $payload = [
            'email' => 'INVALID_EMAIL_ADDRESS',
        ];

        $this->json('POST', route('clients.store', $payload))
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

        $payload = [
            'email' => str_repeat('a', $maxLength - strlen('_long@email.com') + 1) . '_long@email.com',
        ];

        $this->json('POST', route('clients.store', $payload))
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
