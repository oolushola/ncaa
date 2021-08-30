
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{URL::asset('vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}" />
  <link rel="stylesheet" href="{{URL::asset('vendors/css/vendor.bundle.base.css')}}" />
  <link rel="stylesheet" href="{{URL::asset('css/style.css')}}" />
  <link rel="stylesheet" href="{{URL::asset('css/custom.css')}}" />
  <style>
    @media print {
      tr.table-secondary {
        background-color: #000 !important
        -webki-print-color-adjust:exact
      }
    }
    .error{ font-size:12px; font-weight:bold; color:red; margin-top:10px; margin-bottom:10px;}
    p{font-size:11px; margin:0px;}
    #exportTableData_filter{
      font-size:12px;
      display:inline-block;
      margin-left:10px;
    }
    #exportTableData_filter input {
      border:1px solid #ccc;
    }
    #exportTableData_length{
      font-size:12px;
      display:inline-block;
      margin-left:10px;
    }
    .paging_simple_numbers{
      display:none;
    }
    .dashboard__economic {
      background:rgba(255, 255, 255, 0.2); color:#fff; padding:10px; border-radius:5px; cursor:pointer;
    }
    .dashboard__economic:hover {
      background: #fbfbfb;
      color:#666;
    } 

  </style>
    <link rel="shortcut icon" href="/images/ncaa-logo.png" />

</head>
<body>
    <?php $auth = Auth::user()->role ?>
  <div class="container-scroller">
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{URL('/dashboard')}}">
          <img src="{{URL::asset('../../images/ncaa-logo.png')}}" alt="logo" style="height:50px; width:50px;"/>
        </a>

        <a class="navbar-brand brand-logo-mini" href="{{URL('/dashboard')}}">
          <img src="{{URL::asset('../../images/ncaa-logo.png')}}" alt="logo" style="height:50px; width:50px;" />
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <div class="search-field d-none d-md-block">
            <!-- <div class="input-group">
              <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>                
              </div>
              <input type="text" class="form-control bg-transparent border-0" placeholder="Search NCAA"> 
            </div> -->
        </div>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item d-none d-lg-block full-screen-link">
            <a class="nav-link">
              <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
            </a>
          </li>
          <li class="nav-item nav-logout d-lg-block">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size:12px; color:#ff0000">
              <i class="mdi mdi-power"></i>
              Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <a href="{{URL('update-profile')}}" class="nav-link">
              <div class="nav-profile-image">
                @if(Auth::user()->photo == '')
                  <span class="mdi mdi-account-circle" style="font-size:50px; color:#d599ff"></span>
                @else
                <?php
                $photo = Auth::user()->photo;
                ?>
                <img src="{{URL::asset('confidentials/users/'.$photo.' ')}}" alt="profile-photo">
                <!-- <span class="login-status online">Active</span> change to offline or busy as needed  -->
                @endif             
              </div>
              <div class="nav-profile-text d-flex flex-column">
                <span class="font-weight-bold mb-2">{{ucwords(Auth::user()->name)}}</span>
                <span class="text-secondary text-small">
                  @if($auth == 1)
                    {{ strtoupper(Auth::user()->department) }} - SUPER USER
                  @elseif($auth == 2)
                  {{ strtoupper(Auth::user()->department) }} - TOP-TIER
                  @elseif($auth == 3)
                  {{ strtoupper(Auth::user()->department) }} - READ & VIEW
                  @endif
                </span>
              </div>
            </a>
            
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{URL('dashboard')}}">
              <span class="menu-title">Dashboard</span>
              <i class="mdi mdi-home menu-icon"></i>
            </a>
          </li>
          
          @if(Auth::user()->role==1)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#gop" aria-expanded="false" aria-controls="gop">
              <span class="menu-title">Global Operations</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="gop">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('aircraft-make')}}">Aircrafts Maker</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('aircraft-type')}}">Aircraft Type</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('foreign-registration-marks')}}">Foreign Registration Marks</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('foreign-amo-holder')}}">Foreign AMO</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('general-aviation')}}">General Aviation</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('operations')}}">Operation Type</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('state-of-registry')}}">State of Registry</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('training-organization')}}">Training Organization</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('travel-agency')}}">Travel Agency</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('foreign-airline')}}">Foreign Airline</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('cpm')}}">CPM</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('team-members')}}">Team Members</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('applicant-name')}}">Applicant Name</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('certification-type')}}">Certification Type</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('user-role')}}">User Role</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('users')}}">Users</a></li>
              </ul>
            </div>
          </li>

          @endif

          @if(!isset(Auth::user()->department) || Auth::user()->department == 'dolt' ||  Auth::user()->department == 'daws' )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#aoc" aria-expanded="false" aria-controls="aoc">
              <span class="menu-title">AOC</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="aoc">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('view-all-aoc')}}">View All AOC's</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('new-aoc')}}">Add New AOC</a></li>
                  @endif
                  @if($auth == 1)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('assign-operation-type-to-aoc')}}">Assign Opr Spec to AOC</a></li>
                  @endif
                  
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#certTracker" aria-expanded="false" aria-controls="certTracker">
              <span class="menu-title">Certification Tracker</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="certTracker">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('certification-tracker/view')}}">Certification Tracker</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('certification-tracker')}}">New Certificate Tracker</a></li>
                  @endif
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ato" aria-expanded="false" aria-controls="ato">
              <span class="menu-title">ATO</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ato">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('ato/view')}}">View All ATO's</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('ato')}}">Add New ATO</a></li>
                  @endif 
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#fadacl" aria-expanded="false" aria-controls="fadacl">
              <span class="menu-title">Foreign Airline DACL</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="fadacl">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('foreign-airline-dacl/view')}}">View All Foriegn Airline DACL</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('foreign-airline-dacl')}}">Add New Foreign Airline DACL</a></li>
                  @endif 
              </ul>
            </div>
          </li>
          @endif

          @if(!isset(Auth::user()->department) || Auth::user()->department == 'daws' )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ac-status" aria-expanded="false" aria-controls="ac-status">
              <span class="menu-title">A/C Status</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ac-status">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('view-all-air-craft-status')}}">View All Aircraft status</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('add-new-aircraft')}}">Add New Aircraft</a></li>
                  @endif
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#amo" aria-expanded="false" aria-controls="amo">
              <span class="menu-title">AMO</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="amo">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('amo-view-selection')}}">View AMO's</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('amo-local')}}">Local</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{URL('amo-foreign')}}">Foreign </a></li>
                  @endif
              </ul>
            </div>
          </li>
          @endif

          @if(!isset(Auth::user()->department) || Auth::user()->department == 'dolt'  ||  Auth::user()->department == 'daws' )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#focc" aria-expanded="false" aria-controls="focc">
              <span class="menu-title">FOCC & MCC</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="focc">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('focc-lists')}}">View</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('focc-and-mcc')}}">Add</a></li>
                  @endif
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#typeacceptance" aria-expanded="false" aria-controls="typeacceptance">
              <span class="menu-title">T.A.C</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="typeacceptance">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('all-type-acceptance-certificate')}}">View</a></li>
                  @if($auth == 1  || $auth == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('type-acceptance-certificate')}}">Add</a></li>
                  @endif
              </ul>
            </div>
          </li>
          @endif
          
          @if(!isset(Auth::user()->department) || (Auth::user()->department == 'datr' ))
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#economicLicence" aria-expanded="false" aria-controls="gop">
              <span class="menu-title">Economic Licence</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="economicLicence">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/aop')}}">AOP</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/atl')}}">ATL</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/pncf')}}">PNCF</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/atol')}}">ATOL</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/paas')}}">PAAS</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/ato')}}">ATO</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('economic-licence/fcop')}}">FCOP</a></li>
              </ul>
            </div>
          </li>
          @endif
        </ul>
      </nav>

      <div class="main-panel" >
        <div class="content-wrapper-2">
          @yield('main')
        </div>
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © {{date('Y')}} <a href="#" target="_blank">Team ICT</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Made with <i class="mdi mdi-heart text-danger"></i> by the NCAA ICT Team </span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="{{URL::asset('vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{URL::asset('vendors/js/vendor.bundle.addons.js')}}"></script>
  <script src="{{URL::asset('js/off-canvas.js')}}"></script>
  <script src="{{URL::asset('js/misc.js')}}"></script>
  <script src="{{URL::asset('js/chart.js')}}"></script>
  <script src="{{URL::asset('js/dashboard.js')}}"></script>
  <script src="{{URL::asset('js/file-upload.js')}}"></script>
  <script src="{{URL::asset('js/print.js')}}"></script>

  @yield('scripts')
</body>

</html>
