<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_record()
    {
        $client = factory(Client::class)->create();

        $payload = [
            'id' => $client->id,
            'email' => 'new@example.com',
        ];

        $this->json('PUT', route('clients.update', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $client->id,
                    'email' => 'new@example.com',
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
