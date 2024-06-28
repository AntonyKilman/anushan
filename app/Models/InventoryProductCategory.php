<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProductCategory extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'inventory_product_categories';
}
