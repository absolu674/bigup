<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\DedicationRequest;
use App\Http\Requests\RejectDedicationRequest;
use App\Http\Requests\ShipDedicationRequest;
use App\Http\Resources\DedicationResource;
use App\Repositories\DedicationRepository;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;

class DedicationController extends Controller
{
    private $dedicationRepository;
    private $paymentService;

    public function __construct(DedicationRepository $dedicationRepository, PaymentService $paymentService)
    {
        $this->dedicationRepository = $dedicationRepository;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $dedications = $this->dedicationRepository->index();
        return ApiResponseClass::sendResponse(
            DedicationResource::collection($dedications),
            'Dedications retrieved successfully'
        );
    }

    public function store(DedicationRequest $request)
    {
        try {
            DB::beginTransaction();            
            $request->validated();
            $dedication = $this->dedicationRepository->createDedication($request);
            $responsePay = $this->paymentService->pay($dedication, $request);
            $dedication = array(new DedicationResource($dedication));
            DB::commit();
            return ApiResponseClass::sendResponse(
                array_merge(["dedication_details" => $dedication], ["payment_detail" => $responsePay]),
                'Dedication created successfully'
            );
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e, $e->getMessage());
        }
    }

    public function show($slug)
    {
        $dedication = $this->dedicationRepository->getDedicationBySlug($slug);
        return ApiResponseClass::sendResponse(
            new DedicationResource($dedication),
            'Dedication retrieved successfully'
        );
    }

    public function accept($slug)
    {
        $dedication = $this->dedicationRepository->acceptDedication($slug);
        return ApiResponseClass::sendResponse(
            new DedicationResource($dedication),
            'Dedication accepted successfully'
        );
    }

    public function reject(RejectDedicationRequest $request, $slug)
    {
        DB::beginTransaction();
        $request->validated();
        $dedication = $this->dedicationRepository->rejectDedication($slug, $request->message);
        DB::commit();
        return ApiResponseClass::sendResponse(
            new DedicationResource($dedication),
            'Dedication rejected successfully'
        );
    }

    public function ship(ShipDedicationRequest $request, $slug)
    {
        DB::beginTransaction();
        $request->validated();
        $dedication = $this->dedicationRepository->shipDedication($slug, $request);
        DB::commit();
        return ApiResponseClass::sendResponse(
            new DedicationResource($dedication),
            'Dedication shiped successfully'
        );
    }
}
