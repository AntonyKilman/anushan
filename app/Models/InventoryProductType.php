<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProductType extends Model
{
    use HasFactory;
    public $timeStamps=true;
    public $table='inventory_product_types';
}
