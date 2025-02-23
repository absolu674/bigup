<?php

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('register a client', function () {
    $data = [
        'name' => 'Client Name',
        'email' => 'client@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ];

    $response = postJson(route('register.client'), $data);

    $response->assertStatus(200)
             ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
             ]);

    $user = User::where('email', 'client@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->type)->toBe(UserType::CLIENT->value);
});

it('register an artist', function () {
    $data = [
        'name' => 'Artist Name',
        'email' => 'artist@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ];

    $response = postJson(route('register.artist'), $data);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'access_token',
                 'token_type',
                 'expires_in',
             ]);

    $user = User::where('email', 'artist@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->type)->toBe(UserType::ARTIST->value);
});
