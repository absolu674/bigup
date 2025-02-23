<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Repositories\TransactionRepository;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $paymentService;
    private $transactionRepository;

    public function __construct(PaymentService $paymentService, TransactionRepository $transactionRepository)
    {
        $this->paymentService = $paymentService;
        $this->transactionRepository = $transactionRepository;
    }

    public function show($billId)
    {
        $transaction = $this->paymentService->verifyBillState($billId);
        return ApiResponseClass::sendResponse($transaction, "Transaction returned successfully", 200);
    }

    public function index()
    {
        $transactions = $this->transactionRepository->index();
        return ApiResponseClass::sendResponse(
            $transactions,
            'Transactions retrieved successfully',
            200
        );
    }

    public function updateStatus(Request $request)
    {
        $transaction = $this->transactionRepository->update($request->transaction_id, ['status' => $request->status]);
        return ApiResponseClass::sendResponse($transaction, "Transaction status updated successfully", 200);
    }
}
