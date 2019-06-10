
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
    .error{ font-size:12px; font-weight:bold; color:red; margin-top:10px; margin-bottom:10px;}
    p{font-size:11px; margin:0px;}
  </style>
    <link rel="shortcut icon" href="/images/ncaa-logo.png" />

</head>
<body>
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
            <div class="input-group">
              <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>                
              </div>
              <input type="text" class="form-control bg-transparent border-0" placeholder="Search NCAA">
            </div>
        </div>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item d-none d-lg-block full-screen-link">
            <a class="nav-link">
              <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell-outline"></i>
              <span class="count-symbol bg-danger"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <h6 class="p-3 mb-0">Notifications</h6>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="mdi mdi-airplane-off"></i>
                  </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1">Boeing 7374-fg</h6>
                  <p class="text-gray ellipsis mb-0">
                    Air Peace licence will expire in 90 days
                  </p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="mdi mdi-airplane-off"></i>
                  </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1">Boeing 45KB-CV</h6>
                  <p class="text-gray ellipsis mb-0">
                    AZMAN AIR Validity Expires in 14 days.
                  </p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="mdi mdi-link-variant"></i>
                  </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1">Boeing 9JKCV</h6>
                  <p class="text-gray ellipsis mb-0">
                    DANA Airlines validity has expire
                  </p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <h6 class="p-3 mb-0 text-center">See all notifications</h6>
            </div>
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
                  @if(Auth::user()->role==1)
                    NCAA - SUPER USER
                  @elseif(Auth::user()->role==2)
                    NCAA - TOP-TIER
                  @elseif(Auth::user()->role==3)
                    NCAA - READ & VIEW
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
                <li class="nav-item"> <a class="nav-link" href="{{URL('aircraft-make')}}">Add Aircrafts Maker</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('operations')}}">Operation Type</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('user-role')}}">User Role</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL('users')}}">Users</a></li>
              </ul>
            </div>
          </li>

          @endif

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#aoc" aria-expanded="false" aria-controls="aoc">
              <span class="menu-title">AOC</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="aoc">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('view-all-aoc')}}">View All AOC's</a></li>
                  @if(Auth::user()->role == 1  || Auth::user()->role == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('new-aoc')}}">Add New AOC</a></li>
                  @endif
                  @if(Auth::user()->role == 1)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('assign-operation-type-to-aoc')}}">Assign Opr Spec to AOC</a></li>
                  @endif
                  
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ac-status" aria-expanded="false" aria-controls="ac-status">
              <span class="menu-title">A/C Status</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ac-status">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('view-all-air-craft-status')}}">View All Aircraft status</a></li>
                  @if(Auth::user()->role == 1  || Auth::user()->role == 2)
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
                  @if(Auth::user()->role == 1  || Auth::user()->role == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('amo-local')}}">Local</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{URL('amo-foreign')}}">Foreign </a></li>
                  @endif
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#focc" aria-expanded="false" aria-controls="focc">
              <span class="menu-title">FOCC</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="focc">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL('focc-lists')}}">View</a></li>
                  @if(Auth::user()->role == 1  || Auth::user()->role == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{URL('focc')}}">Add</a></li>
                  @endif
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="menu-title">MCC</span>
              <i class="mdi mdi-contacts menu-icon"></i>
            </a>
          </li>
          
        </ul>
      </nav>

      <div class="main-panel" >
        <div class="content-wrapper">
          @yield('main')
        </div>
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2019 <a href="#" target="_blank">Team ICT</a>. All rights reserved.</span>
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

  @yield('scripts')
</body>

</html>
