<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private $categoryRepository;
     
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $data = $this->categoryRepository->index();
        return ApiResponseClass::sendResponse(CategoryResource::collection($data),'',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try{
            DB::beginTransaction();
            $datum = $this->categoryRepository->store($request->validated());
            DB::commit();
            return ApiResponseClass::sendResponse(
                new CategoryResource($datum),
                'La catégorie a été enregistrée avec succès',
                200);
        }catch(\Exception $exception){
            ApiResponseClass::rollback($exception->getMessage(), $exception->getMessage());
        }
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
    public function update(Request $request, string $alias)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
