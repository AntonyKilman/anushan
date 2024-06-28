<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\AccessModelController;
use App\Http\Controllers\AccessPointController;
use App\Http\Controllers\PermisionController;
use App\Http\Controllers\LoginAccessController;

// footcity controllers
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CommisionCustomerController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\stockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\SalesStockAdjustmentController;
use App\Http\Controllers\SalesReturnDetailController;
use App\Http\Controllers\Advance_Payment_Controller;

//inventory controllers
use App\Http\Controllers\InventoryProductCategoryController;
use App\Http\Controllers\InventoryProductSubCategoryController;
use App\Http\Controllers\InventoryProductTypeController;
use App\Http\Controllers\InventoryBrandController;
use App\Http\Controllers\InventoryProductController;
use App\Http\Controllers\InventoryPurchaseOrderRequestController;
use App\Http\Controllers\InventorySellerTypeController;
use App\Http\Controllers\InventorySellerController;
use App\Http\Controllers\InventoryPurchaseOrderController;
use App\Http\Controllers\InventoryPurchaseItemController;
use App\Http\Controllers\InventoryDepartmentController;
use App\Http\Controllers\InventoryIndoorTransferController;
use App\Http\Controllers\InventoryReturnReasonController;
use App\Http\Controllers\InventoryOutdoorReturnController;
use App\Http\Controllers\InventoryEquipmentController;
use App\Http\Controllers\InventoryPermanentAssetTransferController;
use App\Http\Controllers\InventoryIndoorReturnController;
use App\Http\Controllers\InventoryAssetStatusTypeController;
use App\Http\Controllers\InventoryPermanentAssetsController;
use App\Http\Controllers\InventoryPurOrdReqRepController;
use App\Http\Controllers\InventoryPerAsseRepController;
use App\Http\Controllers\inventoryDashController;
use App\Http\Controllers\InventoryPurchaseRequestController;
use App\Http\Controllers\newInventoryPurchaseOrder;
use App\Http\Controllers\InventoryElectricUsedController;
use App\Http\Controllers\inventoryStockController;
use App\Http\Controllers\inventoryNewStockController;
use App\Http\Controllers\SalesStockTransferController;
use App\Http\Controllers\company_return_controller;


//HR controllers
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AllowanceTypeController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentTransferController;
use App\Http\Controllers\EmployeeBasicSalaryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocController;
use App\Http\Controllers\EmployeeSalaryPartController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\HRReportController;
use App\Http\Controllers\SalaryPayableController;
use App\Http\Controllers\SalaryPayPrintController;
use App\Http\Controllers\WorkTypeController;
use App\Http\Controllers\AdvancePaymentController;


// Accounnt controllers
use App\Http\Controllers\accountBankController;
use App\Http\Controllers\AccountsOtherExpenseController;
use App\Http\Controllers\accountBankChargesTypeController;
use App\Http\Controllers\accountBankIntrestController;
use App\Http\Controllers\AccountsBankChargesController;
use App\Http\Controllers\accountBankTransectionController;
use App\Http\Controllers\AccountsOtherIncomesController;
use App\Http\Controllers\accountServiceProviderController;
use App\Http\Controllers\accountServiceChargeController;
use App\Http\Controllers\accountChequePaymentController;
use App\Http\Controllers\AccountsLedgerController;
use App\Http\Controllers\AccountsServiceTypeController;
use App\Http\Controllers\AccountsServiceExpenseController;
use App\Http\Controllers\accountReportController;
use App\Http\Controllers\account_department_controller;
use App\Http\Controllers\AccountOtherExpensesTypeController;
use App\Http\Controllers\ProfitLoss\FoodcityProfitlossController;
use App\Http\Controllers\ProfitLoss\InventortProfitlossController;
use App\Http\Controllers\ProfitLoss\HrProfitlossController;
use App\Http\Controllers\ProfitLoss\foodcitAccountController;
use App\Http\Controllers\newServiceExpenseController;
use App\Http\Controllers\AccountsLedger;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Models\account_OtherExpensesType;
use App\Models\accountChequePayment;
use App\Models\accountServiceCharge;
use App\Models\AccountsServiceExpense;
use Facade\FlareClient\Report;
use App\Http\Controllers\AccountController;
use App\Models\account_department;
use App\Http\Controllers\AddCategeoryController;
use App\Http\Controllers\Accounts_DetailsController;
use App\Http\Controllers\AccountsDeatailReportController;
use App\Http\Controllers\Closed_Balance;
use App\Http\Controllers\DailyCashBook_Controller;
use App\Http\Controllers\DailyAdjustmentReportController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\MukunthanController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AccountsOtherIncomesCategeoryController;


Route::get('/', [UserController::class, 'token'])->name('user.token');
Route::get('/admin', [UserController::class, 'token'])->name('user.tokenx');
Route::get('/admin/login', [UserController::class, 'token']);

Route::post('/admin/login', [UserController::class, 'login'])->name('login');
Route::get('/admin/logout', [UserController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () { //'checkAccess'
    // home access

    Route::get('/admin/home', [inventoryDashController::class, 'getDashDatas'])->name('user.home');

    //Create user
    Route::get('/admin/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/admin/user/destroy', [UserController::class, 'destroy'])->name('user.destroy');

    //reset Password
    Route::get('/admin/main_reset', [UserController::class, 'resetIndex'])->name('main_reset.index');
    Route::post('/admin/main_reset/store', [UserController::class, 'resetStore'])->name('main_reset.store');

    //Create user role
    Route::get('/admin/role', [UserRoleController::class, 'index'])->name('role.index');
    Route::post('/admin/role/store', [UserRoleController::class, 'store'])->name('role.store');
    Route::post('/admin/role/update', [UserRoleController::class, 'update'])->name('role.update');
    Route::post('/admin/role/destroy', [UserRoleController::class, 'destroy'])->name('role.destroy');

    //Create Access Point
    Route::get('/admin/accessPoint/{id}', [AccessPointController::class, 'index'])->name('access_point.index');
    Route::post('/admin/accessPoint/store', [AccessPointController::class, 'store'])->name('access_point.store');
    Route::post('/admin/accessPoint/destroy', [AccessPointController::class, 'destroy'])->name('access_point.destroy');
    Route::POST('/admin/accessPoint/update', [AccessPointController::class, 'update'])->name('access_point.update');

    //Create Access Model
    Route::get('/admin/accessModel', [AccessModelController::class, 'index'])->name('access_model.index');
    Route::post('/admin/accessModel/store', [AccessModelController::class, 'store'])->name('access_model.store');
    Route::post('/admin/accessModel/destroy', [AccessModelController::class, 'destroy'])->name('access_model.destroy');
    Route::POST('/admin/accessModel/update', [AccessModelController::class, 'update'])->name('access_model.update');

    //Create PermisionController
    Route::get('/admin/permission/{id}', [PermisionController::class, 'index'])->name('permission.index');
    Route::post('/admin/permission/store', [PermisionController::class, 'store'])->name('permission.store');

    //login access
    Route::post('/admin/loginAccess/store', [LoginAccessController::class, 'store'])->name('login_access.store');

    // start Foodcity //////////////////////////////////////////////////////////////////////////////////////////

    // Product
    Route::get('/sales/product', [ProductController::class, 'index'])->name('product.index');
    Route::post('/sales/product/update', [ProductController::class, 'update'])->name('product.update');
    //Product in transit
    Route::get('/sales/product-in', [ProductController::class, 'indexProductIn'])->name('product.indexProductIn');
    Route::post('/sales/product-approve', [ProductController::class, 'approveProductIn'])->name('product.approveProductIn');
    Route::post('/sales/product-reject', [ProductController::class, 'rejectProductIn'])->name('product.rejectProductIn');
    //product return
    Route::get('/sales/product-return', [ProductController::class, 'indexProductReturn'])->name('product.indexProductReturn');
    Route::post('/sales/product-item-return', [ProductController::class, 'indexProductItemReturn'])->name('product.indexProductItemReturn');

    //foodcity sales return details
    Route::get('/sales-return-details', [SalesReturnDetailController::class, 'showFoodcitySalesReturnDetails'])->name('sales-return-details.index');
    Route::put('/sales-return-details-update', [SalesReturnDetailController::class, 'updatePaymentStatus'])->name('sales-return-details.update');

    //reprint barcode
    Route::get('sales/reprint-barcode/{id}', [ProductController::class, 'reprintBarcode'])->name('product.reprintBarcode');

    //sales
    Route::get('/sales/product-sale', [SalesController::class, 'index'])->name('salesProduct.index');
    Route::get('/sales/search-product', [SalesController::class, 'search'])->name('salesProduct.search');
    Route::post('/sales/store-product', [SalesController::class, 'store'])->name('salesProduct.store');

    //credit sales
    Route::get('/credit/sales/product-sale', [SalesController::class, 'creditindex'])->name('salesProduct.creditindex');

    //sales return
    Route::get('/sales/sale-return', [SalesReturnController::class, 'index'])->name('salesReturn.index');
    Route::get('/sales/sale-return/{id}', [SalesReturnController::class, 'salesReturn'])->name('salesReturn.view');
    Route::post('/sales/sale-cancel', [SalesReturnController::class, 'store'])->name('salesReturn.store');
    // credit
    Route::get('/sales/credit-payment', [CreditController::class, 'index'])->name('credit_payment.index');
    Route::post('/sales/credit-payment-store', [CreditController::class, 'save_credit_payment_details'])->name('credit_payment.save_credit_payment_details');

    Route::get('/sales/credit-list', [CreditController::class, 'credit_list_view'])->name('credit_list.credit_list_view');
    Route::post('/sales/credit-list-store', [CreditController::class, 'savecreditdetails'])->name('credit_list.savecreditdetails');

    //customers
    Route::get('/sales/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/sales/customer', [CustomerController::class, 'customer_create'])->name('customer.customer_create');

    Route::get('/customer-tale-report/{id}', [CustomerController::class, 'customerTaleReport'])->name('customer.customerTaleReport');

    // commision customer
    Route::get('/sales/commisionCustomer', [CommisionCustomerController::class, 'index'])->name('commisioncustomer.index');
    Route::post('/sales/commisionCustomer', [CommisionCustomerController::class, 'commision_customer_create'])->name('commisioncustomer.commision_customer_create');

    Route::get('/sales/add-commisions', [CommisionCustomerController::class, 'addCommision'])->name('addcommision.addCommision');
    Route::post('/sales/commision-details-add', [CommisionCustomerController::class, 'add_commision_details'])->name('commisioncustomer.add_commision_details');
    Route::get('/sales/search-sales', [CommisionCustomerController::class, 'searchSales'])->name('addcommision.search-sales');
    Route::get('/sales/search-commision-Customer', [CommisionCustomerController::class, 'searchCommisionCustomers'])->name('addcommision.searchCommisionCustomers');

    //sales report
    Route::get('/sales/sales-report', [ReportController::class, 'sales'])->name('sales.report');
    Route::get('/sales/temp-sales-report', [ReportController::class, 'temp_sales'])->name('sales.temp-report');
    Route::get('/sales/temp-sales-delete/{id}', [ReportController::class, 'temp_sales_delete'])->name('sales.temp_sales_delete');
    Route::get('/sales/sales-report/{id}', [ReportController::class, 'salesView'])->name('salesView.report');
    // credit report
    Route::get('/sales/credit-report', [ReportController::class, 'creditReport'])->name('creditReport');

    //sales product report
    Route::get('/sales-product-reports', [ReportController::class, 'salesProduct'])->name('salesProduct.report');

    //sales cancel report
    Route::get('/sales/sales-cancel-report', [ReportController::class, 'salesCancel'])->name('salesCancel.report');
    Route::get('/sales/sales-cancel-report/{id}', [ReportController::class, 'salesCancelView'])->name('salesCancelView.report');

    //return report
    Route::get('/sales/return-report', [ReportController::class, 'return'])->name('return.report');
    Route::post('/sales/return-report', [ReportController::class, 'returnFilter'])->name('returnFilter.report');

    //availalbe product report
    Route::get('/sales/stock-report', [ReportController::class, 'stock'])->name('stock.report');

    // for accounts
    Route::get("/stock", [stockController::class, 'stock'])->name('account.stockShow');
    Route::post("/stock-Store", [stockController::class, 'stockStore'])->name('account.stockStore');

    // Sales Stock teansfer
    Route::get('/sales-stock-transfer-view', [SalesStockTransferController::class, 'stocktransferview'])->name('sales.stocktransferview');
    Route::post('/sales-stock-transfer-add', [SalesStockTransferController::class, 'stocktransferAdd'])->name("sales.stocktransferAdd");

    Route::get('/sales/search-products', [SalesStockTransferController::class, 'searchProducts'])->name('searchProducts');
    Route::get('/sales/search-foodcity-products', [SalesStockTransferController::class, 'searhfoodcityProducts'])->name('searhfoodcityProducts');

    //Advance payments
    Route::get('/sales/advance-payment', [Advance_Payment_Controller::class, 'index'])->name('advance_payment.index');
    Route::get('/sales/add-advance-payment', [Advance_Payment_Controller::class, 'addindex'])->name('advance_payment.addindex');
    Route::post('/sales/advance_payment-create', [Advance_Payment_Controller::class, 'add_advance_payment_create'])->name('advance_payment.add_advance_payment_create');
    Route::get('/sales/view-advance_payment-order-product/{id}', [Advance_Payment_Controller::class, 'viewOrderProduct'])->name('advance_payment.viewOrderProduct');
    Route::get('/sales/advance_payment-order-delete/{id}', [Advance_Payment_Controller::class, 'delete'])->name('advance_payment.delete');

    // end foodcity ////////////////////////////////////////////////////////////////////////////////////////////////////

    // **************START INVENTRY*************

    // product type
    Route::post('/product-type-add-process', [InventoryProductTypeController::class, 'productTypeAddProcess'])->name("inventory.productTypeAddProcess");
    Route::get('/product-type-show-all', [InventoryProductTypeController::class, 'productTypeShowAll'])->name("inventory.productTypeShowAll");
    Route::post('/product-type-update-process', [InventoryProductTypeController::class, 'productTypeUpdateProcess'])->name("inventory.productTypeUpdateProcess");

    // product category    Inventory-Product Category-access model
    Route::post('/productCatAdd', [InventoryProductCategoryController::class, 'productCatAdd'])->name("inventory.productCatAdd");
    Route::get('/productCatShow', [InventoryProductCategoryController::class, 'productCatShow'])->name("inventory.productCatShow");
    Route::post('/productCatUpdate', [InventoryProductCategoryController::class, 'productCatUpdate'])->name("inventory.productCatUpdate");

    // product subcategory     Inventory-Product Sub Category-access model
    Route::get('/productSubCatGet', [InventoryProductSubCategoryController::class, 'productSubCatGet'])->name("inventory.productSubCatGet"); //no need
    Route::post('/productSubCatAdd', [InventoryProductSubCategoryController::class, 'productSubCatAdd'])->name("inventory.productSubCatAdd");
    Route::get('/productSubCatShow', [InventoryProductSubCategoryController::class, 'productSubCatShow'])->name("inventory.productSubCatShow");
    Route::post('/productSubCatUpdate', [InventoryProductSubCategoryController::class, 'productSubCatUpdate'])->name("inventory.productSubCatUpdate");

    // Brand       Inventory-Product Brand-access modelelectric-use-edit
    Route::post('/brandAdd', [InventoryBrandController::class, 'brandAdd'])->name("inventory.brandAdd");
    Route::get('/brandShow', [InventoryBrandController::class, 'brandShow'])->name("inventory.brandShow");
    Route::post('/brandUpdate', [InventoryBrandController::class, 'brandUpdate'])->name("inventory.brandUpdate");
    Route::get('/brands/{id}', [InventoryBrandController::class, 'brandsDetails'])->name("inventory.brandsDetails"); // no need

    // Product    Inventory-Product-access model
    Route::get('/productGet', [InventoryProductController::class, 'productGet'])->name("inventory.productGet"); // no need
    Route::post('/productAdd', [InventoryProductController::class, 'productAdd'])->name("inventory.productAdd");
    Route::get('/productShow', [InventoryProductController::class, 'productShow'])->name("inventory.productShow");
    Route::post('/productUpdate', [InventoryProductController::class, 'productUpdate'])->name("inventory.productUpdate");
    Route::get('/product-search', [InventoryProductController::class, 'productSearch'])->name("inventory.productSearch");
    Route::get('/product-purchase/{product}', [InventoryProductController::class, 'productPurchase'])->name("inventory.productPurchase");
    Route::get('/product-qty-alert-showall', [InventoryProductController::class, 'productQtyAlertShowall'])->name("inventory.productQtyAlertShowall");

    // seller type                Inventory-Seller Type-access model
    Route::get('/seller-type-add', [InventorySellerTypeController::class, 'sellerTypeAdd'])->name("inventory.sellerTypeAdd"); //no need
    Route::post('/seller-type-add-process', [InventorySellerTypeController::class, 'sellerTypeAddProcess'])->name("inventory.sellerTypeAddProcess");
    Route::get('/seller-type-show-all', [InventorySellerTypeController::class, 'sellerTypeShowAll'])->name("inventory.sellerTypeShowAll");
    Route::get('/seller-type-edit/{id}', [InventorySellerTypeController::class, 'sellerTypeEdit'])->name("inventory.sellerTypeEdit"); //no need
    Route::post('/seller-type-update-process', [InventorySellerTypeController::class, 'sellerTypeUpdateProcess'])->name("inventory.sellerTypeUpdateProcess");

    // seller       Inventory-Seller-access model
    Route::get('/seller-add', [InventorySellerController::class, 'sellerAdd'])->name("inventory.sellerAdd");
    Route::post('/seller-add-process', [InventorySellerController::class, 'sellerAddProcess'])->name("inventory.sellerAddProcess"); //no need
    Route::get('/seller-show-all', [InventorySellerController::class, 'sellerShowAll'])->name("inventory.sellerShowAll");
    Route::get('/seller-view/{id}', [InventorySellerController::class, 'sellerView'])->name("inventory.sellerView");
    Route::get('/seller-edit/{id}', [InventorySellerController::class, 'sellerEdit'])->name("inventory.sellerEdit");
    Route::post('/seller-update-process', [InventorySellerController::class, 'sellerUpdateProcess'])->name("inventory.sellerUpdateProcess"); //no need
    Route::get('/seller-histroy/{sellere}', [InventorySellerController::class, 'sellerHistroy'])->name("inventory.sellerHistroy");

    // Purchase order request               Inventory-Purchase Order Request-access model
    Route::post('/PurchaseOrderRequestAdd', [InventoryPurchaseOrderRequestController::class, 'PurchaseOrderRequestAdd'])->name("inventory.purchaseOrderRequestAdd");
    Route::get('/purchaseOrderRequestShow', [InventoryPurchaseOrderRequestController::class, 'purchaseOrderRequestShow'])->name("inventory.purchaseOrderRequestShow");
    Route::post('/purchaseorderRequestUpdate', [InventoryPurchaseOrderRequestController::class, 'purchaseorderRequestUpdate'])->name("inventory.purchaseorderRequestUpdate");

    // purchase order           Inventory-Purchase Order-access model
    Route::get('/purchase-order-add', [InventoryPurchaseOrderController::class, 'purchaseOrderAdd'])->name("inventory.purchaseOrderAdd");
    Route::post('/purchase-boucher-add-process', [InventoryPurchaseOrderController::class, 'purchaseBoucherAddProcess'])->name("inventory.purchaseBoucherAddProcess");
    Route::post('/purchase-order-add-process', [InventoryPurchaseOrderController::class, 'purchaseOrderAddProcess'])->name("inventory.purchaseOrderAddProcess");
    Route::get('/permanent-assets-add', [InventoryPurchaseOrderController::class, 'permanentAssetsAdd'])->name("inventory.permanentAssetsAdd");
    Route::post('/permanent-assets-add-process', [InventoryPurchaseOrderController::class, 'permanentAssetsAddProcess'])->name("inventory.permanentAssetsAddProcess");
    Route::get('/purchase-order-show-all', [InventoryPurchaseOrderController::class, 'purchaseOrderShowAll'])->name("inventory.purchaseOrderShowAll");
    Route::get('/purchase-order-edit/{id}', [InventoryPurchaseOrderController::class, 'purchaseOrderEdit'])->name("inventory.purchaseOrderEdit");
    Route::get('/purchase-order-view/{id}/{product_id}', [InventoryPurchaseOrderController::class, 'purchaseOrderView'])->name("inventory.purchaseOrderView");
    Route::post('/purchase-order-view', [InventoryPurchaseOrderController::class, 'purchaseOrderViewPost'])->name("inventory.purchaseOrderViewPost");
    Route::post('/purchase-order-update-process', [InventoryPurchaseOrderController::class, 'purchaseOrderUpdateProcess'])->name("inventory.purchaseOrderUpdateProcess");

    // purchased items              Inventory-Purchase Item-access model
    Route::get('/purchased-item-show-all', [InventoryPurchaseItemController::class, 'purchasedItemShowAll'])->name("inventory.purchasedItemShowAll");
    Route::get('/expery-products-show', [InventoryPurchaseItemController::class, 'experyDateItemShow'])->name("inventory.experyDateItemShow");
    Route::get('/expery-product-return/{id}', [InventoryPurchaseItemController::class, 'experyProductReturn'])->name("inventory.experyProductReturn"); // no need



    // Department
    Route::get('/departmentShow', [InventoryDepartmentController::class, 'departmentShow'])->name("inventory.departmentShow");
    Route::post('/departmentAdd', [InventoryDepartmentController::class, 'departmentAdd'])->name("inventory.departmentAdd");
    Route::post('/departmentUpdate', [InventoryDepartmentController::class, 'departmentUpdate'])->name("inventory.departmentUpdate");


    // Indoor Transfer             Inventory-Indoor Transfer-access model
    Route::get("GetProDept", [InventoryIndoorTransferController::class, 'GetProDept'])->name("inventory.GetProDept");
    Route::post("/indoorTransferAdd", [InventoryIndoorTransferController::class, 'indoorTransferAdd'])->name("inventory.indoorTransferAdd");
    Route::get("IndoorTransferShow", [InventoryIndoorTransferController::class, 'IndoorTransferShow'])->name("inventory.IndoorTransferShow");
    Route::get("IndoorTransferEdit/{id}", [InventoryIndoorTransferController::class, 'IndoorTransferEdit'])->name("inventory.IndoorTransferEdit");
    Route::post("IndoorTransferUpdate", [InventoryIndoorTransferController::class, 'IndoorTransferUpdate'])->name("inventory.IndoorTransferUpdate");
    Route::get("IndoorTransferDelete/{id}", [InventoryIndoorTransferController::class, 'IndoorTransferDelete'])->name("inventory.IndoorTransferDelete");

    // Equipment Transfer               Inventory-Equpment Transfer-access model
    Route::get("EquProduct", [InventoryEquipmentController::class, 'EquProduct'])->name("inventory.EquProduct");
    Route::post("equipmentTransferAdd", [InventoryEquipmentController::class, 'equipmentTransferAdd'])->name("inventory.equipmentTransferAdd");
    Route::get("equipmentTransferShow", [InventoryEquipmentController::class, 'equipmentTransferShow'])->name("inventory.equipmentTransferShow");
    Route::get("/equipmentTransferEdit/{id}", [InventoryEquipmentController::class, 'equipmentTransferEdit'])->name("inventory.equipmentTransferEdit");
    Route::post("equipmentTransferUpdate", [InventoryEquipmentController::class, 'equipmentTransferUpdate'])->name("inventory.equipmentTransferUpdate");
    Route::get("equipmentTransferDelete/{id}", [InventoryEquipmentController::class, 'equipmentTransferDelete'])->name("inventory.equipmentTransferDelete");

    // Permenent asset Transfer
    Route::get("permanent-asset-transfer", [InventoryPermanentAssetTransferController::class, 'permanentTransferShow'])->name("inventory.permanentTransferShow");
    Route::get("permanent-asset-transfer-add", [InventoryPermanentAssetTransferController::class, 'PermanentAssetsAdd'])->name("inventory.PermanentAssetsAdd");
    Route::post("permanent-asset-transfer-store", [InventoryPermanentAssetTransferController::class, 'PermanentAssetsTransferAdd'])->name("inventory.PermanentAssetsTransferAdd");
    Route::post("permanent-asset-transfer-store-new", [InventoryPermanentAssetTransferController::class, 'PermanentAssetsTransferAddNew'])->name("inventory.PermanentAssetsTransferAddNew");
    Route::get("permanent-assets/{id}", [InventoryPermanentAssetTransferController::class, 'PermanentAssetsAll'])->name("inventory.PermanentAssetsAll");

    //Reports

    Route::get('/permanent-asset-transfer-report', [InventoryPermanentAssetTransferController::class, 'permanentTransferReport'])->name("inventory.permanentTransferReport");
    Route::post('/permanent-asset-transfer-report', [InventoryPermanentAssetTransferController::class, 'permanentTransferReportView'])->name("inventory.permanentTransferReportView");



    // return reason           Inventory-Return Reason-access model
    Route::get('/reason-show-all', [InventoryReturnReasonController::class, 'reasonShowAll'])->name("inventory.reasonShowAll");
    Route::post('/reason-add-process', [InventoryReturnReasonController::class, 'reasonAddProcess'])->name("inventory.reasonAddProcess");
    Route::post('/reason-update-process', [InventoryReturnReasonController::class, 'reasonUpdateProcess'])->name("inventory.reasonUpdateProcess");

    // outdoor return             Inventory-Outdoor Return-access model
    Route::get('/outdoor-return-add', [InventoryOutdoorReturnController::class, 'outdoorReturnAdd'])->name("inventory.outdoorReturnAdd");
    Route::post('/outdoor-return-add-process', [InventoryOutdoorReturnController::class, 'outdoorReturnAddProcess'])->name("inventory.outdoorReturnAddProcess");
    Route::get('/outdoor-return-edit/{id}', [InventoryOutdoorReturnController::class, 'outdoorReturnEdit'])->name("inventory.outdoorReturnEdit");
    Route::post('/outdoor-return-update-process', [InventoryOutdoorReturnController::class, 'outdoorReturnUpdateProcess'])->name("inventory.outdoorReturnUpdateProcess");
    Route::get('/outdoor-return-show-all', [InventoryOutdoorReturnController::class, 'outdoorReturnShowAll'])->name("inventory.outdoorReturnShowAll");
    Route::get('/outdoor-return-delete/{id}', [InventoryOutdoorReturnController::class, 'outdoorReturnDelete'])->name("inventory.outdoorReturnDelete");

    // company return
    Route::get('/company-return', [company_return_controller::class, 'company_return_view'])->name("inventory.company_return_view");
    Route::get('/company-return-add', [company_return_controller::class, 'companyReturnAdd'])->name("inventory.companyReturnAdd");
    Route::post('/company-return-add-process', [company_return_controller::class, 'companyReturnAddProcess'])->name("inventory.companyReturnAddProcess");

    Route::get('/purchase-view-by-product', [company_return_controller::class, 'PurchaseItemview'])->name("inventory.PurchaseItemview");
    // Route::post('/department-total-qty', [company_return_controller::class, 'viewPurchaseItem'])->name("inventory.viewPurchaseItemQty");

    // indoor return       Inventory-Indoor Return-access model
    Route::get('/indoor-return-add', [InventoryIndoorReturnController::class, 'indoorReturnAdd'])->name("inventory.indoorReturnAdd");
    Route::post('/indoor-return-add-process', [InventoryIndoorReturnController::class, 'indoorReturnAddProcess'])->name("inventory.indoorReturnAddProcess");
    Route::get('/indoor-return-edit/{id}', [InventoryIndoorReturnController::class, 'indoorReturnEdit'])->name("inventory.indoorReturnEdit");
    Route::post('/indoor-return-update-process', [InventoryIndoorReturnController::class, 'indoorReturnUpdateProcess'])->name("inventory.indoorReturnUpdateProcess");
    Route::get('/indoor-Foodcity-return-edit/{id}', [InventoryIndoorReturnController::class, 'indoorFoodcityReturnEdit'])->name("inventory.indoorFoodcityReturnEdit");
    Route::post('/indoor-foodcity-return-update-process', [InventoryIndoorReturnController::class, 'indoorFoodcityReturnUpdateProcess'])->name("inventory.indoorFoodcityReturnUpdateProcess");
    Route::get('/indoor-return-show-all', [InventoryIndoorReturnController::class, 'indoorReturnShowAll'])->name("inventory.indoorReturnShowAll");
    Route::get('/indoor-return-delete/{id}', [InventoryIndoorReturnController::class, 'indoorReturnDelete'])->name("inventory.indoorReturnDelete");
    Route::get('/indoor-foodcity-return-delete/{id}', [InventoryIndoorReturnController::class, 'indoorFoodcityReturnDelete'])->name("inventory.indoorFoodcityReturnDelete");    // reports

    // Out door return report
    Route::get('/out-door-return-report', [InventoryOutdoorReturnController::class, 'outDoorReturnReport'])->name("inventory.outDoorReturnReport");
    Route::post('/out-door-return-report', [InventoryOutdoorReturnController::class, 'outDoorReturnReportPost'])->name("inventory.outDoorReturnReportPost");

    // reports
    // indoor transfer report
    Route::get('/indoor-transfer-report', [InventoryIndoorTransferController::class, 'indoorTransferReport'])->name("inventory.indoorTransferReport");
    Route::post('/indoor-transfer-report', [InventoryIndoorTransferController::class, 'indoorTransferReportView'])->name("inventory.indoorTransferReportView");

    // indoor return report
    Route::get('/indoor-return-report', [InventoryIndoorReturnController::class, 'indoorReturnReport'])->name("inventory.indoorReturnReport");
    Route::post('/indoor-return-report', [InventoryIndoorReturnController::class, 'indoorReturnReportView'])->name("inventory.indoorReturnReportView");

    // purchase order
    Route::get('/purchase-order-report', [InventoryPurchaseOrderController::class, 'purchaseOrderReport'])->name("inventory.purchaseOrderReport");
    Route::post('/purchase-order-report', [InventoryPurchaseOrderController::class, 'purchaseOrderReportView'])->name("inventory.purchaseOrderReportView");

    // expery date
    Route::get('/expery-date-report', [InventoryPurchaseItemController::class, 'experyDateReport'])->name("inventory.experyDateReport");
    Route::post('/expery-date-report', [InventoryPurchaseItemController::class, 'experyDateReportPost'])->name("inventory.experyDateReportPost");

    // Purchase Order Request Report
    Route::post('purchaseOrderRequestRepShow', [InventoryPurOrdReqRepController::class, 'purchaseOrderRequestRepShow'])->name("inventory.purchaseOrderRequestRepShow");
    Route::get('purchaseOrderRequestRepMonth', [InventoryPurOrdReqRepController::class, 'purchaseOrderRequestRepMonth'])->name("inventory.purchaseOrderRequestRepMonth");

    // Permanent Assets report
    Route::post('permanantAssetsReportShow', [InventoryPerAsseRepController::class, 'permanantAssetsReportShow'])->name("inventory.permanantAssetsReportShow");
    Route::get('permanantAssetsReportMonth', [InventoryPerAsseRepController::class, 'permanantAssetsReportMonth'])->name("inventory.permanantAssetsReportMonth");

    Route::post("dashIndoorTransfer", [inventoryDashController::class, 'dashIndoorTransfer'])->name("inventory.dashIndoorTransfer");
    Route::post("dashoutdoorReturn", [inventoryDashController::class, 'dashoutdoorReturn'])->name("inventory.dashoutdoorReturn");

    // equipment transfer
    Route::get('/equipment-transfer-report', [InventoryEquipmentController::class, 'equipmentTransferReport'])->name("inventory.equipmentTransferReport");
    Route::post('/equipment-transfer-report', [InventoryEquipmentController::class, 'equipmentTransferReportView'])->name("inventory.equipmentTransferReportView");

    // purchased reports
    Route::get('/purchased-item-report', [InventoryPurchaseItemController::class, 'purchaseItemReport'])->name("inventory.purchaseItemReport");
    Route::post('/purchased-item-report', [InventoryPurchaseItemController::class, 'purchaseItemReportPost'])->name("inventory.purchaseItemReportPost");

    // purchase order request new
    Route::get('/PurchaseRequestShow', [InventoryPurchaseRequestController::class, 'PurchaseRequestShow'])->name("inventory.PurchaseRequestShow");
    Route::get('/PurchaseRequestShowAdd', [InventoryPurchaseRequestController::class, 'PurchaseRequestShowAdd'])->name("inventory.PurchaseRequestShowAdd");
    Route::get('/PurchaseRequestView/{id}', [InventoryPurchaseRequestController::class, 'PurchaseRequestView'])->name("inventory.PurchaseRequestView");
    Route::get('/PurchaseRequestChange/{id}', [InventoryPurchaseRequestController::class, 'PurchaseRequestChange'])->name("inventory.PurchaseRequestChange");
    Route::get('/PurchaseRequestEdit/{id}', [InventoryPurchaseRequestController::class, 'PurchaseRequestEdit'])->name("inventory.PurchaseRequestEdit");

    // purchase order new
    Route::get('/newPurchaseOrderShow', [newInventoryPurchaseOrder::class, 'newPurchaseOrderShow'])->name("inventory.newPurchaseOrderShow");
    Route::get('/newPurchaseOrderAdd', [newInventoryPurchaseOrder::class, 'newPurchaseOrderAdd'])->name("inventory.newPurchaseOrderAdd");

    // electric ministore used
    Route::get('/electric-use-add', [InventoryElectricUsedController::class, 'electricUseAdd'])->name("inventory.electricUseAdd");
    Route::post('/electric-use-add-process', [InventoryElectricUsedController::class, 'electricUseAddProcess'])->name("inventory.electricUseAddProcess");
    Route::get('/electric-use-showall', [InventoryElectricUsedController::class, 'electricUseShowall'])->name("inventory.electricUseShowall");
    Route::get('/electric-use-edit', [InventoryElectricUsedController::class, 'electricUseEdit'])->name("inventory.electricUseEdit");
    Route::post('/electric-use-update-process', [InventoryElectricUsedController::class, 'electricUseUpdateProcess'])->name("inventory.electricUseUpdateProcess");

    // Report
    Route::get('/stock-by-date', [inventoryNewStockController::class, 'stockByDate'])->name('inventory.stockByDate');

    // ***********END INVENTRY***********

    // **********START HR*************

    // department status change
    Route::get('/department/change-status', [DepartmentController::class, 'changeStatus'])->name('department.change-status');
    // employee status change
    Route::get('/employee/change-status', [EmployeeController::class, 'changeStatus'])->name('employee.change-status');

    Route::post('/employee/store-emp-all-data', [EmployeeController::class, 'storeEmpAllData'])->name('employee.storeEmpAllData');
    Route::get('/employee/emp-salary', [EmployeeController::class, 'empSalary'])->name('employee.emp-salary');

    // search employee For Salary Part
    Route::get('/search-emp/salary-part', [EmployeeSalaryPartController::class, 'searchEmpForSalaryPart'])->name('search-employee.salary-part');
    // get Allowance Amount By emp_id 
    Route::get('/allowance/amount/by-emp-id', [EmployeeSalaryPartController::class, 'getAllowanceAmountByEmpId'])->name('allowance.amount.by-emp-id');

    // search for emp basic salary
    Route::get('/search-emp/basic-salary', [EmployeeBasicSalaryController::class, 'searchEmpBasicSalary'])->name('search.empBasicSalary');

    // emp salary payable store
    Route::post('/emp/salary-payable/store', [SalaryPayableController::class, 'store'])->name('hr.salaryPayable.store');
    // get employee datails for salary
    Route::get('/emp/salary/details', [SalaryPayableController::class, 'getDetailForSalary'])->name('hr.getDetailForSalary');
    // view salary payable
    Route::get('/emp/salary-payable/view/{id}', [SalaryPayableController::class, 'view'])->name('hr.salaryPayable.view');

    // common employee search
    Route::get('/common/search-employee', [CommonController::class, 'commonSearchEmployee'])->name('common.search-employee');

    //  employee search for user create
    Route::get('/user/search-employee', [UserController::class, 'searchEmpForUser'])->name('user.search-employee');


    // login user dashboard
    Route::get('/auth/dashboard', [DashboardController::class, 'authDashboard'])->name('hr.auth.dashboard');

    Route::get('/report/salary-payable-report', [HRReportController::class, 'salaryPayableReport'])->name('hr.salaryPayableReport');
    Route::get('/report/basic-salary-report', [HRReportController::class, 'basicSalaryReport'])->name('hr.basicSalaryReport');

    // employee view ajax
    Route::get('/emp/view-data', [EmployeeController::class, 'empViewDataAjax'])->name('hr.empViewDataAjax');

    // work type
    Route::get('/work-type-show', [WorkTypeController::class, 'index'])->name('worktype.index');
    Route::post('/work-type-store', [WorkTypeController::class, 'store'])->name('worktype.store');

    // employee type
    Route::get('/employee-type-show', [EmployeeTypeController::class, 'index'])->name('employeetype.index');
    Route::post('/employee-type-store', [EmployeeTypeController::class, 'store'])->name('employeetype.store');

    // employee
    Route::get('/employee-show', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('/employee-store', [EmployeeController::class, 'store'])->name('employee.store');

    // employee doc
    Route::get('/employee-doc-show', [EmployeeDocController::class, 'index'])->name('employeedoc.index');
    Route::post('/employee-doc-store', [EmployeeDocController::class, 'store'])->name('employeedoc.store');


    // activity & login  log
    Route::get('/activity-log', [ActivityLogController::class, 'view'])->name('activity-log.view');
    Route::get('/login-log', [LoginLogController::class, 'index'])->name('login-log.index');


    // allowance type
    Route::get('/allowance-type-show', [AllowanceTypeController::class, 'index'])->name('hr.allowanceType.index');
    Route::post('/allowance-type-store', [AllowanceTypeController::class, 'Store'])->name('hr.allowanceType.store');

    // employee salary part
    Route::get('/employee/salaty-part-show', [EmployeeSalaryPartController::class, 'index'])->name('hr.empSalaryPart.index');
    Route::post('/employee/salaty-part-store', [EmployeeSalaryPartController::class, 'Store'])->name('hr.empSalaryPart.store');

    // employee basic salary
    Route::get('/employee/basic-salary-show', [EmployeeBasicSalaryController::class, 'index'])->name('hr.empBasicSalary.index');
    Route::post('/employee/basic-salary-store', [EmployeeBasicSalaryController::class, 'Store'])->name('hr.empBasicSalary.store');

    // salary payable
    Route::get('/emp/salary-payable', [SalaryPayableController::class, 'index'])->name('hr.salaryPayable.index');
    Route::get('/emp/salary-payable/create', [SalaryPayableController::class, 'create'])->name('hr.salaryPayable.create');

    // advance_payments
    Route::get('/advance-payment-show', [AdvancePaymentController::class, 'index'])->name('hr.advancePayment.index');
    Route::post('/advance-payment-store', [AdvancePaymentController::class, 'Store'])->name('hr.advancePayment.store');

    // attendance report
    Route::get('/report/attendance-report', [HRReportController::class, 'attendanceReport'])->name('hr.report.attendance-report');
    // employee report
    Route::get('/report/emp-report', [HRReportController::class, 'employeeReport'])->name('hr.report.emp-report');
    // leave report
    Route::get('/report/leave-report', [HRReportController::class, 'leaveReport'])->name('hr.report.leaveReport');
    // epf & etf report
    Route::get('/report/epf-etf-report', [HRReportController::class, 'epfEtfReport'])->name('hr.report.epfEtfReport');
    // advance payment report
    Route::get('/report/advance-payment-report', [HRReportController::class, 'advancePaymentReport'])->name('hr.report.advancePaymentReport');
    // over time work report
    Route::get('/report/over-time-report', [HRReportController::class, 'overTimeReport'])->name('hr.report.overTimeReport');

    // print salary sheet
    Route::get('/salary-pay-by-bank', [SalaryPayPrintController::class, 'salaryPayByBank'])->name('hr.salary-pay-by-bank');
    Route::get('/salary-pay-by-hand', [SalaryPayPrintController::class, 'salaryPayByHand'])->name('hr.salary-pay-by-hand');

    // report - month
    Route::get('/report/attendance-report-month', [HRReportController::class, 'attendanceReportMonth'])->name('hr.report.attendance-report-month');

    // **********END HR*************


    //********************START ACCOUNTS*********************************** */
    //Income
    Route::get('/admin/income', [IncomeController::class, 'index'])->name('income.index');
    Route::post('/admin/income', [IncomeController::class, 'indexFilter'])->name('income.indexFilter');

    //Other Income
    Route::post('/other-income-add-process', [AccountsOtherIncomesController::class, 'otherIncomeStore'])->name('otherincome.otherIncomeStore');
    Route::get('/other-income-view', [AccountsOtherIncomesController::class, 'otherIncomeShow'])->name('otherincome.otherIncomeShow');
    Route::post('/other-income-update', [AccountsOtherIncomesController::class, 'otherIncomeUpdate'])->name('otherincome.otherIncomeUpdate');

    Route::get('/other-income-categeory-view', [AccountsOtherIncomesCategeoryController::class, 'otherIncomeCategeoryShow'])->name('otherincome.otherIncomeCategeoryShow');
    Route::post('/other-income-add-categeory', [AccountsOtherIncomesCategeoryController::class, 'otherIncomecategeoryStore'])->name('otherincome.otherIncomecategeoryStore');


    // Bank
    Route::get('/bank-show', [accountBankController::class, 'bankShow'])->name('account.bankShow');
    Route::post('/bank-store', [accountBankController::class, 'bankAdd'])->name('account.bankAdd');

    // bank balancebank-balance-filter
    Route::get('/bank-balance-show', [accountBankController::class, 'bankBalanceShow'])->name('account.bankBalanceShow');

    // Bank Charges Types
    Route::get('/bank-chargestype-show', [accountBankChargesTypeController::class, 'bankChargesTypesShow'])->name('account.bankChargesTypesShow');
    Route::post('/bank-chargestype-store', [accountBankChargesTypeController::class, 'bankChargesTypesStore'])->name('account.bankChargesTypesStore');

    // Bank Intrest
    Route::get('/bank-intrest-show', [accountBankIntrestController::class, 'bankIntrestShow'])->name('account.bankIntrestShow');
    Route::post('/bank-intrest-store', [accountBankIntrestController::class, 'bankIntrestStore'])->name('account.bankIntrestStore');

    // Bank Charges
    Route::get('/bank-charges-view', [AccountsBankChargesController::class, 'bankChargesShow'])->name('bank.bankChargesShow');
    Route::post('/bank-charges-store', [AccountsBankChargesController::class, 'bankChargesStore'])->name('bank.bankChargesStore');
    Route::post('/bank-charges-update', [AccountsBankChargesController::class, 'bankChargesUpdate'])->name('bank.bankChargesUpdate');


    // Bank Transection
    Route::get('/bank-transection-show', [accountBankTransectionController::class, 'bankTransectionShow'])->name('account.bankTransectionShow');
    Route::post('/bank-transection-store', [accountBankTransectionController::class, 'bankTransectionStore'])->name('account.bankTransectionStore');

    //Service Type
    Route::get('/service-type-view', [AccountsServiceTypeController::class, 'serviceTypeShow'])->name('service.serviceTypeShow');
    Route::post('/service-type-store', [AccountsServiceTypeController::class, 'serviceTypeStore'])->name('service.serviceTypeStore');
    Route::post('/service-type-update', [AccountsServiceTypeController::class, 'serviceTypeUpdate'])->name('service.serviceTypeUpdate');


    // Service provider
    Route::get('/service-provider-show', [accountServiceProviderController::class, 'serviceProviderShow'])->name('account.serviceProviderShow');
    Route::post('/service-provider-store', [accountServiceProviderController::class, 'serviceProviderStore'])->name('account.serviceProviderStore');

    // Service Charge
    Route::get('/service-charge-show', [accountServiceChargeController::class, 'serviceChargeShow'])->name('account.serviceChargeShow');
    Route::get('/service-charge-Add', [accountServiceChargeController::class, 'serviceChargeAdd'])->name('account.serviceChargeAdd');
    Route::get('/service-charge-Edit/{id}', [accountServiceChargeController::class, 'serviceChargeEdit'])->name('account.serviceChargeEdit');
    Route::post('/service-charge-Store', [accountServiceChargeController::class, 'serviceChargeStore'])->name('account.serviceChargeStore');

    // cheque payment
    Route::get('/cheque-payment-show', [accountChequePaymentController::class, 'chequePaymentShow'])->name('account.chequePaymentShow');
    Route::post('/cheque-payment-pending-update', [accountChequePaymentController::class, 'chequePaymentPendingupdate'])->name('account.chequePaymentPendingupdate');
    Route::get('/cheque-payment-pending', [accountChequePaymentController::class, 'chequePaymentPending'])->name('account.chequePaymentPending');
    Route::get('/cheque-payment-update/{id}', [accountChequePaymentController::class, 'chequePaymentUpdate'])->name('account.chequePaymentUpdate');

    //Service Expense
    Route::get('/service-expenses-view', [AccountsServiceExpenseController::class, 'serviceExpenseShow'])->name('serviceExpense.serviceExpenseShow');
    Route::post('/service-expenses-store', [AccountsServiceExpenseController::class, 'serviceExpenseStore'])->name('serviceExpense.serviceExpenseStore');
    Route::post('/service-expenses-update', [AccountsServiceExpenseController::class, 'serviceExpenseUpdate'])->name('serviceExpense.serviceExpenseUpdate');
    Route::get('/service-expenses-show/{id}', [AccountsServiceExpenseController::class, 'serviceExpenseViewByid'])->name('serviceExpense.serviceExpenseViewByid');


    // Route::get('/other-income-add',[AccountsOtherIncomesController::class,'otherIncome'])->name('otherIncome.add');
    Route::post('/other-income-add-process', [AccountsOtherIncomesController::class, 'otherIncomeStore'])->name('otherincome.otherIncomeStore');
    Route::get('/other-income-view', [AccountsOtherIncomesController::class, 'otherIncomeShow'])->name('otherincome.otherIncomeShow');
    Route::get('/other-income-edit/{id}', [AccountsOtherIncomesController::class, 'otherIncomeEdit'])->name('otherincome.otherIncomeEdit');
    Route::post('/other-income-update', [AccountsOtherIncomesController::class, 'otherIncomeUpdate'])->name('otherincome.otherIncomeUpdate');

    //Other Expenses
    Route::get('/other-expenses-view', [AccountsOtherExpenseController::class, 'otherExpenseShow'])->name('otherExpense.otherExpenseShow');
    Route::post('/other-expenses-store', [AccountsOtherExpenseController::class, 'otherExpenseStore'])->name('otherExpense.otherExpenseStore');
    Route::post('/other-expenses-update', [AccountsOtherExpenseController::class, 'otherExpenseUpdate'])->name('otherExpense.otherExpenseUpdate');
    Route::get('/other-expenses-show/{id}', [AccountsOtherExpenseController::class, 'otherExpenseViewByid'])->name('otherExpense.otherExpenseViewByid');

    //Other Expenses Category
    Route::get('/other-expenses-category', [AccountsOtherExpenseController::class, 'otherExpenseCategory'])->name('otherExpense.otherExpenseCategory');
    Route::post('/other-expenses-category-store', [AccountsOtherExpenseController::class, 'otherExpenseCategoryStore'])->name('otherExpense.otherExpenseCategoryStore');


    //Other Expenses Subcategory
    Route::get('/other-expenses-type', [AccountOtherExpensesTypeController::class, 'index'])->name('otherExpense.otherExpenseType');
    Route::post('/other-expenses-type-store', [AccountOtherExpensesTypeController::class, 'store'])->name('otherExpense.otherExpenseTypeSave');

    //Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expense.index');
    Route::post('/expenses', [ExpenseController::class, 'indexFilter'])->name('expense.indexFilter');

    //Accounts Report
    Route::get('/account-monthlyReport', [accountReportController::class, 'monthlyReport'])->name('account.monthlyReport');
    Route::get('/account-DetailsReport', [AccountsDeatailReportController::class, 'DetailsReport'])->name('account.DetailsReport');
    Route::get('/account-profit-loss-Report', [AccountsDeatailReportController::class, 'profitlossReport'])->name('account.profitlossReport');
    Route::get('/account-balance-sheet-Report', [AccountsDeatailReportController::class, 'balancesheetReport'])->name('account.balancesheetReport');

    // account department
    Route::get("/account_dept_show", [account_department_controller::class, 'account_dept_show'])->name('account.account_dept_show');
    Route::post("/account_dept_store", [account_department_controller::class, 'account_dept_store'])->name('account.account_dept_store');


    //Ledgers
    Route::get('/ledgers', [AccountsLedgerController::class, 'ledgerShow'])->name('account.ledgerShow');
    Route::get('/sales-details-showall', [AccountsLedgerController::class, 'salesDetailsShowall'])->name('account.salesDetailsShowall');
    Route::get('/cheque-details-showall', [AccountsLedgerController::class, 'chequeDetailsShowall'])->name('account.chequeDetailsShowall');

    // New service expense
    Route::get('/new-service-expense', [newServiceExpenseController::class, 'newServiceExpense'])->name('account.newServiceExpense');
    Route::post('/get-service-expense-amount', [newServiceExpenseController::class, 'getServiceExpenseAmount'])->name('account.getServiceExpenseAmount');
    Route::post('/dept-service-expense-amount', [newServiceExpenseController::class, 'deptServiceExpenseAmount'])->name('account.deptServiceExpenseAmount');

    // profit loss account
    Route::get("/account_profit_loss_foodcity/{type}", [FoodcityProfitlossController::class, 'account_profit_loss_foodcity'])->name('account.account_profit_loss_foodcity');
    Route::get("/account_profit_loss_inventory/{type}", [InventortProfitlossController::class, 'account_profit_loss_inventory'])->name('account.account_profit_loss_inventory');
    Route::get("/account_profit_loss_hr/{type}", [HrProfitlossController::class, 'account_profit_loss_hr'])->name('account.account_profit_loss_hr');
    Route::get('/service-charge-View/{id}', [accountServiceChargeController::class, 'serviceChargeView'])->name('account.serviceChargeView');

    Route::get('/Accounts/Showcategory', [AddCategeoryController::class, 'Showcategory'])->name('Accounts.Showcategory');
    Route::post('/Accounts/Addcategory', [AddCategeoryController::class, 'Addcategory'])->name('Accounts.Addcategory');

    Route::get('/Accounts/Showsubcategory', [AddCategeoryController::class, 'Showsubcategory'])->name('Accounts.Showsubcategory');
    Route::post('/Accounts/AddSubcategory', [AddCategeoryController::class, 'AddSubcategory'])->name('Accounts.AddSubcategory');

    Route::get('/Accounts/Accounts-Details', [Accounts_DetailsController::class, 'index'])->name('Accounts.index');
    Route::post('/Accounts/Accounts-Details-add', [Accounts_DetailsController::class, 'AddAccountsDeatils'])->name('Accounts.AddAccountsDeatils');

    //owner transaction
    Route::get('/owner/owner-list', [OwnerController::class, 'index'])->name('owner.index');
    Route::post('/owner/owner-list-add', [OwnerController::class, 'StoreOwnerList'])->name('owner.StoreOwnerList');

    Route::get('/owner/owner-transaction', [OwnerController::class, 'ownertransactionindex'])->name('owner.ownertransactionindex');
    Route::post('/owner/owner-transaction-add', [OwnerController::class, 'StoreOwnertransaction'])->name('owner.StoreOwnertransaction');

    Route::get('/owner/owner-transaction-payment-view', [OwnerController::class, 'ownertransactionpaymentindex'])->name('owner.ownertransactionpaymentindex');
    Route::post('/owner/owner-transaction-payment-add', [OwnerController::class, 'saveownertransactionpayment'])->name('owner.saveownertransactionpayment');

    //owner transaction


    Route::get('/Accounts/ShowclosedBalance', [Closed_Balance::class, 'ShowclosedBalance'])->name('Accounts.ShowclosedBalance');
    Route::post('/Accounts/AddclosedBalance', [Closed_Balance::class, 'AddclosedBalance'])->name('Accounts.AddclosedBalance');

    Route::get('/Accounts/ShowDailyCash', [DailyCashBook_Controller::class, 'ShowDailyCash'])->name('Accounts.ShowDailyCash');
    Route::post('/Accounts/AddDailyCash', [DailyCashBook_Controller::class, 'AddDailyCash'])->name('Accounts.AddDailyCash');

    Route::get('/Accounts/ShowDailyCashReport', [DailyAdjustmentReportController::class, 'ShowDailyCashReport'])->name('Accounts.ShowDailyCashReport');
    Route::post('/Accounts/AddDailyCashReport', [DailyAdjustmentReportController::class, 'AddDailyCashReport'])->name('Accounts.AddDailyCashReport');

    Route::get('/Accounts/ShowTrialBalanceReport', [TrialBalanceController::class, 'ShowTrialBalanceReport'])->name('Accounts.ShowTrialBalanceReport');
    Route::get('/Accounts/FinalTrialBalanceReport', [TrialBalanceController::class, 'FinalTrialBalanceReport'])->name('Accounts.FinalTrialBalanceReport');
    Route::get('/Accounts/FinalTrialBalanceMonth', [TrialBalanceController::class, 'FinalTrialBalanceMonth'])->name('Accounts.FinalTrialBalanceMonth');

    // mukunthan
    Route::get('/Mukunthan_Anna_Collection', [MukunthanController::class, 'index'])->name('mukunthan.index');
    Route::post('/Mukunthan_Anna_Collection/store', [MukunthanController::class, 'store'])->name('mukunthan.store');

    //********************END ACCOUNTS*********************************** */
});

// ********* ACCOUNTS *************

// inventory
Route::get('/purchase-order-edit/{id}', [InventoryPurchaseOrderController::class, 'purchaseOrderEdit'])->name("inventory.purchaseOrderEdit");
// Route::get('/purchase-order-view/{id}', [InventoryPurchaseOrderController::class, 'purchaseOrderView'])->name("inventory.purchaseOrderView");
Route::get('/purchase-order-add', [InventoryPurchaseOrderController::class, 'purchaseOrderAdd'])->name("inventory.purchaseOrderAdd");
Route::post('/purchase-boucher-add-process', [InventoryPurchaseOrderController::class, 'purchaseBoucherAddProcess'])->name("inventory.purchaseBoucherAddProcess");
Route::post('/purchase-boucher-update-process', [InventoryPurchaseOrderController::class, 'purchaseBoucherUpdateProcess'])->name("inventory.purchaseBoucherUpdateProcess");


// foodcity accounts
Route::post('/foodcity-sales', [foodcitAccountController::class, 'foodcitySales'])->name("account.foodcitySales");
Route::post('/foodcity-sales-return', [foodcitAccountController::class, 'foodcitySalesReturn'])->name("account.foodcitySalesReturn");
Route::post('/foodcity-purchases', [foodcitAccountController::class, 'foodcityPurchases'])->name("account.foodcityPurchases");
Route::post('/foodcity-purchases-Return', [foodcitAccountController::class, 'foodcityPurchasesReturn'])->name("account.foodcityPurchasesReturn");
Route::post('/foodcity-otherExpense', [foodcitAccountController::class, 'foodcityotherExpense'])->name("account.foodcityotherExpense");
Route::post('/foodcity-debtors', [foodcitAccountController::class, 'foodcityDebtors'])->name("account.foodcityDebtors");


//One and only account

// Accounts New
Route::get('/account-balance/{type}/{id}', [AccountController::class, 'AccountBalance'])->name("account.AccountBalance");
Route::get('/account-ledger/{dept}/{table}', [AccountController::class, 'AccountLedger'])->name("account.AccountLedger");
Route::post('/account-other-exp', [AccountController::class, 'AccountOtherExp'])->name("account.AccountOtherExp");
Route::post('/account-service-charges', [AccountController::class, 'AccountServiceCharges'])->name("account.AccountServiceCharges");
Route::get('/account-main/{type}', [AccountController::class, 'AccountMain'])->name("account.AccountMain");
//Account DB View
Route::get('/account-database', [AccountController::class, 'accountDatabase']);
Route::get('/department-search', [account_department_controller::class, 'departmentSearch']);


// ********* ACCOUNTS *************

// ajax
// electric ministore used
Route::get('/electric-use-add-getdata/{id}', [InventoryElectricUsedController::class, 'electricUseAddGetdata'])->name("inventory.electricUseAddGetdata");

Route::post('/purchase-view-by-product-id', [InventoryOutdoorReturnController::class, 'viewPurchaseItem'])->name("inventory.viewPurchaseItem");
Route::post('/department-total-qty', [InventoryIndoorReturnController::class, 'viewPurchaseItem'])->name("inventory.viewPurchaseItemQty");

// brand
Route::get('/brandEdit/{id}', [InventoryBrandController::class, 'brandEdit'])->name("inventory.brandEdit");

// product
Route::get('/getBrandId/{id}', [InventoryProductController::class, 'getBrandId'])->name("inventory.getBrandId");

// indoor transfer
Route::get("/GetPurchaseId/{id}", [InventoryIndoorTransferController::class, 'GetPurchaseId'])->name("inventory.GetPurchaseId");

// indoor return report
Route::post('/get-specific-indoor-return-report', [InventoryIndoorReturnController::class, 'getDetailsIndoorReturnReport'])->name("inventory.getDetailsIndoorReturnReport");

// indoor transfer report
Route::post('/get-specific-details-for-report', [InventoryIndoorTransferController::class, 'getDetailsForReport'])->name("inventory.getDetailsForReport");

// permanent assets report
Route::post('permanantAssetsReportModal', [InventoryPerAsseRepController::class, 'permanantAssetsReportModal'])->name("inventory.permanantAssetsReportModal");

// purchased report
Route::post('/purchase-item-by-proid-brandid', [InventoryPurchaseItemController::class, 'purchaseItemByProidBrandid'])->name("inventory.purchaseItemByProidBrandid");

// purchased order request report
Route::post('purchaseOrderRequestRepModal', [InventoryPurOrdReqRepController::class, 'purchaseOrderRequestRepModal'])->name("inventory.purchaseOrderRequestRepModal");

// purchase order Request new
Route::post('/PurchaseRequestAdd', [InventoryPurchaseRequestController::class, 'PurchaseRequestAdd'])->name("inventory.PurchaseRequestAdd");
Route::post('/PurchaseRequestUpdate', [InventoryPurchaseRequestController::class, 'PurchaseRequestUpdate'])->name("inventory.PurchaseRequestUpdate");
Route::post('/PurchaseRequestChangeUpdate', [InventoryPurchaseRequestController::class, 'PurchaseRequestChangeUpdate'])->name("inventory.PurchaseRequestChangeUpdate");

// purchase order new
Route::post('/newPurchaseOrderStore', [newInventoryPurchaseOrder::class, 'newPurchaseOrderStore'])->name("inventory.newPurchaseOrderStore");
Route::get('/newPurchaseOrderView/{id}', [newInventoryPurchaseOrder::class, 'newPurchaseOrderView'])->name("inventory.newPurchaseOrderView");
Route::post('/newPurchaseOrderUpdate', [newInventoryPurchaseOrder::class, 'newPurchaseOrderUpdate'])->name("inventory.newPurchaseOrderUpdate");

//  new purchase item add
Route::get('/new-purchased-item-add', [InventoryPurchaseOrderController::class, 'newPurchaseItemAdd'])->name("inventory.newPurchaseItemAdd");
Route::get('/good-receive-data/{id}', [InventoryPurchaseOrderController::class, 'goodReceiveData'])->name("inventory.goodReceiveData");
Route::post('/purchase-boucher-update-process', [InventoryPurchaseOrderController::class, 'purchaseBouelectric-use-edit?cherUpdateProcess'])->name("inventory.purchaseBoucherUpdateProcess");

// get stock report
Route::get('/stock-report', [inventoryStockController::class, 'stockReport'])->name("inventory.stockReport");
Route::post('/stock-report', [inventoryStockController::class, 'purchaseStockReportPost'])->name("inventory.purchaseStockReportPost");

// calculate new stock sales
Route::get('/sales_stock_view/{type}', [SalesStockAdjustmentController::class, 'sales_stock'])->name('sales.sales_stock');
Route::POST('/sales-adjustment-store', [SalesStockAdjustmentController::class, 'salesstockAdjustmentStore'])->name('sales.salesstockAdjustmentStore');

//Product Price
Route::get('/product-price', [inventoryNewStockController::class, 'productPrice'])->name('inventory.productPrice');
Route::get('/new-product-price', [inventoryNewStockController::class, 'newproductPrice'])->name('inventory.newproductPrice');
Route::get('/last-price/{product}', [inventoryNewStockController::class, 'productLastPrice'])->name('inventory.productLastPrice');


Route::get('/credit-payment-show', [InventoryPurchaseOrderController::class, 'creditPaymentShow'])->name("inventory.creditPaymentShow");
Route::post('/credit-payment-store', [InventoryPurchaseOrderController::class, 'creditPaymentStore'])->name("inventory.creditPaymentStore");
Route::get('/credit-payments', [InventoryPurchaseOrderController::class, 'creditPayments'])->name("inventory.creditPayments");

// Product Code
Route::get('/product-code/{id}', [InventoryProductController::class, 'productCode']);
Route::get('/product-moving-count', [InventoryProductController::class, 'productMovingCount'])->name('inventory.productMovingCount');

Route::post('/stock-by-date-product', [inventoryNewStockController::class, 'stockByDateProduct'])->name('inventory.stockByDateProduct');
Route::post('/stock-by-date-transfer', [inventoryNewStockController::class, 'stockByDateTransfer'])->name('inventory.stockByDateTransfer');

Route::get('/pro_type', [InventoryProductController::class, 'pro_type']);

Route::get('/clear', [InventoryProductCategoryController::class, 'clear']);
