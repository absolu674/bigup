<?php

use App\Models\Artist;
use App\Models\Client;
use App\Models\Dedication;
use App\Models\DedicationType;
use App\Repositories\DedicationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;
use function Pest\Faker\fake;

// uses(RefreshDatabase::class);

it('store dedication', function () {
    $data = [
        'dedication_type_id' => DedicationType::inRandomOrder()->first()->id,
        'artist_id' => Artist::inRandomOrder()->first()->id,
        'user_id' => Client::inRandomOrder()->first()->id,
        'message' => fake()->sentence,
    ];
    
    $repository = new DedicationRepository(new Dedication());
    $dedication = $repository->createDedication($data);

    expect($dedication)->not->toBeNull()
        ->and($dedication->message)->toBe($data['message']);
});

it('can retrieve a dedication by id', function () {

    $dedication = Dedication::factory()->create();
    $repository = new DedicationRepository(new Dedication());
    $foundDedication = $repository->getDedicationById($dedication->id);

    expect($foundDedication)->not->toBeNull()
        ->and($foundDedication->id)->toBe($dedication->id)
        ->and($foundDedication->user_id)->toBe($dedication->user_id);
});