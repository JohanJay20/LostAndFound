<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Welcome - Tenant</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="../../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <!-- endinject -->

  <!-- inject:css -->
  <link rel="stylesheet" href="../../assets/css/style.css">
  <!-- endinject -->

  <link rel="shortcut icon" href="../../assets/images/favicon.png" />
</head>
<body>

  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">

      <div class="content-wrapper flex-column align-items-center justify-content-center px-4">

      <!-- Login and Logo Section -->
<div class="d-flex justify-content-between align-items-center w-100 px-4">
  
  <!-- Logo -->
  <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
    <img src="{{ $logo }}" alt="logo" style="width: 100px; height: auto;"/>
  </a>
  
  <!-- Login Button -->
  <div class="d-flex justify-content-end">
    <a href="{{ route('login') }}" class="btn btn-link text-primary" >Login</a>
  </div>

</div>

        <!-- Welcome Section -->
        <div class="d-flex justify-content-center align-items-center w-100" style="margin-top: 130px; margin-bottom: 30px;">
  <h1 class="fw-bold text-primary mb-0 me-2">Welcome</h1>
  <h2 class="text-muted mb-0 mx-2">to</h2>
  <h1 class="text-primary mb-0 ms-2">{{ $tenant_organization }}</h1>
  
</div>


        <!-- Lost & Found Items Section -->
        <div class="w-100 text-center mb-5">
          <h4 class="text-primary mb-4">Latest Lost and Found </h4>

          @if($items->isEmpty())
            <p class="text-muted">No items found.</p>
          @else
            <div class="row justify-content-center">
              @foreach($items as $item)
                <div class="col-md-5 col-lg-4 mb-4">
                  <div class="card shadow-sm border-start border-4 border-primary h-100">
                    <div class="card-body text-start">
                      <h5 class="card-title text-primary">Item: {{ $item->item_name }}</h5>
                      <ul class="ps-3 text-dark">
                        <li><strong>Found At:</strong> {{ \Carbon\Carbon::parse($item->found_at)->format('Y-m-d') }}</li>
                        <li><strong>Status:</strong> {{ $item->status }}</li>
                        <li><strong>Location:</strong> {{ $item->location }}</li>
                        <li><strong>Category:</strong> {{ $item->category }}</li>
                      </ul>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>

      </div>

    </div>
  </div>

  <!-- plugins:js -->
  <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- endinject -->

  <!-- inject:js -->
  <script src="../../assets/js/off-canvas.js"></script>
  <script src="../../assets/js/template.js"></script>
  <script src="../../assets/js/settings.js"></script>
  <script src="../../assets/js/hoverable-collapse.js"></script>
  <script src="../../assets/js/todolist.js"></script>
  <!-- endinject -->

</body>
</html>
