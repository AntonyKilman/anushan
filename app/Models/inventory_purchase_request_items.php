<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventory_purchase_request_items extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'inventory_purchase_request_items';
}
