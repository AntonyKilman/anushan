-
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jaffna Electrical ERP</title>
    <link href="{{ asset('assets/css/yearpicker.css') }}" rel="stylesheet">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/JFE.png') }}" />
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    {{-- data table --}}
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        ef="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">

    @yield('link')

</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn">
                                <i data-feather="align-justify"></i></a></li>
                        <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i></a>
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">

                    @if (in_array('main_farm_request.index', session('Access')))
                        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                                class="nav-link notification-toggle nav-link-lg"><i data-feather="bell"
                                    class="bell"></i>
                            </a>
                            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                                <div class="dropdown-header">
                                    Hello!

                                </div>
                                <div class="dropdown-list-content dropdown-list-icons">

                                </div>
                                <div class="dropdown-footer text-center">
                                    <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </li>
                    @endif





                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="{{ asset('assets/img/user1.png') }}" class="user-img-radious-style"> <span
                                class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">Hello
                                @if (in_array('main_reset.index', session('Access')))
                                    <a href="{{ route('main_reset.index') }}" class="dropdown-item has-icon"> <i
                                            class="fas fa-cog"></i>
                                        Reset Password
                                    </a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"> <i
                                        class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                    </li>
                </ul>
            </nav>
            <!-- ---------------------------------------HEADER END------------------------------------------------- -->
            <!-- ---------------------------------------SIDE-NAVBAR START------------------------------------------ -->

            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html"> <img alt="image" src="{{ asset('assets/img/electrical.jpg') }}"
                                style="width: 70px; height:100%; margin:10px auto;" class="header-logo" /> <span
                                class="logo-name"></span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">

                        <li class="dropdown @yield('dashboard', '') @yield('alerts_', '')">
                            <a href="/admin/home" class="nav-link"><i
                                    data-feather="monitor"></i><span>Dashboard</span></a>
                        </li>



                        {{-- start sales --}}
                        <li class="menu-header">SALES</li>
                        <li class="dropdown @yield('All_pro', '')  @yield('product_in', '') @yield('product_return', '') @yield('SalesReturnDetails', '')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="briefcase"></i><span>Products</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('product.index', session('Access')))
                                    <li class="@yield('All_pro', '')"><a class="nav-link"
                                            href="{{ route('product.index') }}">All Products</a></li>
                                @endif
                                @if (in_array('product.indexProductIn', session('Access')))
                                    <li class="@yield('product_in', '')"><a class="nav-link"
                                            href="{{ route('product.indexProductIn') }}">Product In</a></li>
                                @endif
                                @if (in_array('product.indexProductReturn', session('Access')))
                                    <li class="@yield('product_return', '')"><a class="nav-link"
                                            href="{{ route('product.indexProductReturn') }}">Product Return</a>
                                    </li>
                                @endif
                                {{-- @if (in_array('product.indexProductReturn', session('Access')))
                                <li class="@yield('SalesReturnDetails', '')"><a class="nav-link"
                                        href="{{ route('Accounts.ShowReturnDetails') }}">Sales Return Details</a>
                                </li>
                            @endif --}}

                                {{-- @if (in_array('product.indexProductReturn', session('Access')))
                            <li class="@yield('ViewReturnDetails', '')"><a class="nav-link"
                                    href="{{ route('Accounts.ShowReturnDetailsOnly') }}">Return View Details Only</a>
                            </li>
                            @endif --}}
                            </ul>
                        </li>

                        <li
                            class="dropdown @yield('Billing', '')  @yield('salesReturn', '') @yield('Stock_Transfer', '') @yield('Foodcity_Sales_Return_Details', '')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="dollar-sign"></i><span>Sales</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('salesProduct.index', session('Access')))
                                    <li class="@yield('Billing', '')"><a class="nav-link"
                                            href="{{ route('salesProduct.index') }}">Billing</a></li>
                                @endif
                                @if (in_array('salesProduct.creditindex', session('Access')))
                                    <li class="@yield('creditBilling', '')"><a class="nav-link"
                                            href="{{ route('salesProduct.creditindex') }}">Credit Billing</a></li>
                                @endif
                                @if (in_array('salesReturn.index', session('Access')))
                                    <li class="@yield('salesReturn', '')"><a class="nav-link"
                                            href="{{ route('salesReturn.index') }}">Sales Return</a></li>
                                @endif
                                <li class="@yield('Stock_Transfer', '')"><a class="nav-link"
                                        href="{{ route('sales.stocktransferview') }}">Sales Stock Transfer</a></li>

                                <li class="@yield('Foodcity_Sales_Return_Details', '')"><a class="nav-link"
                                        href="{{ route('sales-return-details.index') }}">Sales Return Details</a></li>
                            </ul>
                        </li>
                        <li class="dropdown @yield('customer', '')  @yield('Credit List', '')  @yield('Credit Payment', '') ">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="users"></i><span>Credit</span></a>
                            <ul class="dropdown-menu">
                                <li class="@yield('customer', '')"><a class="nav-link"
                                        href="{{ route('customer.index') }}">
                                        <span>Credit Customers</span></a></li>
                                <li class="@yield('Credit List', '')"><a class="nav-link"
                                        href="{{ route('credit_list.credit_list_view') }}">Credit List</a></li>
                                <li class="@yield('Credit Payment', '')"><a class="nav-link"
                                        href="{{ route('credit_payment.index') }}">Credit Payment</a></li>
                            </ul>
                        </li>
                        <li class="dropdown @yield('CommisionCustomer', '')  @yield('Add Commision', '') ">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="users"></i><span>Commision Customer</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('salesProduct.index', session('Access')))
                                    <li class="@yield('CommisionCustomer', '')"><a class="nav-link"
                                            href="{{ route('commisioncustomer.index') }}">Commision Customer</a></li>
                                @endif
                                @if (in_array('salesReturn.index', session('Access')))
                                    <li class="@yield('Add Commision', '')"><a class="nav-link"
                                            href="{{ route('addcommision.addCommision') }}">Add Commision</a></li>
                                @endif
                            </ul>
                        </li>
                        <li class="dropdown @yield('', '') @yield('', '')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="shuffle"></i><span>Transfer</span></a>
                            <ul class="dropdown-menu">
                                @if (in_array('inventory.newProductStock', session('Access')))
                                    <li class="@yield('', '')"><a class="nav-link"
                                            href="{{ route('sales.sales_stock', ['type' => 'adjustment']) }}">Stock
                                            Adjustment</a></li>
                                @endif
                            </ul>
                        </li>
                        <li class="@yield('Advance Payment', '')"><a class="nav-link"
                                href="{{ route('advance_payment.index') }}">Advance Payments</a></li>

                        {{-- end sales --}}

                        @if (in_array('inventory.productTypeShowAll', session('Access')) ||
                                in_array('inventory.productCatShow', session('Access')) ||
                                in_array('inventory.productSubCatShow', session('Access')) ||
                                in_array('inventory.brandShow', session('Access')) ||
                                in_array('inventory.productShow', session('Access')))

                            {{-- start inventory --}}
                            <li class="menu-header">Inventory</li>

                            <li
                                class="dropdown @yield('qty_alert', '')  @yield('product_type', '') @yield('product_category', '') @yield('product_subcategory', '') @yield('product_brand', '') @yield('product', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="briefcase"></i><span>Product</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('inventory.productShow', session('Access')))
                                        <li class="@yield('product', '')"><a class="nav-link" href="/productShow">New
                                                Product</a></li>
                                    @endif
                                    @if (in_array('inventory.productTypeShowAll', session('Access')))
                                        <li class="@yield('product_type', '')"><a class="nav-link"
                                                href="/product-type-show-all">Product Type</a></li>
                                    @endif
                                    @if (in_array('inventory.productCatShow', session('Access')))
                                        <li class="@yield('product_category', '')"><a class="nav-link"
                                                href="/productCatShow">Product
                                                Category</a></li>
                                    @endif
                                    @if (in_array('inventory.productSubCatShow', session('Access')))
                                        <li class="@yield('product_subcategory', '')"><a class="nav-link"
                                                href="/productSubCatShow">Product Subcategory</a></li>
                                    @endif
                                    @if (in_array('inventory.brandShow', session('Access')))
                                        <li class="@yield('product_brand', '')"><a class="nav-link" href="/brandShow">Product
                                                Brand</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (in_array('inventory.sellerTypeShowAll', session('Access')) ||
                                in_array('inventory.sellerShowAll', session('Access')))
                            <li class="dropdown @yield('seller_type', '') @yield('seller', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="command"></i><span>Supplier</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('inventory.sellerTypeShowAll', session('Access')))
                                        <li class="@yield('seller_type', '')"><a class="nav-link"
                                                href="/seller-type-show-all">Supplier Type</a></li>
                                    @endif
                                    @if (in_array('inventory.sellerShowAll', session('Access')))
                                        <li class="@yield('seller', '')"><a class="nav-link"
                                                href="/seller-show-all">Supplier</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (in_array('inventory.creditPayments', session('Access')) ||
                                in_array('inventory.PurchaseRequestShow', session('Access')) ||
                                in_array('inventory.creditPaymentShow', session('Access')) ||
                                in_array('inventory.newPurchaseOrderShow', session('Access')) ||
                                in_array('inventory.purchaseOrderShowAll', session('Access')) ||
                                in_array('inventory.purchasedItemShowAll', session('Access')) ||
                                in_array('inventory.permanentAssetsShowAll', session('Access')) ||
                                in_array('inventory.assetStatusTypeShowAll', session('Access')))
                            <li
                                class="dropdown @yield('purchaseRequest', '') @yield('credit_payments', '') @yield('purchaseOrderCredit', '') @yield('good_receive', '') @yield('purchaseOrderNew', '') @yield('purchase_order', '') @yield('perchased_item', '') @yield('permanent_assets', '') @yield('assets_status_type', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="pocket"></i><span>Purchase</span></a>
                                <ul class="dropdown-menu">

                                    @if (in_array('inventory.purchaseOrderShowAll', session('Access')))
                                        <li class="@yield('good_receive', '')"><a class="nav-link"
                                                href="/purchase-order-show-all">Good Receive</a></li>
                                    @endif

                                    @if (in_array('inventory.purchasedItemShowAll', session('Access')))
                                        <li class="@yield('perchased_item', '')"><a class="nav-link"
                                                href="/purchased-item-show-all">GR Item</a></li>
                                    @endif

                                    @if (in_array('inventory.creditPaymentShow', session('Access')))
                                        <li class="@yield('purchaseOrderCredit', '')"><a class="nav-link"
                                                href="/credit-payment-show">GR Credit</a></li>
                                    @endif

                                    @if (in_array('inventory.creditPayments', session('Access')))
                                        <li class="@yield('credit_payments', '')"><a class="nav-link"
                                                href="/credit-payments">Credit Payment</a></li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        @if (in_array('inventory.outdoorReturnShowAll', session('Access')) ||
                                in_array('inventory.indoorReturnShowAll', session('Access')) ||
                                in_array('inventory.reasonShowAll', session('Access')))
                            <li
                                class="dropdown @yield('outdoor_return', '')  @yield('company_return', '') @yield('indoor_return', '') @yield('return_reason', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="corner-up-left"></i><span>Returns</span></a>
                                <ul class="dropdown-menu">
                                    {{-- @if (in_array('inventory.outdoorReturnShowAll', session('Access')))
                                        <li class="@yield('outdoor_return', '')"><a class="nav-link"
                                                href="/outdoor-return-show-all">Outdoor Return</a></li>
                                    @endif --}}
                                    <li class="@yield('company_return', '')"><a class="nav-link" href="/company-return">Company
                                            Return</a></li>

                                    @if (in_array('inventory.reasonShowAll', session('Access')))
                                        <li class="@yield('return_reason', '')"><a class="nav-link"
                                                href="/reason-show-all">Return Reasons</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif


                        {{-- end inventry --}}

                        {{-- start hr --}}
                        <li class="menu-header">Hr</li>
                        {{-- employee --}}
                        @if (in_array('employee.index', session('Access')) ||
                                in_array('employeedoc.index', session('Access')) ||
                                in_array('employeenote.index', session('Access')) ||
                                in_array('leavebyemployee.index', session('Access')) ||
                                in_array('hr.empBasicSalary.index', session('Access')) ||
                                in_array('hr.empSalaryPart.index', session('Access')) ||
                                in_array('mechineByEmp.index', session('Access')))
                            <li
                                class="dropdown @yield('employee', '') @yield('employee doc', '') @yield('salary_payable', '') 
                                 @yield('emp_basic_salary', '') @yield('employee_salary_part', '') @yield('allowance_type', '')
                                 @yield('employee_type', '') @yield('work_type', '') ">
                                <a href="#" class="menu-toggle nav-link has-dropdown">
                                    <i class="fas fa-users"></i><span>Employee</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('employee.index', session('Access')))
                                        <li class="dropdown @yield('employee', '')">
                                            <a href="{{ route('employee.index') }}"
                                                class="nav-link"><span>Employees</span></a>
                                        </li>
                                    @endif
                                    @if (in_array('employeedoc.index', session('Access')))
                                        <li class="dropdown @yield('employee doc', '')">
                                            <a href="{{ route('employeedoc.index') }}"
                                                class="nav-link"><span>Employee Documents</span></a>
                                        </li>
                                    @endif
                                    <li class="dropdown @yield('salary_payable', '')">
                                        <a href="{{ route('hr.salaryPayable.index') }}" class="nav-link">
                                            <span>Salary Payable</span></a>
                                    </li>
                                    @if (in_array('hr.empBasicSalary.index', session('Access')))
                                        <li class="dropdown @yield('emp_basic_salary', '')">
                                            <a href="{{ route('hr.empBasicSalary.index') }}" class="nav-link">
                                                <span>Basic Salary</span></a>
                                        </li>
                                    @endif
                                    @if (in_array('hr.empSalaryPart.index', session('Access')))
                                        <li class="dropdown @yield('employee_salary_part', '')">
                                            <a href="{{ route('hr.empSalaryPart.index') }}">Allowance</a>
                                        </li>
                                    @endif
                                    @if (in_array('hr.allowanceType.index', session('Access')))
                                        <li class="dropdown @yield('allowance_type', '')">
                                            <a href="{{ route('hr.allowanceType.index') }}">Allowance Type</a>
                                        </li>
                                    @endif
                                    @if (in_array('employeetype.index', session('Access')))
                                        <li class="dropdown @yield('employee_type', '')">
                                            <a href="{{ route('employeetype.index') }}"
                                                class="nav-link"><span>Employee Type</span></a>
                                        </li>
                                    @endif
                                    @if (in_array('worktype.index', session('Access')))
                                        <li class="dropdown @yield('work_type', '')">
                                            <a href="{{ route('worktype.index') }}" class="nav-link"><span>Work
                                                    Type</span></a>
                                        </li>
                                    @endif
                                    <li class="dropdown @yield('advance_payment', '')">
                                        <a href="{{ route('hr.advancePayment.index') }}"
                                            class="nav-link"><span>Advance
                                                Payment</span></a>
                                    </li>
                                </ul>
                        @endif

                        {{-- end hr --}}

                        {{-- start Accounts --}}
                        <li class="menu-header">Accounts</li>
                        @if (in_array('income.index', session('Access')) || in_array('otherincome.otherIncomeShow', session('Access')))
                            <li class="dropdown @yield('all_income', '') @yield('other_income', '') @yield('other_income_categeory', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="trending-up"></i><span>Income</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('income.index', session('Access')))
                                        <li class="@yield('all_income', '')"><a href="{{ route('income.index') }}">All
                                                Income</a></li>
                                    @endif
                                    <li class="@yield('other_income_categeory', '')"><a
                                            href="{{ route('otherincome.otherIncomeCategeoryShow') }}">Categeory</a>
                                    </li>
                                    @if (in_array('otherincome.otherIncomeShow', session('Access')))
                                        <li class="@yield('other_income', '')"><a
                                                href="{{ route('otherincome.otherIncomeShow') }}">Other Income</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (in_array('expense.index', session('Access')) ||
                                in_array('otherExpense.otherExpenseType', session('Access')) ||
                                in_array('otherExpense.otherExpenseShow', session('Access')) ||
                                in_array('service.serviceTypeShow', session('Access')) ||
                                in_array('account.serviceProviderShow', session('Access')) ||
                                in_array('account.serviceChargeShow', session('Access')) ||
                                in_array('otherExpense.otherExpenseCategory', session('Access')) ||
                                in_array('account.newServiceExpense', session('Access')))
                            <li
                                class="dropdown @yield('all_expense', '') @yield('other_exp_cat', '') @yield('other_exp_type', '') @yield('other_expense', '') @yield('service_types', '') @yield('service_provider', '') @yield('service_charges', '') @yield('service_expenses', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="trending-down"></i><span>Expense</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('expense.index', session('Access')))
                                        <li class="@yield('all_expense', '')"><a href="{{ route('expense.index') }}">All
                                                Expense</a></li>
                                    @endif

                                    @if (in_array('otherExpense.otherExpenseCategory', session('Access')))
                                        <li class="@yield('other_exp_cat', '')"><a
                                                href="{{ route('otherExpense.otherExpenseCategory') }}">Other Expenses
                                                Category
                                            </a></li>
                                    @endif

                                    @if (in_array('otherExpense.otherExpenseType', session('Access')))
                                        <li class="@yield('other_exp_type', '')"><a
                                                href="{{ route('otherExpense.otherExpenseType') }}">Other Expenses
                                                Subcategory
                                            </a></li>
                                    @endif
                                    @if (in_array('otherExpense.otherExpenseShow', session('Access')))
                                        <li class="@yield('other_expense', '')"><a
                                                href="{{ route('otherExpense.otherExpenseShow') }}">Other Expenses</a>
                                        </li>
                                    @endif
                                    @if (in_array('service.serviceTypeShow', session('Access')))
                                        <li class="@yield('service_types', '')"><a
                                                href="{{ route('service.serviceTypeShow') }}">Service Types</a></li>
                                    @endif
                                    @if (in_array('account.serviceProviderShow', session('Access')))
                                        <li class="@yield('service_provider', '')"><a
                                                href="{{ route('account.serviceProviderShow') }}">Service Provider</a>
                                        </li>
                                    @endif
                                    @if (in_array('account.serviceChargeShow', session('Access')))
                                        <li class="@yield('service_charges', '')"><a
                                                href="{{ route('account.serviceChargeShow') }}">Service Charge</a>
                                        </li>
                                    @endif

                                    @if (in_array('account.newServiceExpense', session('Access')))
                                        <li class="@yield('service_expenses', '')"><a
                                                href="{{ route('account.newServiceExpense') }}">Service Expenses</a>
                                        </li>
                                    @endif



                                </ul>
                            </li>
                        @endif


                        @if (in_array('account.bankShow', session('Access')) ||
                                in_array('account.bankChargesTypesShow', session('Access')) ||
                                in_array('bank.bankChargesShow', session('Access')) ||
                                in_array('account.bankIntrestShow', session('Access')) ||
                                in_array('account.bankTransectionShow', session('Access')) ||
                                in_array('account.bankBalanceShow', session('Access')) ||
                                in_array('account.chequePaymentPending', session('Access')))
                            <li
                                class="dropdown  @yield('bank_details', '') @yield('bank_charges_types', '') @yield('bank_charges', '') @yield('bank_intrests', '') @yield('bank_transaction', '') @yield('cheque_pendings', '') @yield('cheque_payments', '') @yield('bank_balance', '') ">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="grid"></i><span>Bank</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('account.bankShow', session('Access')))
                                        <li class="@yield('bank_details', '')"><a
                                                href="{{ route('account.bankShow') }}">Bank
                                                Details</a></li>
                                    @endif
                                    @if (in_array('account.bankChargesTypesShow', session('Access')))
                                        <li class="@yield('bank_charges_types', '')"><a
                                                href="{{ route('account.bankChargesTypesShow') }}">Bank Charge
                                                Types</a></li>
                                    @endif
                                    @if (in_array('bank.bankChargesShow', session('Access')))
                                        <li class="@yield('bank_charges', '')"><a
                                                href="{{ route('bank.bankChargesShow') }}">Bank Charges</a></li>
                                    @endif
                                    @if (in_array('account.bankIntrestShow', session('Access')))
                                        <li class="@yield('bank_intrests', '')"><a
                                                href="{{ route('account.bankIntrestShow') }}">Bank Interest</a></li>
                                    @endif
                                    @if (in_array('account.bankTransectionShow', session('Access')))
                                        <li class="@yield('bank_transaction', '')"><a
                                                href="{{ route('account.bankTransectionShow') }}">Bank Transaction</a>
                                        </li>
                                    @endif
                                    @if (in_array('account.chequePaymentPending', session('Access')))
                                        <li class="@yield('cheque_pendings', '')"><a
                                                href="{{ route('account.chequePaymentPending') }}">Cheque Pending</a>
                                        </li>
                                    @endif
                                    @if (in_array('account.chequePaymentShow', session('Access')))
                                        <li class="@yield('cheque_payments', '')"><a
                                                href="{{ route('account.chequePaymentShow') }}">Cheque Payments</a>
                                        </li>
                                    @endif
                                    @if (in_array('account.bankBalanceShow', session('Access')))
                                        <li class="@yield('bank_balance', '')"><a
                                                href="{{ route('account.bankBalanceShow') }}">Bank Balance</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        {{-- <li class="menu-header">Main Account</li> --}}
                        {{-- <li class="dropdown @yield('profitloss', '') @yield('ledgers', '') @yield('balanceSheet', '')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="user-check"></i><span>Accounts</span></a>

                            <ul class="dropdown-menu">
                                <li class="@yield('profitloss', '')"> <a class="nav-link dept" data-toggle="modal"
                                        data-target="#profitlossModal"><span>Profit or Loss</span></a></li>

                                <li class="@yield('balanceSheet', '')"><a class="nav-link" data-toggle="modal"
                                        data-target="#balanceSheetModal"><span>Balance Sheet</span></a></li>

                                <li class="@yield('ledgers', '')"><a class="nav-link" data-toggle="modal"
                                        data-target="#ledgerModal"><span>Ledgers</span></a></li>

                            </ul>
                        </li> --}}
                        <li
                            class="dropdown @yield('Add Showcategory', '')
                            @yield('Add Subcategory', '')  @yield('Accounts_details', '')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="user-check"></i><span>Categeory</span></a>
                            <ul class="dropdown-menu">
                                <li class="@yield('Add Showcategory', '')"><a class="nav-link"
                                        href="{{ route('Accounts.Showcategory') }}">Add Categeory</a></li>
                                <li class="@yield('Add Subcategory', '')"><a class="nav-link"
                                        href="{{ route('Accounts.Showsubcategory') }}">Add Sub Categeory</a></li>
                                <li class="@yield('Accounts_details', '')"><a class="nav-link"
                                        href="{{ route('Accounts.index') }}">Accounts Details</a></li>
                            </ul>
                        </li>

                        <li
                            class="dropdown @yield('Owners List', '')
                            @yield('Owners Transaction', '')  @yield('Owners Transaction Payment', '')">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="user-check"></i><span>Owners Transaction</span></a>
                            <ul class="dropdown-menu">
                                <li class="@yield('Owners List', '')"><a class="nav-link"
                                        href="{{ route('owner.index') }}">Owners List</a></li>
                                <li class="@yield('Owners Transaction', '')"><a class="nav-link"
                                        href="{{ route('owner.ownertransactionindex') }}">Owners Transaction</a></li>
                                <li class="@yield('Owners Transaction Payment', '')"><a class="nav-link"
                                        href="{{ route('owner.ownertransactionpaymentindex') }}">Transaction
                                        Payment</a></li>
                            </ul>
                        </li>

                        @if (in_array('user.home', session('Access')))
                            <li class="dropdown @yield('Closed Blance', '')">
                                <a class="nav-link" href="{{ route('Accounts.ShowclosedBalance') }}"><i
                                        data-feather="layers"></i><span>Closed Blance</span></a>
                            </li>
                        @endif

                        <li class="dropdown @yield('mukunthan', '')">
                            <a class="nav-link" href="{{ route('mukunthan.index') }}"></i><span>Mukunthan Anna
                                    Collection</span></a>
                        </li>

                        {{-- end Accounts --}}


                        {{-- Start Reports --}}
                        <li class="menu-header">Reports</li>
                        @if (in_array('sales.report', session('Access')))
                            <li
                                class="dropdown @yield('sales-report', '') @yield('creditReport', '') @yield('productReport', '') @yield('salecancelReport', '') 
                                @yield('returnReport', '') @yield('stockReport', '') @yield('sales-temp-report', '')">
                                <a class="menu-toggle nav-link has-dropdown" href="#"><i
                                        data-feather="pie-chart"></i><span>Sales Reports</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('sales.report', session('Access')))
                                        <li class="@yield('sales-report')"><a href="{{ route('sales.report') }}">Sales
                                                Report</a></li>
                                    @endif
                                    <li class="@yield('sales-temp-report')"><a
                                            href="{{ route('sales.temp-report') }}">Tempory Sales
                                        </a></li>
                                    <li class="@yield('creditReport')"><a href="/sales/credit-report">Credit Report</a>
                                    </li>
                                    <li class="@yield('productReport')"><a href="/sales-product-reports">Sales Product
                                            Report</a></li>

                                    @if (in_array('salesCancel.report', session('Access')))
                                        <li class="@yield('salecancelReport')"><a
                                                href="{{ route('salesCancel.report') }}">Sales Cancel Report</a></li>
                                    @endif
                                    @if (in_array('return.report', session('Access')))
                                        <li class="@yield('returnReport')"><a
                                                href="{{ route('return.report') }}">Returned Products</a></li>
                                    @endif

                                    @if (in_array('stock.report', session('Access')))
                                        <li class="@yield('stockReport')"><a href="{{ route('stock.report') }}">Stock
                                                Report</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (in_array('inventory.purchaseOrderRequestRepMonth', session('Access')) ||
                                in_array('inventory.stockByDate', session('Access')) ||
                                in_array('inventory.stockReport', session('Access')) ||
                                in_array('inventory.purchaseOrderReport', session('Access')) ||
                                in_array('inventory.purchaseItemReport', session('Access')) ||
                                in_array('inventory.indoorReturnReport', session('Access')) ||
                                in_array('inventory.outDoorReturnReport', session('Access')) ||
                                in_array('inventory.indoorTransferReport', session('Access')) ||
                                in_array('inventory.equipmentTransferReport', session('Access')) ||
                                // in_array('inventory.permanentTransferReport', session('Access')) ||
                                in_array('inventory.permanantAssetsReportMonth', session('Access')) ||
                                in_array('inventory.experyDateReport', session('Access')))
                            <li
                                class="dropdown @yield('purchase_order_request_report', '') @yield('stockByDate', '') @yield('ProductMoving', '') @yield('newProductPrice', '') @yield('ProductPrice', '') @yield('purchase_order_report', '') @yield('purchase_item_report', '') @yield('indoor_return_report', '') @yield('outdoor_return_report', '') @yield('indoor_transfer_report', '') @yield('equipment_transfer_report', '') @yield('permanent_transfer_report', '') @yield('permanent_assets_report', '') @yield('stock', '') @yield('expery_date_report', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="book-open"></i><span>Inventry Reports</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('inventory.purchaseOrderRequestRepMonth', session('Access')))
                                        <li class="@yield('purchase_order_request_report', '')"><a class="nav-link"
                                                href="/purchaseOrderRequestRepMonth">PR Report</a></li>
                                    @endif
                                    @if (in_array('inventory.purchaseOrderReport', session('Access')))
                                        <li class="@yield('purchase_order_report', '')"><a class="nav-link"
                                                href="/purchase-order-report">GR Report</a></li>
                                    @endif
                                    @if (in_array('inventory.purchaseItemReport', session('Access')))
                                        <li class="@yield('purchase_item_report', '')"><a class="nav-link"
                                                href="/purchased-item-report">Purchase Item</a></li>
                                    @endif
                                    @if (in_array('inventory.indoorReturnReport', session('Access')))
                                        <li class="@yield('indoor_return_report', '')"><a class="nav-link"
                                                href="/indoor-return-report">Indoor Return</a></li>
                                    @endif
                                    @if (in_array('inventory.outDoorReturnReport', session('Access')))
                                        <li class="@yield('outdoor_return_report', '')"><a class="nav-link"
                                                href="/out-door-return-report">Outdoor Return</a></li>
                                    @endif
                                    @if (in_array('inventory.indoorTransferReport', session('Access')))
                                        <li class="@yield('indoor_transfer_report', '')"><a class="nav-link"
                                                href="/indoor-transfer-report">Indoor Transfer</a></li>
                                    @endif
                                    @if (in_array('inventory.equipmentTransferReport', session('Access')))
                                        <li class="@yield('equipment_transfer_report', '')"><a class="nav-link"
                                                href="/equipment-transfer-report">Equipment Transfer</a></li>
                                    @endif


                                    @if (in_array('inventory.permanantAssetsReportMonth', session('Access')))
                                        <li class="@yield('permanent_assets_report', '')"><a class="nav-link"
                                                href="/permanantAssetsReportMonth">Permanent Assets</a></li>
                                    @endif

                                    {{-- @if (in_array('inventory.permanentTransferReport', session('Access')))
                                        <li class="@yield('permanent_transfer_report', '')"><a class="nav-link"
                                                href="/permanent-asset-transfer-report">Permanent Asset Transfer</a>
                                        </li>
                                    @endif --}}

                                    @if (in_array('inventory.experyDateReport', session('Access')))
                                        <li class="@yield('expery_date_report', '')"><a class="nav-link"
                                                href="/expery-date-report">Expiry Date</a></li>
                                    @endif
                                    {{-- @if (in_array('inventory.stockReport', session('Access')))
                                        <li class="@yield('stock', '')"><a class="nav-link"
                                                href="/get-product-stock/report">Stock</a></li>
                                    @endif --}}

                                    @if (in_array('inventory.productPrice', session('Access')))
                                        <li class="@yield('newProductPrice', '')"><a class="nav-link"
                                                href="/new-product-price">Product Price</a></li>
                                    @endif
                                    @if (in_array('inventory.productMovingCount', session('Access')))
                                        <li class="@yield('ProductMoving', '')"><a class="nav-link"
                                                href="/product-moving-count">Product Moving</a></li>
                                    @endif
                                    @if (in_array('inventory.stockByDate', session('Access')))
                                        <li class="@yield('stockByDate', '')"><a class="nav-link"
                                                href="/stock-by-date">Stock By
                                                Date </a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (in_array('hr.report.attendance-report', session('Access')) ||
                                in_array('hr.report.emp-report', session('Access')) ||
                                in_array('hr.report.leaveReport', session('Access')) ||
                                in_array('hr.report.epfEtfReport', session('Access')) ||
                                in_array('hr.report.advancePaymentReport', session('Access')) ||
                                in_array('hr.report.overTimeReport', session('Access')))
                            <li
                                class="dropdown  @yield('attendance-report', '') @yield('attendance-report-month', '') @yield('employee_report', '')  @yield('Leave_report', '') @yield('epf_etf_report', '') @yield('advance_payment_report', '') @yield('over_time_report', '') @yield('salary payable report', '') @yield('basic salary report', '')">
                                <a href="#" class="menu-toggle nav-link has-dropdown">
                                    <i class="fas fa-hdd"></i><span>Hr Reports</span></a>
                                <ul class="dropdown-menu">

                                    <li class="dropdown @yield('salary payable report', '')">
                                        <a href="{{ route('hr.salaryPayableReport') }}">Salary Payable Report</a>
                                    </li>
                                    <li class="dropdown @yield('basic salary report', '')">
                                        <a href="{{ route('hr.basicSalaryReport') }}">Basic Salary Report</a>
                                    </li>
                                    @if (in_array('hr.report.emp-report', session('Access')))
                                        <li class="dropdown @yield('employee_report', '')">
                                            <a href="{{ route('hr.report.emp-report') }}">Employee Report</a>
                                        </li>
                                    @endif
                                    @if (in_array('hr.report.epfEtfReport', session('Access')))
                                        <li class="dropdown @yield('epf_etf_report', '')">
                                            <a href="{{ route('hr.report.epfEtfReport') }}">EPF&ETF Report</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        <li
                            class="dropdown  @yield('monthly_report', '')  @yield('Accounts Final Trial Month', '') @yield('Accounts Details', '') @yield('Accounts Details', '') 
                             @yield('Accounts profitloss', '')  @yield('Accounts Balance Sheet', '') @yield('Daily Cash Book', '')  @yield('Accounts Final Trial Balance', '')
                             @yield('DailyAdjustment', '') ">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i class="fas fa-hdd"></i><span>Accounts Reports</span></a>
                            <ul class="dropdown-menu">

                                @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('monthly_report', '')">
                                        <a class="nav-link" href="{{ route('account.monthlyReport') }}"><i
                                                data-feather="layers"></i><span>Accounts Report</span></a>
                                    </li>
                                @endif

                                @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('Accounts Details', '')">
                                        <a class="nav-link" href="{{ route('account.DetailsReport') }}"><i
                                                data-feather="layers"></i><span>Accounts Details Report</span></a>
                                    </li>
                                @endif

                                @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('Accounts profitloss', '')">
                                        <a class="nav-link" href="{{ route('account.profitlossReport') }}"><i
                                                data-feather="layers"></i><span>Profit Losss Report</span></a>
                                    </li>
                                @endif
                                @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('Accounts Balance Sheet', '')">
                                        <a class="nav-link" href="{{ route('account.balancesheetReport') }}"><i
                                                data-feather="layers"></i><span>Balance Sheet Report</span></a>
                                    </li>
                                @endif
                                {{-- end Reports --}}


                                @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('Daily Cash Book', '')">
                                        <a class="nav-link" href="{{ route('Accounts.ShowDailyCash') }}"><i
                                                data-feather="layers"></i><span>Daily Cash Book</span></a>
                                    </li>
                                @endif

                                @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('DailyAdjustment', '')">
                                        <a class="nav-link" href="{{ route('Accounts.ShowDailyCashReport') }}"><i
                                                data-feather="layers"></i><span>Daily Adjustment Report</span></a>
                                    </li>
                                @endif

                                {{-- @if (in_array('user.home', session('Access')))
                                    <li class="dropdown @yield('Accounts Trial Balance', '')">
                                        <a class="nav-link" href="{{ route('Accounts.ShowTrialBalanceReport') }}"><i
                                                data-feather="layers"></i><span>Trial Balance Report</span></a>
                                    </li>
                                @endif --}}

                                <li class="dropdown @yield('Accounts Final Trial Balance', '')">
                                    <a class="nav-link" href="{{ route('Accounts.FinalTrialBalanceReport') }}"><i
                                            data-feather="layers"></i><span>Final Trial Balance</span></a>
                                </li>

                                <li class="dropdown @yield('Accounts Final Trial Month', '')">
                                    <a class="nav-link" href="{{ route('Accounts.FinalTrialBalanceMonth') }}"><i
                                            data-feather="layers"></i><span>Final Trial Month</span></a>
                                </li>
                            </ul>
                        </li>

                        @if (in_array('user.index', session('Access')) ||
                                in_array('role.index', session('Access')) ||
                                in_array('access_model.index', session('Access')))
                            <li class="dropdown">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="user-check"></i><span>Auth</span></a>
                                <ul class="dropdown-menu">
                                    @if (in_array('user.index', session('Access')))
                                        <li><a href="{{ route('user.index') }}">User</a></li>
                                    @endif

                                    @if (in_array('role.index', session('Access')))
                                        <li><a href="{{ route('role.index') }}">User Role</a></li>
                                    @endif

                                    @if (in_array('access_model.index', session('Access')))
                                        <li><a href="{{ route('access_model.index') }}">Access Model</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @if (\Session::has('success'))
                    <div class="alert alert-success session-msg"
                        style="width: 50%; margin:0 auto 15px auto; text-align:center;">
                        <p>{{ \Session::get('success') }}</p>
                    </div>
                    <script>
                        $(function() {
                            setTimeout(function() {
                                $('.session-msg').slideUp();
                            }, 5000);
                        });
                    </script>
                @endif
                @if (\Session::has('error'))
                    <div class="alert alert-danger session-msg"
                        style="width: 50%; margin:0 auto 15px auto; text-align:center;">
                        <p>{{ \Session::get('error') }}</p>
                    </div>
                    <script>
                        $(function() {
                            setTimeout(function() {
                                $('.session-msg').slideUp();
                            }, 5000);
                        });
                    </script>
                @endif
                @yield('content')

            </div>

            @yield('model')
            @yield('modal')

            <div class="modal fade" id="profitlossModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formModal">Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        <div class="modal-body">

                            <div class="input-group">
                                <input type="text" class="form-control profitloss-Search"
                                    placeholder="Search Department..." onkeyup="profitlossSearch()">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary" onclick="profitlossSearch()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div><br>

                            <div class="table-responsive" style="width: 100%">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th style="width: 75%">Department</th>
                                            <th style="width: 25%; text-align: center;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="profitloss_tbody">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="balanceSheetModal" tabindex="-1" role="dialog"
                aria-labelledby="formModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formModal">Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        <div class="modal-body">

                            <div class="input-group">
                                <input type="text" class="form-control balanceSheet-Search"
                                    placeholder="Search Department..." onkeyup="balanceSheetSearch()">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary" onclick="balanceSheetSearch()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div><br>

                            <div class="table-responsive" style="width: 100%">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th style="width: 75%">Department</th>
                                            <th style="width: 25%; text-align: center;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="balanceSheet_tbody">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ledgerModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formModal">Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        <div class="modal-body">

                            <div class="input-group">
                                <input type="text" class="form-control ledger-Search"
                                    placeholder="Search Department..." onkeyup="ledgerSheetSearch()">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary" onclick="ledgerSheetSearch()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div><br>

                            <div class="table-responsive" style="width: 100%">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th style="width: 75%">Department</th>
                                            <th style="width: 25%; text-align: center;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="ledger_tbody">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ledgerTypeModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formModal">Ledgers</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        <div class="modal-body">

                            <div class="table-responsive" style="width: 100%">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 75%">Ledger</th>
                                            <th style="width: 25%; text-align: center;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="ledger_Type_tbody">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <footer class="main-footer">
                <div class="footer-left">
                    <a href="https://codevita.lk/" target="blank">© JAFFNA ELETRICAL {{ date('Y') }}. TECHNICAL
                        SUPPORT BY
                        CODEVITA.</a></a>
                </div>
                <div class="footer-right">
                </div>
            </footer>
        </div>
    </div>





    <!-- General JS Scripts -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    {{-- data table --}}
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>

    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>

    {{-- accounts --}}
    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
    <!-- year picker -->
    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>

    @yield('script')

    <script>
        $('.yearpicker').yearpicker();

        $(function() {
            setTimeout(function() {
                $('.fade-message').slideUp();
            }, 1000);
        });

        $(document).ready(function() {
            profitlossSearch();
            balanceSheetSearch();
            ledgerSheetSearch();
        });



        function profitlossSearch() {
            let search = $(".profitloss-Search").val();
            $.ajax({
                type: "get",
                url: "/department-search",
                data: {
                    search
                },

                success: function(response) {

                    let profit_loss_html = "";

                    if (response.length > 0) {
                        $.each(response, function(indexInArray, valueOfElement) {
                            profit_loss_html += `<tr>
                                        <td style="display: none">#</td>
                                        <td>${valueOfElement.dept_name}</td>
                                        <td style="text-align: center"><a
                                                        href="/account-balance/profit_loss/${valueOfElement.acc_dept_id}"
                                                        class="btn btn-success" style="color: white">Select</button>
                                                </td>
                            </tr>`;

                        });

                    } else {
                        profit_loss_html += `<tr>
                                                <td colspan="2" align="center">No Match Records</td>
                                        </tr>`;
                    }

                    $("#profitloss_tbody").empty().append(profit_loss_html);

                },

            });
        }

        function balanceSheetSearch() {
            let search = $(".balanceSheet-Search").val();
            $.ajax({
                type: "get",
                url: "/department-search",
                data: {
                    search
                },

                success: function(response) {

                    let balanceSheet_html = "";

                    if (response.length > 0) {
                        $.each(response, function(indexInArray, valueOfElement) {
                            balanceSheet_html += `<tr>
                                        <td style="display: none">#</td>
                                        <td>${valueOfElement.dept_name}</td>
                                        <td style="text-align: center"><a
                                                        href="/account-balance/balance_sheet/${valueOfElement.acc_dept_id}"
                                                        class="btn btn-success" style="color: white">Select</button>
                                                </td>
                            </tr>`;

                        });

                    } else {
                        balanceSheet_html += `<tr>
                                                <td colspan="2" align="center">No Match Records</td>
                                        </tr>`;
                    }


                    $("#balanceSheet_tbody").empty().append(balanceSheet_html);


                },

            });
        }

        function ledgerSheetSearch() {
            let search = $(".ledger-Search").val();
            $.ajax({
                type: "get",
                url: "/department-search",
                data: {
                    search
                },

                success: function(response) {

                    let ledger_html = "";

                    if (response.length > 0) {
                        $.each(response, function(indexInArray, valueOfElement) {
                            ledger_html += `<tr>
                                        <td style="display: none">#</td>
                                        <td>${valueOfElement.dept_name}</td>
                                        <td style="text-align: center"><button class="btn btn-success ledger"
                                            dept_id="${valueOfElement.acc_dept_id}"
                                                        style="color: white">Select</button>
                                                </td>
                            </tr>`;

                        });

                    } else {
                        ledger_html += `<tr>
                                                <td colspan="2" align="center">No Match Records</td>
                                        </tr>`;
                    }

                    $("#ledger_tbody").empty().append(ledger_html);

                    $(".ledger").click(function(e) {
                        e.preventDefault();
                        $("#ledgerModal").modal('hide');

                        let dept_id = $(this).attr("dept_id");
                        let ledger_tbody_html = "";

                        switch (dept_id) {
                            // Ankadi
                            case "1":

                                ledger_tbody_html = `

                                <tr>
                                    <td>Sales</td>
                                    <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Sales Return</td>
                                    <td><a href="/account-ledger/${dept_id}/3" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Purchase</td>
                                    <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Purchase Return</td>
                                    <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Credit</td>
                                    <td><a href="/account-ledger/${dept_id}/4" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                               `;

                                break;
                                // front office
                            case "3":

                                ledger_tbody_html = `
                                <tr>
                                    <td>Sales - Ticket Sales</td>
                                    <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Sales Return - Ticket</td>
                                    <td><a href="/account-ledger/${dept_id}/3" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>`;

                                break;
                                // Restaurant
                            case "8":

                                ledger_tbody_html = `
                                    <tr>
                                        <td>Sales</td>
                                        <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>
                                        <tr>
                                        <td>Purchase</td>
                                        <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Return</td>
                                        <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>

                                    <tr>
                                        <td>Cash</td>
                                        <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>`;

                                break;


                                // Kurinchi  Account Department 09
                            case "9":

                                ledger_tbody_html = `
                                    <tr>
                                        <td>Sales</td>
                                        <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>
                                        <tr>
                                        <td>Purchase</td>
                                        <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Return</td>
                                        <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>

                                    <tr>
                                        <td>Cash</td>
                                        <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>`;

                                break;


                                // Pizza Account Department 11

                            case "11":

                                ledger_tbody_html = `
                                    <tr>
                                        <td>Sales</td>
                                        <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>
                                        <tr>
                                        <td>Purchase</td>
                                        <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Return</td>
                                        <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>

                                    <tr>
                                        <td>Cash</td>
                                        <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                    </tr>`;

                                break;

                            default:
                                break;
                        }

                        $("#ledger_Type_tbody").empty().append(ledger_tbody_html);
                        $("#ledgerTypeModal").modal('show');

                    });




                },

            });
        }


        $(".ledger").click(function(e) {
            e.preventDefault();
            $("#ledgerModal").modal('hide');
            let dept_id = $(this).attr("dept_id");
            let ledger_tbody_html = "";

            switch (dept_id) {
                // Ankadi
                case "1":

                    ledger_tbody_html = `
                                <tr>
                                    <td>Sales</td>
                                    <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Sales Return</td>
                                    <td><a href="/account-ledger/${dept_id}/3" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Purchase</td>
                                    <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Purchase Return</td>
                                    <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Credit</td>
                                    <td><a href="/account-ledger/${dept_id}/4" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                               `;

                    break;
                    // front office
                case "3":

                    ledger_tbody_html = `
                                <tr>
                                    <td>Sales - Ticket Sales</td>
                                    <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Sales Return - Ticket</td>
                                    <td><a href="/account-ledger/${dept_id}/3" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>`;

                    break;
                    // Restaurant
                case "8":

                    ledger_tbody_html = `
                            <tr>
                                <td>Sales</td>
                                <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>

                            <tr>
                                <td>Purchase</td>
                                <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                            <tr>
                                <td>Purchase Return</td>
                                <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                            <tr>
                                <td>Credit</td>
                                <td><a href="/account-ledger/${dept_id}/4" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                            <tr>
                                <td>Cash</td>
                                <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                        `;

                    break;


                    // Kurinchi  Account Department 09

                case "9":

                    ledger_tbody_html = `
                                <tr>
                                    <td>Sales</td>
                                    <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>

                                <tr>
                                    <td>Purchase</td>
                                    <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Purchase Return</td>
                                    <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Credit</td>
                                    <td><a href="/account-ledger/${dept_id}/4" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                                </tr>
                            `;

                    break;


                    // Pizza
                case "11":
                    ledger_tbody_html = `
                            <tr>
                                <td>Sales</td>
                                <td><a href="/account-ledger/${dept_id}/2" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>

                            <tr>
                                <td>Purchase</td>
                                <td><a href="/account-ledger/${dept_id}/5" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                            <tr>
                                <td>Purchase Return</td>
                                <td><a href="/account-ledger/${dept_id}/6" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                            <tr>
                                <td>Credit</td>
                                <td><a href="/account-ledger/${dept_id}/4" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                            <tr>
                                <td>Cash</td>
                                <td><a href="/account-ledger/${dept_id}/1" class="btn btn-success" style="color: white">Select</a></td>
                            </tr>
                        `;

                    break;

                default:
                    break;
            }


            $("#ledger_Type_tbody").empty().append(ledger_tbody_html);
            $("#ledgerTypeModal").modal('show');

        });
    </script>
    {{-- accounts --}}
</body>

</html>
