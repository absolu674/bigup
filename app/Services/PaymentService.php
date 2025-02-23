<?php

namespace App\Services;

use App\Models\Dedication;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    private $baseUrl;
    private $token;
    private $billId;
    private $transactionRepository;


    public function __construct(TransactionRepository $transactionRepository) {
        $this->baseUrl = "https://staging.billing-easy.net/shap/api/v1/merchant/";
        $this->token = $this->getToken();
        $this->transactionRepository = $transactionRepository;
    }
    public function pay(Dedication $dedication, Request $request): JsonResponse
    {
        $paymentDetails = $this->getPaymentDetails($request, $dedication);            
        $responseCreateBill = $this->createBill($paymentDetails);
        $ebills = $responseCreateBill->json()['response']['e_bills'][0];
        $this->transactionRepository->createTransaction([
            'dedication_id' => $dedication->id,
            'amount' => $paymentDetails['amount'],
            'phone_payment' => $paymentDetails['numero'],
            'mode_paiement' => $paymentDetails['payment_system_name'],
            'transaction_ref' => $paymentDetails['reference'],
            'bill_id' => $ebills['bill_id']
        ]);
        $responseSendPush = $this->sendPush($paymentDetails["numero"], $this->billId, $paymentDetails["payment_system_name"]);

        return response()->json([
            'status' => 200,
            'status_message' => 'success',
            'message' => 'paiement initialisé',
            'bill' =>  $ebills,
            'push' => $responseSendPush,
        ]);
    }

    public function getToken()
    {
        $response = Http::post($this->baseUrl."auth", [
            "api_id" => env('API_ID'),
            "api_secret" => env("API_SECRET")
        ]);
        return $response->json()['access_token'];
    }

    public function createBill($paymentDetails)
    {
        $response = Http::withHeader("Authorization", "Basic ".$this->token)->post($this->baseUrl."create-invoice", [
            "payer_msisdn" => $paymentDetails["numero"],
            "payer_email" => $paymentDetails["email"],
            "payer_last_name" => $paymentDetails['last_name'],
            "payer_first_name" => $paymentDetails['first_name'],
            "amount" => $paymentDetails['amount'],
            "payment_system_name" => $paymentDetails["payment_system_name"],
            "label" => config('app.name'),
            "short_description" => $paymentDetails['short_description']
        ]);
        Log::info("RESPONSE BILL CREATE ".$response);
        $this->billId = $response->json()['response']['e_bills'][0]['bill_id'];
        return $response;
    }

    public function sendPush($numero, $billId, $paymentSystemName)
    {
        $response = Http::withHeader("Authorization", "Basic ".$this->token)->post($this->baseUrl."send-ussd-push", [
            "payment_system_name" => $paymentSystemName,
            "payer_msisdn" => $numero,
            "bill_id" => $billId
        ]);
        
        return $response;
    }

    public function kyc($numero, $paymentSystemName)
    {
        $response = Http::withHeader("Authorization", "Basic ".$this->token)
            ->get($this->baseUrl."kyc?payment_system_name=$paymentSystemName&msisdn=$numero");
            
        return $response->json();
    }

    public function payout($payoutDetail, $user, $payout)
    {
        $reference = config('app.name') .'-'. $user.'-'. Str::random(8);
        $response = Http::withHeader("Authorization", "Basic ".$this->token)
            ->post("https://staging.billing-easy.net/shap/api/v1/merchant/payout",[
            "payment_system_name" => $payoutDetail["payment_system_name"],
            "payout" => [
                "payee_msisdn" => $payoutDetail["payee_msisdn"],
                "amount" => $payoutDetail["amount"],
                "external_reference" => $reference,
                "payout_type" => "refund",
                "transaction_type" => "B2B"
            ]
        ]);

        $status = array_key_exists('response', $response->json()) ? $response->json()['response']['state'] : $response->json()['error'];
        $message = array_key_exists('response', $response->json()) ? serialize($response->json()['response']) : $response->json()['error_description'];
        
        $payout->update(   [
            'payout_status' => $status,
            'message' => $message
        ]);   
    }

    public function balance()
    {
        $response = Http::withHeader("Authorization", "Basic ".$this->token)
            ->get($this->baseUrl."balance");
        return $response->json();
    }

    public function verifyBillState(string $invoiceNumber): array
    {
        $response = Http::withHeader("Authorization", "Basic ".$this->token)
            ->post("https://staging.billing-easy.net/shap/api/v1/query-invoice/",
        [
            "invoice_number" => "$invoiceNumber"            
        ]);
        return $response->json();
    }

    public function airtelBalance()
    {
        $operatorDetails = $this->balance()['data'][0];
        $solde = $operatorDetails['payment_system_name'] == 'airtelmoney' ? $operatorDetails['amount'] : "invalid";
        return $solde;
    }

    public function getPaymentDetails($request, Dedication $dedication)
    {
        $client = $dedication->user()->first();
        $artist = $dedication->artist()->first();
        $numero = $request->use_phone_user_for_payment ? $client->pluck('phone') : $request->phone_payment;
        $paymentSystemName = $this->getPaymentSystemName($numero);
        $paymentDetails = [
            "numero" => $numero,
            "email" => $request->is_self ? $client->email :  $request->email,
            "last_name" => $request->is_self ? $client->lastname : $request->lastname,
            "first_name" => $request->is_self ? $client->firstname : $request->lastname,
            "amount" => $artist->dedication_price,
            "payment_system_name" => $paymentSystemName,
            "short_description" => "Dédicace de ".$client->firstname." ".$client->lastname,
            "reference" => config('app.name') .'-'. Str::uuid7()
        ];
        return $paymentDetails;
    }

    public function getPaymentSystemName($numero)
    {
        $operator = substr($numero, 1, 1);
        $paymentSystemName = $operator == '7' ? 'airtelmoney' : ($operator == '6' ? 'moovmoney4' : 'invalid');
        return $paymentSystemName;
    }
    
}
