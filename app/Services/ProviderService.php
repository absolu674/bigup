<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Services\FileStorageService;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class ProviderService
{
    protected FileStorageService $fileStorageService;

    public function __construct()
    {
        $this->fileStorageService = new FileStorageService('public', 'avatars');
    }
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['message' => "Cannot initialize connextion with $provider."], 500);
        }
        $user = User::where('email', $socialUser->getEmail())->first();
        if ($user) {
            return response()->json(['error' => 'This email still exists on our system.'], 400);
        } else {
            
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => bcrypt(Str::random(16)),
            ]);

            $this->fileStorageService->downloadAndStoreImage($socialUser->getAvatar(), $user->id);
        }

        $token = auth()->attempt([
            "email" => $user->email,
            "password" => $user->password
        ]);

        return ApiResponseClass::respondWithToken($token);

    }
}
