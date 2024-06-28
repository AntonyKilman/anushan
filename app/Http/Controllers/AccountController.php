<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \stdClass;
use Illuminate\Support\Collection;


class AccountController extends Controller
{
    public function AccountBalance($type, $id)
    {
        $to = Carbon::now()->format('Y-m-d');
        $from = Carbon::now()->subMonths(12)->format('Y-m-d');

        $datas = DB::table('account_main_account')
            ->where('account_main_account.dept_id', $id)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->groupBy('account_main_account.table_id')
            ->select(
                'account_main_account.table_id',
                DB::raw('SUM(account_main_account.credit) as total_credit'),
                DB::raw('SUM(account_main_account.debit) as total_debit')
            )
            ->get();

        // calculate cash amount
        $cashes = $datas->where('table_id', 1)->first();
        if ($cashes) {
            $cash = $cashes->total_debit - $cashes->total_credit;
        } else {
            $cash = 0.00;
        }

        // calculate sales amount
        $sales = $datas->where('table_id', 2)->first();
        if ($sales) {
            $sale = $sales->total_credit - $sales->total_debit;
        } else {
            $sale = 0.00;
        }


        // calculate sales return amount
        $sales_returns = $datas->where('table_id', 3)->first();
        if ($sales_returns) {
            $sales_return = $sales_returns->total_debit - $sales_returns->total_credit;
        } else {
            $sales_return = 0.00;
        }

        // calculate debtors amount
        $debtors = $datas->where('table_id', 4)->first();
        if ($debtors) {
            $debtor = $debtors->total_debit - $debtors->total_credit;
        } else {
            $debtor = 0.00;
        }

        // calculate purchase amount
        $purchases = $datas->where('table_id', 5)->first();

        if ($purchases) {
            $purchase = $purchases->total_debit;
        } else {
            $purchase = 0.00;
        }

        // calculate purchase Return amount
        $purchase_returns = $datas->where('table_id', 6)->first();

        if ($purchase_returns) {
            $purchase_return = $purchase_returns->total_credit;
        } else {
            $purchase_return = 0.00;
        }

        // calculate service expense amount
        $service_expenses = $datas->where('table_id', 7)->first();
        if ($service_expenses) {
            $service_expense = $service_expenses->total_debit;
        } else {
            $service_expense = 0.00;
        }


        // calculate basic stock
        $basic_in_stock = DB::table('account_main_account')
            ->where('dept_id', $id)
            ->where('date', '<=', $from)
            ->whereIn('table_id', [3, 5])
            ->sum('purchase_amount');

        $basic_out_stock = DB::table('account_main_account')
            ->where('dept_id', $id)
            ->where('date', '<=', $from)
            ->whereIn('table_id', [2, 6])
            ->sum('purchase_amount');

        $basic_stock = $basic_in_stock - $basic_out_stock;

        // calculate final stock
        $final_in_stock = DB::table('account_main_account')
            ->where('dept_id', $id)
            ->where('date', '<=', $to)
            ->whereIn('table_id', [3, 5])
            ->sum('purchase_amount');

        $final_out_stock = DB::table('account_main_account')
            ->where('dept_id', $id)
            ->where('date', '<=', $to)
            ->whereIn('table_id', [2, 6])
            ->sum('purchase_amount');

        $final_stock = $final_in_stock - $final_out_stock;

        // restaurant estimate their final stock as 0.00
        if ($id == 8) {
            $final_stock = 0.00;
        }

        // calculate accured  service charge amount
        // $accur = DB::table('account_main_account')
        //     ->where('account_main_account.dept_id', $id)
        //     ->where('date', '>=', $from)
        //     ->where('date', '<=', $to)
        //     ->where('table_id', 8)
        //     ->groupBy('account_main_account.table_id', 'category')
        //     ->select(
        //         'account_main_account.table_id',
        //         'category',
        //         DB::raw('SUM(account_main_account.credit) as total_credit'),
        //         DB::raw('SUM(account_main_account.debit) as total_debit')
        //     )
        //     ->get();

        // $accur_servi_charges = $accur->where('category', 2)->first();
        // if ($accur_servi_charges) {
        //     $accur_servi_charge = $accur_servi_charges->total_credit - $accur_servi_charges->total_debit;
        // } else {
        //     $accur_servi_charge = 0.00;
        // }


        // calculate accured  other expense amount
        $accur_oth_expens = $datas->where('table_id', 8)->first();
        if ($accur_oth_expens) {
            $accur_oth_expen = $accur_oth_expens->total_credit - $accur_oth_expens->total_debit;
        } else {
            $accur_oth_expen = 0.00;
        }

        // Non current assets
        $non_curr_assets = DB::table('account_main_account')
            ->join('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
            ->join('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'inventory_products.product_sub_cat_id')
            ->join('inventory_product_categories', 'inventory_product_categories.id', 'inventory_product_sub_categories.product_cat_id')
            ->where('account_main_account.dept_id', $id)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('account_main_account.table_id', 9)
            ->select(
                'account_main_account.table_id',
                'account_main_account.sub_category as product_id',
                'inventory_product_categories.id as pro_cat_id',
                'inventory_product_categories.product_cat_name',
                'account_main_account.debit',
                'account_main_account.connected_id',
                'account_main_account.date',
            )
            ->get();

        $non_current_collection = collect();

        foreach ($non_curr_assets as $asset) {
            $dep_percentage = DB::table('inventory_permanent_asset_transfers')->where('id', $asset->connected_id)->first()->depreciation_persentage;
            $from_date = Carbon::parse(date('Y-m-d', strtotime($to)));
            $days = $from_date->diffInDays($asset->date);
            $deprecian_amount = (floatval($dep_percentage) / 100) * (floatval($days) / 365) * floatval($asset->debit);
            $deprecian_amount = (floatval(number_format($deprecian_amount, 2)));
            $asset->deprecian_amount = $deprecian_amount;

            if ($non_curr_assets[0] == $asset) {
                $non_current_collection->push($asset);
            } else {
                $checkCollection = $non_current_collection->where('pro_cat_id', $asset->pro_cat_id)->first();

                if ($checkCollection) {
                    $checkCollection->debit = floatval($asset->debit) + floatval($checkCollection->debit);
                    $checkCollection->deprecian_amount = floatval($asset->deprecian_amount) + floatval($checkCollection->deprecian_amount);
                } else {
                    $non_current_collection->push($asset);
                }
            }
        }

        $non_current = $non_current_collection->sum('debit');
        $non_current_deprection = $non_current_collection->sum('deprecian_amount');
        $non_current_assent_cost = $non_current - $non_current_deprection;

        // Assign variables
        $purchase_in = $purchase - $purchase_return;
        $cost_of_sales = $basic_stock + $purchase_in - $final_stock;
        $gross_profit = $sale - $sales_return - $cost_of_sales;
        $total_expenses = $service_expense + $non_current_deprection;
        $net_profit = $gross_profit - $total_expenses;
        $current_assets = $final_stock + $cash + $debtor;
        $total_assets = $current_assets + $non_current_assent_cost;
        $equity_and_liabilities = $net_profit;
        $total_equity_and_liabilities = $equity_and_liabilities + $accur_oth_expen;

        switch ($id) {
            case 1:
                $dept = "Ankadi";
                break;
            case 3:
                $dept = "Front Office";
                break;
            case 6:
                $dept = "Main Store";
                break;
            case 8:
                $dept = "Marutham Restaurant";
                break;

            case 9:
                $dept = "Kurinchi One Restaurant";
                break;

            case 10:
                $dept = "Kurinchi Restaurant";
                break;

            case 11:
                $dept = "Pizza Restaurant";
                break;
            default:
                break;
        }

        if ($type == "profit_loss") {

            // this datas for modal
            $sales_datas = DB::table('account_main_account')
                ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.table_id', 2) //sales account
                ->orderBy('account_main_account.date', 'desc');

            switch ($id) {
                    // front office
                case 3:
                    $sales_datas = $sales_datas->leftJoin('main_events', 'main_events.id', 'account_main_account.sub_category')
                        ->leftJoin('main_rentels', 'main_rentels.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.credit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.cheque',
                            'account_main_account.description',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'account_main_account.category',
                            'main_events.en_title as events',
                            'main_rentels.en_title as games'
                        );
                    break;
                    // Inventory
                case 6:
                    $sales_datas = $sales_datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.credit',
                            'account_main_account.date',
                            'account_main_account.description',
                            'inventory_products.product_name',
                        );
                    break;
                    // Restaurant
                case 8:
                    $sales_datas = $sales_datas->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.credit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.cheque',
                            'account_main_account.description',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'account_main_account.category',
                            'employees.f_name as emp_name',
                            'customers.name as hotel_customer_name'
                        );
                    break;

                case 11:
                    $sales_datas = $sales_datas->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.credit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.cheque',
                            'account_main_account.description',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'account_main_account.category',
                            'employees.f_name as emp_name',
                            'customers.name as hotel_customer_name'
                        );
                    break;

                default:
                    $sales_datas = $sales_datas->select(
                        'account_main_account.credit',
                        'account_main_account.date',
                        'account_main_account.cash',
                        'account_main_account.card',
                        'account_main_account.cheque',
                        'account_main_account.description',
                        'account_main_account.credit_amount',
                        'customers.name'
                    );
                    break;
            }

            $sales_datas =  $sales_datas->get();

            $sales_return_datas = DB::table('account_main_account')
                ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                ->leftJoin('account_category', 'account_category.id', 'account_main_account.category')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.table_id', 3) //sales Return account
                ->orderBy('account_main_account.date', 'desc');

            switch ($id) {
                    // Ankadi
                case 3:
                    $sales_return_datas = $sales_return_datas->leftJoin('main_events', 'main_events.id', 'account_main_account.sub_category')
                        ->leftJoin('main_rentels', 'main_rentels.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.description',
                            'customers.name',
                            'account_main_account.category',
                            'main_events.en_title as events',
                            'main_rentels.en_title as games'
                        );
                    break;

                    // Inventory
                case 6:
                    $sales_return_datas = $sales_return_datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.description',
                            'inventory_products.product_name'
                        );
                    break;

                default:
                    $sales_return_datas = $sales_return_datas->select(
                        'account_main_account.debit',
                        'account_main_account.date',
                        'account_main_account.description',
                        'customers.name'
                    );
                    break;
            }
            $sales_return_datas = $sales_return_datas->get();

            $purchases_datas = DB::table('account_main_account')
                ->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.table_id', 5) //purchase account
                ->orderBy('account_main_account.date', 'desc')
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->select(
                    'account_main_account.debit',
                    'account_main_account.date',
                    'account_main_account.description',
                    'inventory_products.product_name as detail'
                )
                ->get();

            $purchase_return_datas = DB::table('account_main_account')
                ->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.table_id', 6) //purchase return  account
                ->orderBy('account_main_account.date', 'desc')
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->select(
                    'account_main_account.credit',
                    'account_main_account.date',
                    'account_main_account.description',
                    'inventory_products.product_name as detail'
                )
                ->get();

            // expenses section start----------------------------------------------

            $service_types = DB::table('account_service_type')->select('id', 'name')->get();
            $ser_expenses = DB::table('account_main_account')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->where('account_main_account.table_id', 7)
                ->where('account_main_account.description', "Service Charges") //this condition for service expenses
                ->groupBy('account_main_account.sub_category')
                ->select(
                    'account_main_account.sub_category',
                    DB::raw('SUM(account_main_account.debit) as TOTAL_SERVICES')
                )
                ->get();

            foreach ($service_types as $row) {

                $check =  $ser_expenses->where('sub_category', $row->id)->first();

                if ($check) {
                    $row->SERVICES = $check->TOTAL_SERVICES;
                } else {
                    $row->SERVICES = 0.00;
                }
            }

            $other_expenses_types = DB::table('account__other_expenses_types')
                ->leftjoin('account_other_expense_categories', 'account_other_expense_categories.id', 'account__other_expenses_types.oth_exp_cat_id')
                ->select('account_other_expense_categories.category_name', 'account__other_expenses_types.id as sub_cat_id', 'account__other_expenses_types.name as sub_cat_name', 'account__other_expenses_types.oth_exp_cat_id')
                ->get();

            $oth_expenses = DB::table('account_main_account')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->where('account_main_account.table_id', 7)
                ->where('account_main_account.description', "Other Expenses") //this condition for other expenses
                ->groupBy('account_main_account.category', 'account_main_account.sub_category')
                ->select(
                    'account_main_account.category',
                    'account_main_account.sub_category',
                    DB::raw('SUM(account_main_account.debit) as TOTAL_SERVICES')
                )
                ->get();

            $exp_collection = collect();

            foreach ($other_expenses_types as $row) {

                $check_oth =  $oth_expenses->where('sub_category', $row->sub_cat_id)->where('category', $row->oth_exp_cat_id)->first();

                if ($check_oth) {
                    $row->SERVICES = $check_oth->TOTAL_SERVICES;
                } else {
                    $row->SERVICES = 0.00;
                }

                //group by expense category
                if ($other_expenses_types[0] == $row) {

                    $array1 = array();
                    array_push($array1, $row);

                    $obj1 =  new stdClass();
                    $obj1->cat_id = $row->oth_exp_cat_id;
                    $obj1->cat_name = $row->category_name;
                    $obj1->datas = $array1;
                    $exp_collection->push($obj1);
                } else {
                    $check_cat = $exp_collection->where('cat_id', $row->oth_exp_cat_id)->first();

                    if ($check_cat) {
                        array_push($check_cat->datas, $row);
                    } else {

                        $array2 = array();
                        array_push($array2, $row);

                        $obj2 =  new stdClass();
                        $obj2->cat_id = $row->oth_exp_cat_id;
                        $obj2->cat_name = $row->category_name;
                        $obj2->datas = $array2;
                        $exp_collection->push($obj2);
                    }
                }
            }
            // expenses section end----------------------------------------------

            return view('Accounts.profitOrLoss', compact('service_types', 'exp_collection', 'sale', 'sales_return', 'gross_profit', 'net_profit', 'dept', 'id', 'sales_datas', 'sales_return_datas', 'purchase', 'from', 'to', 'basic_stock', 'final_stock', 'purchase_return', 'purchase_in', 'cost_of_sales', 'purchases_datas', 'purchase_return_datas', 'id', 'service_expense', 'total_expenses', 'non_current_deprection'));
        } else {

            $cash_datas = DB::table('account_main_account')
                ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.table_id', 1) //cash account
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->orderBy('account_main_account.date', 'desc');

            switch ($id) {

                    //foodcity
                case 1:
                    $cash_datas = $cash_datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'customers.name',
                            'inventory_products.product_name as detail'
                        );
                    break;

                    // front office
                case 3:
                    $cash_datas = $cash_datas->leftJoin('main_events', 'main_events.id', 'account_main_account.sub_category')
                        ->leftJoin('main_rentels', 'main_rentels.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'customers.name',
                            'main_events.en_title as events',
                            'main_rentels.en_title as games'
                        );
                    break;
                    // Inventory
                case 6:
                    $cash_datas = $cash_datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'customers.name',
                            'inventory_products.product_name',
                        );
                    break;

                    // Restuarant
                case 8:
                    $cash_datas = $cash_datas->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                        ->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.sub_category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'customers.name',
                            'employees.f_name as emp_name',
                            'inventory_products.product_name as detail'
                        );
                    break;

                    // Pizza
                case 11:
                    $cash_datas = $cash_datas->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                        ->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.sub_category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'customers.name',
                            'employees.f_name as emp_name',
                            'inventory_products.product_name as detail'
                        );
                    break;

                default:
                    $cash_datas = $cash_datas->select(
                        'account_main_account.credit',
                        'account_main_account.debit',
                        'account_main_account.date',
                        'account_main_account.cash',
                        'account_main_account.card',
                        'account_main_account.description',
                        'account_main_account.cheque',
                        'account_main_account.credit_amount',
                        'customers.name',
                    );
                    break;
            }
            $cash_datas = $cash_datas->get();

            $debtor_datas = DB::table('account_main_account')
                ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.table_id', 4) //credit account
                ->orderBy('account_main_account.date', 'desc')
                ->groupBy('account_main_account.customer_id')
                ->select(
                    'customers.name',
                    DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                )
                ->get();

            if ($id == 8) {
                $debtor_datas = [];

                $debtor_Customer_datas = DB::table('account_main_account')
                    ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                    ->where('account_main_account.dept_id', $id)
                    ->where('account_main_account.table_id', 4) //credit account
                    ->where('account_main_account.category', 3) //normal customer
                    ->orderBy('account_main_account.date', 'desc')
                    ->groupBy('account_main_account.customer_id')
                    ->select(
                        'customers.name',
                        'account_main_account.category',
                        DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                    )
                    ->get();

                $debtor_Employee_datas = DB::table('account_main_account')
                    ->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                    ->where('account_main_account.dept_id', $id)
                    ->where('account_main_account.table_id', 4) //credit account
                    ->where('account_main_account.category', 1) //employee
                    ->orderBy('account_main_account.date', 'desc')
                    ->groupBy('account_main_account.customer_id')
                    ->select(
                        'employees.f_name',
                        'account_main_account.category',
                        DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                    )
                    ->get();

                $debtor_hotel_datas = DB::table('account_main_account')
                    ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                    ->where('account_main_account.dept_id', $id)
                    ->where('account_main_account.table_id', 4) //credit account
                    ->where('account_main_account.category', 2) //hotel
                    ->orderBy('account_main_account.date', 'desc')
                    ->groupBy('account_main_account.customer_id')
                    ->select(
                        'customers.name',
                        'account_main_account.category',
                        'account_main_account.sub_category',
                        DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                    )
                    ->get();

                foreach ($debtor_Customer_datas as $item) {
                    array_push($debtor_datas, $item);
                }
                foreach ($debtor_Employee_datas as $item) {
                    array_push($debtor_datas, $item);
                }
                foreach ($debtor_hotel_datas as $item) {
                    array_push($debtor_datas, $item);
                }
            }

            // calculate accured service charges
            $accured_service_types = DB::table('account_service_type')->select('id', 'name')->get();
            $accured_ser_charges = DB::table('account_main_account')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->where('account_main_account.table_id', 8)
                ->where('account_main_account.description', "Service Charges") //this condition for service expenses
                ->groupBy('account_main_account.sub_category')
                ->select(
                    'account_main_account.sub_category',
                    DB::raw('SUM(account_main_account.credit) as TOTAL_ACCURED_CHARGES_credit'),
                    DB::raw('SUM(account_main_account.debit) as TOTAL_ACCURED_CHARGES_debit')
                )
                ->get();

            foreach ($accured_service_types as $row) {

                $check =  $accured_ser_charges->where('sub_category', $row->id)->first();

                if ($check) {
                    $row->ACCURED_SERVICES = $check->TOTAL_ACCURED_CHARGES_credit - $check->TOTAL_ACCURED_CHARGES_debit;
                } else {
                    $row->ACCURED_SERVICES = 0.00;
                }
            }

            // calculate accured other expense charges
            $accured_oth_exp_types = DB::table('account__other_expenses_types')
                ->leftjoin('account_other_expense_categories', 'account_other_expense_categories.id', 'account__other_expenses_types.oth_exp_cat_id')
                ->select('account_other_expense_categories.category_name', 'account__other_expenses_types.id as sub_cat_id', 'account__other_expenses_types.name as sub_cat_name', 'account__other_expenses_types.oth_exp_cat_id')
                ->get();


            $accured_oth_exp_amount = DB::table('account_main_account')
                ->where('account_main_account.dept_id', $id)
                ->where('account_main_account.date', '>=', $from)
                ->where('account_main_account.date', '<=', $to)
                ->where('account_main_account.table_id', 8)
                ->where('account_main_account.description', "Other Expenses") //this condition for other expenses
                ->groupBy('account_main_account.sub_category', 'account_main_account.category')
                ->select(
                    'account_main_account.sub_category',
                    'account_main_account.category',
                    DB::raw('SUM(account_main_account.credit) as TOTAL_ACCURED_CHARGES_credit'),
                    DB::raw('SUM(account_main_account.debit) as TOTAL_ACCURED_CHARGES_debit')
                )
                ->get();

            $accured_exp_collection = collect();

            foreach ($accured_oth_exp_types as $row) {

                if ($accured_oth_exp_amount) {
                    $check_oth_accured =  $accured_oth_exp_amount->where('sub_category', $row->sub_cat_id)
                        ->where('category', $row->oth_exp_cat_id)
                        ->first();

                    if ($check_oth_accured) {
                        $row->ACCURED_SERVICES = $check_oth_accured->TOTAL_ACCURED_CHARGES_credit - $check_oth_accured->TOTAL_ACCURED_CHARGES_debit;
                    } else {
                        $row->ACCURED_SERVICES = 0.00;
                    }
                } else {
                    $row->ACCURED_SERVICES = 0.00;
                }

                if ($accured_oth_exp_types[0] == $row) {
                    $array3 = array();
                    array_push($array3, $row);

                    $obj3 =  new stdClass();
                    $obj3->cat_id = $row->oth_exp_cat_id;
                    $obj3->cat_name = $row->category_name;
                    $obj3->datas = $array3;
                    $accured_exp_collection->push($obj3);
                } else {
                    $check_acc_cat = $accured_exp_collection->where('cat_id', $row->oth_exp_cat_id)->first();

                    if ($check_acc_cat) {
                        array_push($check_acc_cat->datas, $row);
                    } else {

                        $array4 = array();
                        array_push($array4, $row);

                        $obj4 =  new stdClass();
                        $obj4->cat_id = $row->oth_exp_cat_id;
                        $obj4->cat_name = $row->category_name;
                        $obj4->datas = $array4;
                        $accured_exp_collection->push($obj4);
                    }
                }
            }

            return view('Accounts.balance_sheet', compact('accured_exp_collection', 'cash', 'current_assets', 'net_profit', 'total_assets', 'equity_and_liabilities', 'total_equity_and_liabilities', 'cash_datas', 'dept', 'debtor', 'debtor_datas', 'to', 'from', 'final_stock', 'id', 'accured_service_types', 'accur_oth_expen', 'accur_oth_expen', 'non_current_collection'));
        }
    }


    public function AccountLedger(Request $request, $department, $table)
    {

        //credit account
        // this if use for debors groupby customer id-------------------------------------------
        if ($table == 4) {

            $datas = DB::table('account_main_account')
                ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                ->leftJoin('account_category', 'account_category.id', 'account_main_account.category')
                ->where('account_main_account.dept_id', $department)
                ->where('account_main_account.table_id', 4) //credit account
                ->orderBy('account_main_account.date', 'desc')
                ->groupBy('account_main_account.customer_id')
                ->select(
                    'customers.name',
                    DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                )
                ->get();

            // Restaurant
            //this if use for group by employess ,customer, hotel customer separately
            if ($department == 8) {
                $datas = [];

                $debtor_Customer_datas = DB::table('account_main_account')
                    ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                    ->where('account_main_account.dept_id', $department)
                    ->where('account_main_account.table_id', 4) //credit account
                    ->where('account_main_account.category', 3) //normal customer
                    ->orderBy('account_main_account.date', 'desc')
                    ->groupBy('account_main_account.customer_id')
                    ->select(
                        'customers.name',
                        'account_main_account.category',
                        DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                    )
                    ->get();


                $debtor_Employee_datas = DB::table('account_main_account')
                    ->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                    ->where('account_main_account.dept_id', $department)
                    ->where('account_main_account.table_id', 4) //credit account
                    ->where('account_main_account.category', 1) //employee
                    ->orderBy('account_main_account.date', 'desc')
                    ->groupBy('account_main_account.customer_id')
                    ->select(
                        'employees.f_name as name',
                        'account_main_account.category',
                        DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                    )
                    ->get();


                $debtor_hotel_datas = DB::table('account_main_account')
                    ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                    ->where('account_main_account.dept_id', $department)
                    ->where('account_main_account.table_id', 4) //credit account
                    ->where('account_main_account.category', 2) //hotel
                    ->orderBy('account_main_account.date', 'desc')
                    ->groupBy('account_main_account.customer_id')
                    ->select(
                        'customers.name',
                        'account_main_account.category',
                        'account_main_account.sub_category',
                        DB::raw('SUM(account_main_account.credit) as TOTAL_CREDIT,SUM(account_main_account.debit) as TOTAL_DEBIT')
                    )
                    ->get();

                foreach ($debtor_Customer_datas as $item) {
                    array_push($datas, $item);
                }
                foreach ($debtor_Employee_datas as $item) {
                    array_push($datas, $item);
                }
                foreach ($debtor_hotel_datas as $item) {
                    array_push($datas, $item);
                }
            }

            $total_credit = 0.00;
            $total_debit = 0.00;

            foreach ($datas as $item) {
                $total_credit += $item->TOTAL_CREDIT;
                $total_debit += $item->TOTAL_DEBIT;
            }
            // this if use for debors groupby customer id-------------------------------------------

        } else {

            $datas = DB::table('account_main_account')
                ->leftJoin('customers', 'customers.id', 'account_main_account.customer_id')
                ->leftJoin('account_category', 'account_category.id', 'account_main_account.category')
                ->where('account_main_account.dept_id', $department)
                ->where('account_main_account.table_id', $table)
                ->orderBy('account_main_account.date', 'desc');

            switch ($department) {
                    // Ankadi
                case 1:
                    $datas = $datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'inventory_products.product_name'
                        );
                    break;
                    // Ticket Booking
                case 3:
                    $datas = $datas->leftJoin('main_events', 'main_events.id', 'account_main_account.sub_category')
                        ->leftJoin('main_rentels', 'main_rentels.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'main_events.en_title as events',
                            'main_rentels.en_title as games'
                        );
                    break;

                    // Restaurant
                case 8:
                    $datas = $datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'account_main_account.sub_category',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'inventory_products.product_name',
                            'employees.f_name'
                        );
                    break;

                    // Pizza
                case 11:
                    $datas = $datas->leftJoin('inventory_products', 'inventory_products.id', 'account_main_account.sub_category')
                        ->leftJoin('employees', 'employees.id', 'account_main_account.sub_category')
                        ->select(
                            'account_main_account.category',
                            'account_main_account.credit',
                            'account_main_account.debit',
                            'account_main_account.date',
                            'account_main_account.cash',
                            'account_main_account.card',
                            'account_main_account.description',
                            'account_main_account.cheque',
                            'account_main_account.sub_category',
                            'account_main_account.credit_amount',
                            'customers.name',
                            'inventory_products.product_name',
                            'employees.f_name'
                        );
                    break;

                default:
                    $datas = $datas->select(
                        'account_main_account.credit',
                        'account_main_account.description',
                        'account_main_account.debit',
                        'account_main_account.date',
                        'account_main_account.cash',
                        'account_main_account.card',
                        'account_main_account.cheque',
                        'account_main_account.credit_amount',
                        'account_category.category_name',
                        'customers.name'
                    );
                    break;
            }


            $datas = $datas->get();

            $total_credit = 0.00;
            $total_debit = 0.00;
            foreach ($datas as $item) {
                $total_credit += $item->credit;
                $total_debit += $item->debit;
            }
        }

        //set heading name

        // if ($department == 1) {
        //     $dept_name = "Ankadi";
        // } elseif ($department == 3) {
        //     $dept_name = "Front Office";
        // } elseif ($department == 8) {
        //     $dept_name = "Marutham Restaurant";
        // }

        switch ($department) {

            case 1:
                $dept_name = "Ankadi";
                break;
            case 3:
                $dept_name = "Front Office";
                break;
            case 8:
                $dept_name = "Marutham Restaurant";
                break;
            case 9:
                $dept_name = "Kurinchi One Restaurant";
                break;
            case 10:
                $dept_name = "Kurinchi Restaurant";
                break;
            case 11:
                $dept_name = "Pizza Restaurant";
                break;
            default:
                break;
        }

        switch ($table) {
            case 1:
                $table_name = "Cash";
                break;
            case 2:
                $table_name = "Sales";
                break;
            case 3:
                $table_name = "Sales Return";
                break;
            case 4:
                $table_name = "Debtors";
                break;
            case 5:
                $table_name = "Purchase";
                break;
            case 6:
                $table_name = "Purchase Return";
                break;

            default:
                break;
        }

        $heading = $dept_name . ' - ' . $table_name;
        return view('Accounts.ledger', compact('datas', 'heading', 'table', 'total_credit', 'total_debit', 'department'));
    }

    public function accountDatabase()
    {
        $accountData = DB::table('account_main_account')->get();
        return view('accountspages.accountDatabase', compact('accountData'));
    }

    public function AccountOtherExp(Request $request)
    {
        $datas = DB::table('accounts_other_expenses')
            ->where('date', '<=', $request->to)
            ->where('date', '>=', $request->from)
            ->where('oth_exp_type_id', $request->exp_id)
            ->get();

        return $datas;
    }

    public function AccountServiceCharges(Request $request)
    {
        $datas = DB::table('account_dept_service_charge')
            ->where('month', '<=', $request->to)
            ->where('month', '>=', $request->from)
            ->where('service_type_id', $request->exp_id)
            ->get();
        return $datas;
    }

    public function AccountMain(Request $request)
    {
        return view('Accounts.reecha_main_profitloss');
    }
}
