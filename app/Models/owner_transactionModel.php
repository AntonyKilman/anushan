<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class owner_transactionModel extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'owners_transaction';
}
