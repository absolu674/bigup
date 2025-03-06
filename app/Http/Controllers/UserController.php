<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
use App\Repositories\ArtistRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

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
        return ApiResponseClass::sendResponse(new UserResource($artist),'',200);
    }

    public function showClient($client)
    {
        $client = $this->clientRepository->getClientByAlias($client);
        return ApiResponseClass::sendResponse(new UserResource($client),'',200);
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

    public function me(): JsonResponse
    {
        return ApiResponseClass::sendResponse(new UserResource(auth()->user()),'',200);
    }
}
