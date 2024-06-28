<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accountChequePayment extends Model
{
    use HasFactory;
    public $table ="account_cheque_payments";
    protected $primaryKey = 'id';
    public $timestamps =true;
}
