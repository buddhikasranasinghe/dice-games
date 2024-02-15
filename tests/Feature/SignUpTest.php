<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Fluent\AssertableJson;

class SignUpTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidPayloadProvider
     */
    public function when_invalid_payload_given(array $payload, array $errors)
    {
        $response = $this->signUp($payload);

        $this->assertUnprocessableDueToInvalidPayload($response, $errors);
    }

    /** @test */
    public function when_valid_payload_given()
    {
        $payload = [
            'username' => 'buddhika@gmail.com',
            'password' => '123123123',
            'password_confirmation' => '123123123'
        ];
        $response = $this->signUp($payload);

        $this->assertUserRegistered($response, $payload);
    }

    protected function signUp(array $payload): TestResponse
    {
        return $this->postJson('api/sign-up', $payload);
    }

    public static function invalidPayloadProvider(): array
    {
        return [
            'empty username' => [
                'payload' => [
                    'username' => '',
                    'password' => '123123123',
                    'password_confirmation' => '123123123'
                ],
                'errors' => [
                    'username' => ['The username field is required.']
                ]
            ],
            'invalid username' => [
                'payload' => [
                    'username' => 'saman',
                    'password' => '123123123',
                    'password_confirmation' => '123123123'
                ],
                'errors' => [
                    'username' => ['The username field must be a valid email address.']
                ]
            ],
            'empty password' => [
                'payload' => [
                    'username' => 'saman@gmail.com',
                    'password' => '',
                    'password_confirmation' => '123123123'
                ],
                'errors' => [
                    'password' => ['The password field is required.'],
                    'password_confirmation' => [
                        'The password confirmation field must match password.'
                    ]
                ]
            ],
            'missing required password length' => [
                'payload' => [
                    'username' => 'saman@gmail.com',
                    'password' => '123',
                    'password_confirmation' => '123'
                ],
                'errors' => [
                    'password' => ['The password field must be at least 6 characters.']
                ]
            ],
            'empty confirm password' => [
                'payload' => [
                    'username' => 'buddhika.ranasinghe@gmail.com',
                    'password' => '123123123',
                    'password_confirmation' => ''
                ],
                'errors' => [
                    'password_confirmation' => ['The password confirmation field is required.']
                ]
            ],
            'mismatch password and confirm password' => [
                'payload' => [
                    'username' => 'buddhika.ranasinghe@yahoo.com',
                    'password' => '123123',
                    'password_confirmation' => '123123123'
                ],
                'errors' => [
                    'password_confirmation' => ['The password confirmation field must match password.']
                ]
            ],
        ];
    }

    protected function assertUnprocessableDueToInvalidPayload(TestResponse $response, array $errors): void
    {
        $response->assertUnprocessable();

        $this->assertSame(
            [
                'message' => 'Oops... we encountered an issue.',
                'errors' => $errors
            ],
            $response->json()
        );
    }

    protected function assertUserRegistered(TestResponse $response, array $expectedUser): void
    {
        $response->assertOk();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['email' => $expectedUser['username']]);

        $response->assertJson(function (AssertableJson $json) use ($expectedUser) {
            $json->where('user.email', $expectedUser['username']);
            $json->where('user.status', 'active')->etc();
        });

        $this->assertNotNull($response->json('user.id'));
    }
}
