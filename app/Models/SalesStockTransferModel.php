<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesStockTransferModel extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'sales_stock_transfer';
}
