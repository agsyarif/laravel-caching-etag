<?php

namespace App\Services\Transaction;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Str;
use LaravelEasyRepository\Service;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\TransactionDetail\TransactionDetailRepository;
use Illuminate\Support\Facades\DB;

class TransactionServiceImplement extends Service implements TransactionService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;
  protected $detailRepository;
  protected $productRepository;

  public function __construct(TransactionRepository $mainRepository, TransactionDetailRepository $detail, ProductRepository $product)
  {
    $this->mainRepository = $mainRepository;
    $this->detailRepository = $detail;
    $this->productRepository = $product;
  }

  // Define your custom methods :)
  public function create($data)
  {
    $transactionCode =  Str::random(10);
    [$transactionDetails, $totalAmount, $totalPrice] = $this->loopingData($data);

    $newTransaction = [
      'customer' => $data['customer'],
      'transaction_code' => $transactionCode,
      'total_amount' => $totalAmount,
      'total_price' => $totalPrice,
      'payment_method' => $data['payment_method']
    ];

    return DB::transaction(function () use ($newTransaction, $transactionDetails) {
      $transaction = $this->mainRepository->create($newTransaction);

      $transactionDetails = array_map(function ($transactionDetail) use ($transaction) {
        $transactionDetail['transaction_id'] = $transaction->id;
        return $transactionDetail;
      }, $transactionDetails);

      $this->detailRepository->insert($transactionDetails);

      return $transaction;
    });
  }

  protected function loopingData($data)
  {

    $totalAmount = 0;
    $totalPrice = 0;
    $transactionDetails = [];

    foreach ($data['products'] as $reqProduct) {
      $rAmount = $reqProduct['amount'];
      $rProductId = $reqProduct['product_id'];

      $rProduct = $this->productRepository->findOrFail($rProductId);

      $rTotalPrice = $rProduct->price * $rAmount;
      $transactionDetails[] = [
        'product_id' => $rProductId,
        'product' => $rProduct->name,
        'amount' => $rAmount,
        'price' => $rProduct->price,
        'total_price' => $rTotalPrice
      ];

      $totalAmount += $rAmount;
      $totalPrice += $rTotalPrice;
    }

    return [$transactionDetails, $totalAmount, $totalPrice];
  }
}
