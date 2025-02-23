<?php

namespace App\Repositories;

use App\Models\Transactions;

class TransactionRepository
{
    private $transaction;
    public function __construct(Transactions $transaction)
    {
        $this->transaction = $transaction;
    }
    
    public function index()
    {
        return $this->transaction->all();
    }

    public function createTransaction(array $data)
    {
        return $this->transaction->create($data);
    }

    public function update($id, array $data)
    {
        $transaction = $this->transaction->findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

}
