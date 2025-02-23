<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Resources\DedicationTypeResource;
use App\Repositories\DedicationTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DedicationTypeController extends Controller
{
    private $dedicationTypeRepository;

    public function __construct(DedicationTypeRepository $dedicationTypeRepository)
    {
        $this->dedicationTypeRepository = $dedicationTypeRepository;
    }

    public function index()
    {
        return $this->dedicationTypeRepository->index();
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $datum = $this->dedicationTypeRepository->createDedication($request->validated());
            DB::commit();
            return ApiResponseClass::sendResponse(
                new DedicationTypeResource($datum),
                'La catégorie a été enregistrée avec succès',
                200);
        }catch(\Exception $exception){
            ApiResponseClass::rollback($exception->getMessage(), $exception->getMessage());
        }
    }
}
