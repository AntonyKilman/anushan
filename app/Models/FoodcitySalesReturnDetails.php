<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodcitySalesReturnDetails extends Model
{
    use HasFactory;

    protected $table = 'foodcity_sales_return_details';
    protected $primaryKey = 'id';
}
