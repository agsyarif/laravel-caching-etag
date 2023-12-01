<?php

namespace App\Repositories\TransactionDetail;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\TransactionDetail;

class TransactionDetailRepositoryImplement extends Eloquent implements TransactionDetailRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(TransactionDetail $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
    public function insert($data)
    {
        return $this->model->insert($data);
    }
}
