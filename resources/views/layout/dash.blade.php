<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Reecha Admin Dashboard</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ url('/assets/css/app.min.css') }}" />  
  {{-- <link rel="stylesheet" href="assets/css/app.min.css"> --}}
  <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
  <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ url('/assets/css/style.css') }}" />
  <link rel="stylesheet" href="{{ url('/assets/css/components.css') }}" />
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ url('/assets/css/custom.css') }}" />
  <link rel='shortcut icon' type='image/x-icon' href='{{ url("/assets/img/favicon.jpg")}}' />
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css">
  <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
</head>

<body>
  
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      {{-- <div class="navbar-bg"></div> --}}

      {{-- navbar strat --}}
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">          
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="/assets/img/user.png"
                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Hello Sarah Smith</div>
              <a href="profile.html" class="dropdown-item has-icon"> <i class="far
										fa-user"></i> Profile
              </a> <a href="timeline.html" class="dropdown-item has-icon"> <i class="fas fa-bolt"></i>
                Activities
              </a> <a href="#" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                Settings
              </a>
              <div class="dropdown-divider"></div>
              <a href="auth-login.html" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
      {{-- navbar end --}}

      {{-- admin side bar --}}
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="/assets/img/ReeCha.png" class="header-logo" style="width: 70px; height:100%; margin:10px auto;"/> 
                {{--<span class="logo-name"></span> --}}
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown @yield('dashboard', '')">
              <a href="/getDashDatas" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>

            <li class="dropdown  @yield('product_type', '') @yield('product_category', '') @yield('product_subcategory', '') @yield('product_brand', '') @yield('product', '')">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Product</span></a>
              <ul class="dropdown-menu">
                <li class="@yield('product_type', '')"><a class="nav-link" href="/product-type-show-all">Product Type</a></li>
                <li class="@yield('product_category', '')"><a class="nav-link" href="/productCatShow">Product Category</a></li>
                <li class="@yield('product_subcategory', '')"><a class="nav-link" href="/productSubCatShow">Product Subcategory</a></li>
                <li class="@yield('product_brand', '')"><a class="nav-link" href="/brandShow">Product Brand</a></li>
                <li class="@yield('product', '')"><a class="nav-link" href="/productShow">Product</a></li>
              </ul>
            </li>
            <li class="dropdown @yield('seller_type', '') @yield('seller', '')">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Seller</span></a>
              <ul class="dropdown-menu">
                <li class="@yield('seller_type', '')"><a class="nav-link" href="/seller-type-show-all">Seller Type</a></li>
                <li class="@yield('seller', '')"><a class="nav-link" href="/seller-show-all">Seller</a></li>
              </ul>
            </li>
            <li class="dropdown @yield('perchase_order_request','') @yield('purchase_order','') @yield('perchased_item','') @yield('permanent_assets','')">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Purchase</span></a>
              <ul class="dropdown-menu">
                <li class="@yield('perchase_order_request','')"><a class="nav-link" href="/purchaseOrderRequestShow">Purchase Order Request</a></li>
                <li class="@yield('purchase_order','')"><a class="nav-link" href="/purchase-order-show-all">Purchase Order</a></li>
                <li class="@yield('perchased_item','')"><a class="nav-link" href="/purchased-item-show-all">Purchased Item</a></li>
                <li class="@yield('permanent_assets','')"><a class="nav-link" href="/permanent-assets-show-all">Permanent Assets</a></li>
              </ul>
            </li>
            <li class="dropdown @yield('outdoor_return','') @yield('indoor_return','') @yield('return_reason','')">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Returns</span></a>
              <ul class="dropdown-menu">
                <li class="@yield('outdoor_return','')"><a class="nav-link" href="/outdoor-return-show-all">Outdoor Return</a></li>
                <li class="@yield('indoor_return','')"><a class="nav-link" href="/indoor-return-show-all">Indoor Return</a></li>
                <li class="@yield('return_reason','')"><a class="nav-link" href="/reason-show-all">Return Reasons</a></li>
              </ul>
            </li>
            <li class="dropdown @yield('equipment_transfer','') @yield('indoor_transfer','')">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i  data-feather="chevrons-down"></i><span>Transfer</span></a>
              <ul class="dropdown-menu">
                <li class="@yield('equipment_transfer','')"><a class="nav-link" href="/equipmentTransferShow">Equipment Transfer</a></li>
                <li class="@yield('indoor_transfer','')"><a class="nav-link" href=/IndoorTransferShow>Indoor Transfer</a></li>
              </ul>
            </li>
            <li class="@yield('department','')"><a class="nav-link" href="/departmentShow"><i data-feather="grid"></i><span>Department</span></a></li>
            <li class="dropdown @yield('purchase_order_request_report','') @yield('purchase_order_report','') @yield('purchase_item_report','') @yield('indoor_return_report','') @yield('outdoor_return_report','') @yield('indoor_transfer_report','') @yield('equipment_transfer_report','') @yield('permanent_assets_report','') @yield('expery_date_report','')">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i  data-feather="sliders"></i><span>Reports</span></a>
              <ul class="dropdown-menu">
                <li class="@yield('purchase_order_request_report','')"><a class="nav-link" href="/purchaseOrderRequestRepMonth">Purchase Order Request Report</a></li>
                <li class="@yield('purchase_order_report','')"><a class="nav-link" href="/purchase-order-report">Purchase Order Report</a></li>
                <li class="@yield('purchase_item_report','')"><a class="nav-link" href="/purchased-item-report">Purchase Item Report</a></li>
                <li class="@yield('indoor_return_report','')"><a class="nav-link" href="/indoor-return-report">Indoor Return Report</a></li>
                <li class="@yield('outdoor_return_report','')"><a class="nav-link" href="/out-door-return-report">Outdoor Return Report</a></li>
                <li class="@yield('indoor_transfer_report','')"><a class="nav-link" href="/indoor-transfer-report">Indoor Transfer Report</a></li>
                <li class="@yield('equipment_transfer_report','')"><a class="nav-link" href="/equipment-transfer-report">Equipment Transfer Report</a></li>
                <li class="@yield('permanent_assets_report','')"><a class="nav-link" href="/permanantAssetsReportMonth">Permanent Assets Report</a></li>
                <li class="@yield('expery_date_report','')"><a class="nav-link" href="/expery-date-report">Expery Date Report</a></li>
              </ul>
            </li>
            
            {{-- <li class="menu-header">Reports</li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="copy"></i><span>products
                  Reports</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="alert.html">Alert</a></li>
                <li><a class="nav-link" href="badge.html">Badge</a></li>
              </ul>
            </li> --}}
          </ul>
        </aside>  
      </div>
      {{-- end admin side bar --}}

      <div class="main-content">
        <section class="section">
          <div class="section-body">
            
            {{-- session --}}
            @if (\Session::has('success'))
              <div class="alert alert-success session-msg" style="width: 50%; margin:0 auto 15px auto; text-align:center;">
                <p>{{\Session::get('success')}}</p>
              </div>

              <script>
                $(function(){
                  setTimeout(function(){
                    $('.session-msg').slideUp();
                  },5000);
                });
              </script>
            @endif

            @if (\Session::has('error'))
              <div class="alert alert-danger session-msg" style="width: 50%; margin:0 auto 15px auto; text-align:center;">
                <p>{{\Session::get('error')}}</p>
              </div>


              <script>
                $(function(){
                  setTimeout(function(){
                    $('.session-msg').slideUp();
                  },5000);
                });
              </script>
            @endif
            {{-- session end --}}
 
            <div class="row">
              <div class="col-12">
                
                  @yield('content')
                
              </div>
            </div>

          </div>
        </section>
      </div>
      
      
      
      {{-- footer  --}}
      <footer class="main-footer">
        <div class="footer-left">
          &copy; ReeCha Organic Farm (Pvt) Ltd 2021. Technical support by
                    <a href="https://codevita.lk/" target="_blank">Codevita</a>.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{url('assets/js/app.min.js')}}"></script>
  <!-- JS Libraies -->
  <script src="{{url('assets/bundles/apexcharts/apexcharts.min.js')}}"></script>
  <script src="assets/bundles/datatables/datatables.min.js"></script>
  <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/bundles/jquery-ui/jquery-ui.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="{{url('assets/js/page/index.js')}}"></script>
  <script src="assets/js/page/datatables.js"></script>
  <!-- Template JS File -->
  <script src="{{url('assets/js/scripts.js')}}"></script>
  <!-- Custom JS File -->
  <script src="{{url('assets/js/custom.js')}}"></script>

  <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
  <script src="assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>

</body>

</html>