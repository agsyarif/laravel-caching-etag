<?php

namespace App\Services\Transaction;

use LaravelEasyRepository\BaseService;

interface TransactionService extends BaseService
{

    // Write something awesome :)
    public function getAll($request);
    public function findOrFail($id);
    public function create($data);
}
