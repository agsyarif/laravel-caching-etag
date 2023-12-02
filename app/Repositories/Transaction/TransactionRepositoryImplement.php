<?php

namespace App\Repositories\Transaction;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Transaction;
use GuzzleHttp\Psr7\Request;

class TransactionRepositoryImplement extends Eloquent implements TransactionRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
    public function create($data)
    {
        return $this->model->create($data);
    }

    public function getAll($request)
    {
        $sortDirection = $request->sort_dir ?? 'desc';
        $softField = $request->sort_field ?? 'id';
        $limit = $request->limit ?: null;

        $transaction = $this->model
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where(function ($q) use ($request) {
                        $q->where('customer', 'like', '%' . $request->q . '%')
                            ->orWhere('transaction_code', $request->q);
                    });
                });
            })
            ->when($request->date, function ($q) use ($request) {
                $q->whereDate('created_at', $request->date);
            })
            ->orderBy($softField, $sortDirection)
            ->orderByDesc('created_at');

        if ($limit) {
            $transaction = $transaction->paginate($limit)->withQueryString();
        } else {
            $transaction = $transaction->get();
        }

        return $transaction;
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }
}
