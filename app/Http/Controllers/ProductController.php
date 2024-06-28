<?php

namespace App\Http\Controllers;

use App\Models\InventoryIndoorTransfer;
use App\Models\Product;
use App\Models\ProductReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DNS1D;
use App\Models\account_profit_loss;
use App\Models\MainAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::orderBy('id','DESC')->get();
        return view('sales.product',compact('products'));
    }
    //view products in
    // public function indexProductIn()
    // {
    //     //Group by transsection id
    //     $products=Product::select('purchase_order_id', 'transection_id', 'quantity', 'status', 'expire_date','created_at')
    //     ->orderBy('created_at','DESC')
    //     ->get();
    //     $unique_products = $products->unique('transection_id');
    //     //calculate total product quantity of transection
    //     foreach($unique_products as $unique_product){
    //         $quantity = 0;
    //         foreach($products->where('purchase_order_id', $unique_product['purchase_order_id']) as $product){
    //             $quantity = $quantity + $product['quantity'];
    //         }
    //         $unique_product['quantity'] = $quantity;

    //     }
    //     //return $unique_products;

    //     return view('foodcity.productIn',compact('products','unique_products'));
    // }

    //view transite products by transection id
    // public function viewProductIn($id){

    //     $products=Product::where('transection_id',$id)->get();
    //     return view('foodcity.productInView',compact('products'));
    // }

    //view pending products
    public function indexProductIn(){

        $products=Product::where('status',0)->get();
        return view('sales.productInView',compact('products'));
    }

    //approve transection indivual products
    public function approveProductIn(Request $request){

        $user=Auth::user()->id;
        $bar=(Carbon::now()->timestamp)-1600000000;

        $products=Product::find($request->id);
        $products->status =1;
        $products->bar_code=$bar;
        $products->sales_price=$request->sell_price;
        $products->save();
        Storage::disk('public')->put($bar.'.png',base64_decode(DNS1D::getBarcodePNG($bar, 'I25')));

        $InventoryIndoorTransfer = InventoryIndoorTransfer::find($products->transection_id);
        $InventoryIndoorTransfer->user_id =  $user;
        $InventoryIndoorTransfer->approved_by = $user;
        $InventoryIndoorTransfer->status = 1;
        $InventoryIndoorTransfer->save();


        // store account_profit_loss table created by sivakaran----------------------------------

        // calculate total purchase price
        $products=Product::find($request->id);
        $totalPurchasePrice = $products -> purchase_price * $products -> quantity;


        // store purchase items in account profit loss account table
        $account_profit_loss = new account_profit_loss();
        $account_profit_loss -> total_purchase_price = $totalPurchasePrice;
        $account_profit_loss -> department_id = 1;
        $account_profit_loss -> type = "Foodcity purchase Product";
        $account_profit_loss -> cash_out = $totalPurchasePrice;
        $account_profit_loss -> connected_id =$products -> id;
        $account_profit_loss -> date = Carbon::now();
        $account_profit_loss -> is_delete = 0;
        $account_profit_loss -> save();

        return view('sales.print_barcode',compact('products'));

        //return back()->with('sucess', 'Successfully updated!');
    }

    //reject transection indivual products
    public function rejectProductIn(Request $request){
        $user=Auth::user()->id;

        $products=Product::find($request->id);
        $products->status =2;
        $products->save();

        $InventoryIndoorTransfer = InventoryIndoorTransfer::find($products->transection_id);
        $InventoryIndoorTransfer->user_id =  $user;
        $InventoryIndoorTransfer->approved_by = $user;
        $InventoryIndoorTransfer->status = 2;
        $InventoryIndoorTransfer->save();

        return back()->with('sucess', 'Successfully updated!');
    }

    //products return page view
    public function indexProductReturn(){

        $return_products= DB::table('foodcity_product_returns')
        ->select('product_id', DB::raw('SUM(return_qty) as returns_quantity'))
        ->groupBy('product_id')
        ->get();

        $sales_products= DB::table('foodcity_product_sales')
        ->select('product_id', DB::raw('SUM(quantity) as sales_quantity'))
        ->groupBy('product_id')
        ->get();

        $products= DB::table('food_city_products')
        ->where('food_city_products.status','=',1)
        ->orderBy('food_city_products.created_at','desc')
        ->get();

        foreach($products as $product){

            if($return_products->count() > 0){
                if($return_products->where('product_id', $product->id)->first() && $return_products->where('product_id', $product->id)->first()->returns_quantity){
                    $product->returns_quantity = $return_products->where('product_id', $product->id)->first()->returns_quantity;
                }else{
                    $product->returns_quantity = 0;
                }
            }else{
                $product->returns_quantity = 0;
            }

            if($sales_products->count() > 0){
                if($sales_products->where('product_id', $product->id)->first() && $sales_products->where('product_id', $product->id)->first()->sales_quantity){
                    $product->sales_quantity = $sales_products->where('product_id', $product->id)->first()->sales_quantity;
                }else{
                    $product->sales_quantity = 0;
                }
            }else{
                $product->sales_quantity = 0;
            }
        }


        //get resons
        $resons =DB::table('inventory_return_reasons')->get();

        return view('sales.productReturn',compact('products','resons'));
    }

    //products item return methord
    public function indexProductItemReturn(Request $request){

        $userId=Auth::user()->id;

        $products=new ProductReturn();
        $products->product_id=$request->id;
        $products->purchase_order_id=$request->purchase_order_id;
        $products->return_qty=$request->quantity;
        $products->return_reason_id=$request->reason_id;
        $products->description=$request->description;
        $products->item_id =$request->id;
        $products->status=1;
        $products->user_id=$userId;
        $products->save();

        // store data for accounts new 

        $purchase_return_account = new MainAccount();
        $purchase_return_account -> credit = $request->purchase_price* $request->quantity;
        $purchase_return_account -> dept_id = 1; //foodcity
        $purchase_return_account -> date = Carbon::now()->format('Y-m-d');
        $purchase_return_account -> account_type = 4000;
        $purchase_return_account -> description  = "Inventory Return";
        $purchase_return_account -> connected_id = $products->id;
        $purchase_return_account -> sub_category = $request->id;
        $purchase_return_account -> purchase_amount = $request->purchase_price* $request->quantity;
        $purchase_return_account -> table_id = 6; //purchase return account
        $purchase_return_account -> save();

        $cash_account = new MainAccount();
        $cash_account -> debit = $request->purchase_price* $request->quantity;
        $cash_account -> dept_id = 1; //foodcity
        $cash_account -> date = Carbon::now()->format('Y-m-d');
        $cash_account -> account_type = 1000;
        $cash_account -> description = "Inventory Return";
        $cash_account -> sub_category = $request->id;
        $cash_account -> connected_id = $products -> id;
        $cash_account -> purchase_amount = $request->purchase_price* $request->quantity;
        $cash_account -> table_id = 1; //cash account
        $cash_account -> save();

        // for inventory
        $cash_account = new MainAccount();
        $cash_account -> credit = $request->purchase_price* $request->quantity;
        $cash_account -> dept_id = 6; //inventory
        $cash_account -> date = Carbon::now()->format('Y-m-d');
        $cash_account -> account_type = 1000;
        $cash_account -> description = "Angadi Return";
        $cash_account -> connected_id = $products -> id;
        $cash_account -> sub_category = $request->id;
        $cash_account -> purchase_amount = $request->purchase_price* $request->quantity;
        $cash_account -> table_id = 1; //cash account
        $cash_account -> save();

        $sales_return_account = new MainAccount();
        $sales_return_account -> debit = $request->purchase_price* $request->quantity;
        $sales_return_account -> dept_id = 6; //inventory
        $sales_return_account -> date = Carbon::now()->format('Y-m-d');
        $sales_return_account -> account_type = 1000;
        $sales_return_account -> description = "Angadi Return";
        $sales_return_account -> connected_id = $products -> id;
        $sales_return_account -> sub_category = $request->id;
        $sales_return_account -> purchase_amount = $request->purchase_price* $request->quantity;
        $sales_return_account -> table_id = 3; //sales Return account
        $sales_return_account -> save();



        // store account profit loss table
        // $account_profit_loss= new  account_profit_loss();
        // $account_profit_loss -> total_purchase_price = $request->purchase_price* $request->quantity;
        // $account_profit_loss -> department_id = 1;
        // $account_profit_loss -> type = "Foodcity Product Return";
        // $account_profit_loss -> cash_in = $request->purchase_price* $request->quantity;
        // $account_profit_loss -> date =Carbon::now();
        // $account_profit_loss -> connected_id =$products->id;
        // $account_profit_loss -> is_delete = 0;
        // $account_profit_loss -> save();


        return back()->with('sucess', 'Successfully updated!');
    }

    //reprint barcode
    public function reprintBarcode($id){
        $products=Product::find($id);

        return view('sales.reprint_barcode',compact('products'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $salesPrice = $request->input('salesPrice');
        $id = $request->input('id');

        $product = Product::find($id);
        $product->sales_price = $salesPrice;
        $product->save();

        return back()->with('sucess', 'Successfully updated!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
 