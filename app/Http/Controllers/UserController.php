<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\ArtistRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private ArtistRepository $artistRepository;
    private ClientRepository $clientRepository;
    private CategoryRepository $categoryRepository;
    
    public function __construct(        
        UserRepository $userRepository, 
        ArtistRepository $artistRepository, 
        ClientRepository $clientRepository,
        CategoryRepository $categoryRepository)
    {
        $this->userRepository = $userRepository;
        $this->artistRepository = $artistRepository;
        $this->clientRepository = $clientRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllArtist()
    {
        $data = $this->artistRepository->index();
        return ApiResponseClass::sendResponse(UserResource::collection($data),'',200);
    }

    public function showArtist($artist)
    {
        $artist = $this->artistRepository->getArtistByAlias($artist);
        return ApiResponseClass::sendResponse(UserResource::collection($artist),'',200);
    }

    public function getAllClient()
    {
        $data = $this->clientRepository->index();
        return ApiResponseClass::sendResponse(UserResource::collection($data),'',200);
    }

    public function getAllArtistByCategries()
    {
        $data = $this->categoryRepository->getAllArtistByCategry();
        return ApiResponseClass::sendResponse(CategoryResource::collection($data),'',200);
    }

    public function getArtistByCategry($slug)
    {
        $data = $this->categoryRepository->getArtistByCategry($slug);
        return ApiResponseClass::sendResponse(CategoryResource::collection($data),'',200);
    }
    
    public function index()
    {
        $data = $this->userRepository->index();

        return ApiResponseClass::sendResponse(UserResource::collection($data),'',200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function me(?string $user): JsonResponse
    {
        $data = $user == null ? auth()->user() : User::where('slug', $user)->first();
        return ApiResponseClass::sendResponse(UserResource::collection($data),'',200);
    }
}
