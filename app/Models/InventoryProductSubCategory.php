<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProductSubCategory extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'inventory_product_sub_categories';
}
