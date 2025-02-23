<?php

namespace App\Repositories;

use App\Models\DedicationType;

class DedicationTypeRepository
{
    private $type;
    /**
     * Create a new class instance.
     */
    public function __construct(DedicationType $type)
    {
        $this->type = $type;
    }

    public function index()
    {
        return $this->type->all();
    }

    public function createDedication($data)
    {
        return $this->type->create($data);
    }
}
