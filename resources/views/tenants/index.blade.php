@extends('layouts.admin')

@section('title', 'Tenant')

@section('content')
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title mb-0">Tenant Requests</h4>
       
      </div>
      <p class="card-description">Hoverable table using <code>.table-hover</code></p>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Organization</th>
              <th>Domain</th>
              <th>Email</th>
              <th>Plan</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $request)
              <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->username }}</td>
                <td>{{ $request->organization }}</td>
                <td>
                  @if($request->status === 'active')
                    <a href="http://{{ $request->domain }}.localhost:8000" target="_blank">
                      {{ $request->domain }}.localhost:8000
                    </a>
                  @else
                    <span class="text-muted">{{ $request->domain }}.localhost:8000</span>
                  @endif
                </td>
                <td>{{ $request->email }}</td>
                <td>{{ $request->plan }}</td>
                <td>
                  @if($request->status === 'active')
                    <label class="badge badge-success">Active</label>
                  @elseif($request->status === 'disabled')
                    <label class="badge badge-secondary">Disabled</label>
                  @elseif($request->status === 'rejected')
                    <label class="badge badge-danger">Rejected</label>
                  @else
                    <label class="badge badge-warning">Pending</label>
                  @endif
                </td>
                <td>
                  @if($request->status === 'pending')
                    <!-- Approve modal trigger -->
                    <button class="btn btn-sm btn-primary" onclick="showModal('approveModal{{ $request->id }}')">Approve</button>

                    <!-- Reject modal trigger -->
                    <button class="btn btn-sm btn-secondary" onclick="showModal('rejectModal{{ $request->id }}')">Reject</button>
                  @elseif($request->status === 'disabled')
                    <!-- Enable modal trigger -->
                    <button class="btn btn-sm btn-success" onclick="showModal('enableModal{{ $request->id }}')">Enable</button>
                  @endif

                  <!-- Edit modal trigger -->
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $request->id }}">Edit</button>

                  <!-- Delete modal trigger (not for active tenants) -->
                  @if(!in_array($request->status, ['active', 'pending']))
                    <button type="button" class="btn btn-sm btn-danger" onclick="showModal('deleteModal{{ $request->id }}')">Delete</button>
                  @endif

                  <!-- Disable modal trigger (for active tenants) -->
                  @if($request->status === 'active')
                    <button class="btn btn-sm btn-danger" onclick="showModal('disableModal{{ $request->id }}')">Disable</button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">No tenant requests found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Include modals partial -->
@include('tenants.modal')

<script>
  // Disable submit button on form submission and show a spinner
  document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function(event) {
      var submitButton = form.querySelector('button[type="submit"]');
      if (submitButton) {
        // Disable the button
        submitButton.disabled = true;
        
        // Create a spinner element
        var spinner = document.createElement('span');
        spinner.classList.add('spinner-border', 'spinner-border-sm', 'text-light' ); // Bootstrap spinner class
        
        // Add spinner alongside button text
        var buttonText = submitButton.textContent || submitButton.innerText; // Store current button text
        submitButton.innerHTML = buttonText; // Re-add the button text
        submitButton.appendChild(spinner); // Append spinner next to text
      }
    });
  });

  function showModal(id) {
    var modal = new bootstrap.Modal(document.getElementById(id));
    modal.show();
  }
</script>

@endsection
