<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Enums\LoginProvider;
use App\Enums\UserType;
use App\Events\RegisterArtistEvent;
use App\Http\Requests\RegisterArtistRequest;
use App\Http\Requests\RegisterClientRequest;
use App\Models\User;
use App\Models\UserArtistCategory;
use App\Services\FileStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    protected $fileStorageService;
    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function registerUser(RegisterArtistRequest|RegisterClientRequest $request, UserType $userType)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $plainPassword = $data['password'];
            $data['password'] = Hash::make($data['password']);
            $data['type'] = $userType->value;
            $data = array_merge($data,['provider' => LoginProvider::BASIC->value]);
            $user = User::create($data);
            $user->assignRole('client');
            if($userType->value == UserType::ARTIST->value)
            {
                $user->syncRoles('admin');
                $this->setArtistData($request, $user);
            }
    
            if($request->hasFile('photo'))
            {
                $this->fileStorageService->setDirectory('avatars');
                $fichier = $this->fileStorageService->storeFile($request->file('photo'), $user->id);
                $this->fileStorageService->storePhotoProfile($fichier->id, $user->id);
            }

            $token = auth()->attempt([
                'email'    => $data['email'],
                'password' => $plainPassword,
            ]);
            DB::commit();
            return ApiResponseClass::respondWithToken($token);
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e->getMessage());
        }
    }

    public function setArtistData($request, $user)
    {
        $user->verified = false;
        foreach($request->categories as $category){
            UserArtistCategory::create([
                'user_id' => $user->id,
                'artist_category_id' => $category,
            ]);
        }
        event(new RegisterArtistEvent($user));
    }

    public function registerClient(RegisterClientRequest $request): JsonResponse
    {
        return $this->registerUser($request, UserType::CLIENT);
    }
    
    public function registerArtist(RegisterArtistRequest $request): JsonResponse
    {        
        return $this->registerUser($request, UserType::ARTIST);
    }

}
