<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Choose Your Plan | Lost & Found Portal</title>

  <!-- CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  
@include('partials.feedback-modals')

  <div class="container-scroller">
    <!-- Main Content -->
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex flex-column align-items-center justify-content-center px-4">

        <!-- Welcome Header -->
        <div class="text-center my-5 w-100">
          <h1 class="display-5 fw-bold text-primary">Welcome to Lost & Found Multi-Tenant Portal</h1>
          <p class="lead text-muted mt-3">Choose the best plan for your organization and apply to get started.</p>
        </div>

        <!-- Plans Section -->
        <div class="row w-100 justify-content-center mb-5">
          <!-- Free Plan -->
          <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-start border-4 border-success">
              <div class="card-body">
                <h3 class="card-title text-success">Basic Plan</h3>
                <p>Perfect for small schools or communities looking for a simple lost & found solution.</p>
                <ul>
                  <li>Access to basic lost & found listing</li>
                  <li>Access to reporting for lost items</li>
                </ul>
                <!-- Apply Now Button for Free Plan -->
                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#applyModal" onclick="setPlan('Basic')">Apply Now</button>
              </div>
            </div>
          </div>

          <!-- Pro Plan -->
          <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-start border-4 border-primary">
              <div class="card-body">
                <h3 class="card-title text-primary">Pro Plan</h3>
                <p>Best for universities, businesses, or large institutions needing custom features.</p>
                <ul>
                  <li>All Free Plan features</li>
                  <li>Custom branding per tenant</li>
                  <li>Dedicated dashboard & analytics</li>
                  <li>Priority support</li>
                </ul>
                <!-- Apply Now Button for Pro Plan -->
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#applyModal" onclick="setPlan('Pro')">Apply Now</button>
             
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

 <!-- Modal -->
 <div class="modal fade" id="applyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
      <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
        <div class="modal-body p-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title" id="modalTitle">Tenant Registration</h4>
              <p class="card-description">Fill in the details below</p>
              <form method="POST" action="{{ route('tenant.requests.store') }}">
                @csrf
                <input type="hidden" name="plan" id="planInput">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" name="username" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Organization</label>
                      <input type="text" name="organization" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Domain</label>
                      <div class="input-group">
                        <input type="text" name="domain" class="form-control" placeholder="yourdomain" required>
                        <span class="input-group-text">.localhost</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Address</label>
                      <input type="text" name="address" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email address</label>
                      <input type="email" name="email" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                  <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- JS Files -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script>
    function setPlan(plan) {
      document.getElementById('modalTitle').innerText = plan + ' Plan Registration';
      document.getElementById('planInput').value = plan;
    }
  </script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    @if (session('success'))
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    @endif
    @if ($errors->any())
      var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
      errorModal.show();
    @endif
  });
</script>

</body>
</html>
