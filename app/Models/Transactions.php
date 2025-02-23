<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionsFactory> */
    use HasFactory;

    protected $fillable = [
        'dedication_id',
        'amount',
        'phone_payment',
        'status',
        'mode_paiement',
        'transaction_ref',
        'bill_id'
    ];
    protected $casts = [
        'status' => \App\Enums\TransactionStatus::class,
        'mode_paiement' => \App\Enums\PaymentMethod::class,
    ];

    public function dedication()
    {
        return $this->belongsTo(Dedication::class);
    }
}
