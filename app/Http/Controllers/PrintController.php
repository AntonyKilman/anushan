<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;

class PrintController extends Controller
{
    //
    public function printDocs()
    {  
        //return "Hii";
       try{
      // Set params
$mid = '123123456';
$store_name = 'YOURMART';
$store_address = 'Mart Address';
$store_phone = '1234567890';
$store_email = 'yourmart@email.com';
$store_website = 'yourmart.com';
$tax_percentage = 10;
$transaction_id = 'TX123ABC456';
$currency = 'Rp';
$image_path = 'logo.png';

// Init printer
$printer = new ReceiptPrinter;
$printer->init(
    config('receiptprinter.connector_type'),
    config('receiptprinter.connector_descriptor')
);

// Set store info
$printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);

// Set currency
$printer->setCurrency($currency);

// Set request amount
// $printer->setRequestAmount($request_amount);

// Set transaction ID
$printer->setTransactionID($transaction_id);

// Set logo
// Uncomment the line below if $image_path is defined
//$printer->setLogo($image_path);


// Print payment request
$printer->printRequest();
        
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
}
/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
