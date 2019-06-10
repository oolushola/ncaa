
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>N.C.A.A</title>
  <link rel="stylesheet" href="{{URL::asset('../../vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{URL::asset('../../vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{URL::asset('../../css/style.css')}}">
  <link rel="shortcut icon" href="" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <img src="{{URL::asset('../../images/ncaa-logo.png')}}" style="width:120px; height:120px; float:right">
              </div>
              <h4>Nigerian Civil Aviation Authority</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" placeholder="email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" placeholder="password">
                </div>
                <div class="mt-3">
                  <a class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" href="{{URL('dashboard')}}">SIGN IN</a>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <script src="{{URL::asset('../../vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{URL::asset('../../vendors/js/vendor.bundle.addons.js')}}"></script>
  <script src="{{URL::asset('../../js/off-canvas.js')}}"></script>
  <script src="{{URL::asset('../../js/misc.js')}}"></script>

</body>

</html>
