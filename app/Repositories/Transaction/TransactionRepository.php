<?php

namespace App\Repositories\Transaction;

use GuzzleHttp\Psr7\Request;
use LaravelEasyRepository\Repository;

interface TransactionRepository extends Repository
{

    // Write something awesome :)
    public function getAll($request);
    public function findOrFail($id);
    public function create($date);
}
