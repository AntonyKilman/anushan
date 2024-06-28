<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBrandSubCategory extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'inventory_brand_sub_categories';
}
