<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommisionCustomerModel extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'commision_customer';
}
