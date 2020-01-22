<?php

namespace Tests\Feature\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_record()
    {
        $manager = factory(Manager::class)->create();

        $payload = [
            'id' => $manager->id,
            'first_name' => 'New First Name',
            'last_name' => 'New Last Name',
            'email' => 'new@example.com',
            'password' => 'My 1 New Password (Phrase)',
        ];

        $this->json('PUT', route('managers.update', $payload))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $manager->id,
                    'first_name' => $payload['first_name'],
                    'last_name' => $payload['last_name'],
                    'email' => $payload['email'],
                ],
            ])
        ;
    }

    public function test_validates_email_type()
    {
        $manager = factory(Manager::class)->create();

        $payload = [
            'id' => $manager->id,
            'email' => 'INVALID_EMAIL_ADDRESS',
        ];

        $this->json('PUT', route('managers.update', $payload))
            ->assertStatus(422)
            ->assertJson([
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

        $manager = factory(Manager::class)->create();

        $payload = [
            'id' => $manager->id,
            'email' => str_repeat('a', $maxLength - strlen('_long@email.com') + 1) . '_long@email.com',
        ];

        $this->json('PUT', route('managers.update', $payload))
            ->assertStatus(422)
            ->assertJson([
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
