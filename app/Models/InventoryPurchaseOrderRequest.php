<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchaseOrderRequest extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'inventory_purchase_order_requests';
}
