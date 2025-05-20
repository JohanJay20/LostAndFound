@extends('tenant.layouts.admin')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <h4 class="card-title">Staff Members</h4>
          <p class="card-description">Manage the staff members</p>
        </div>
        <div class="col-md-4">
          <!-- Search Form -->
          <form method="GET" action="{{ route('staff.index') }}" class="d-flex justify-content-end">
            <div class="input-group">
              <input type="text" class="form-control" name="search" placeholder="Search for staff..." value="{{ request()->search }}">
              <button class="btn btn-secondary" type="submit">Search</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Create New Staff Button -->
      <div class="d-flex justify-content-end">
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createStaffModal">
          Create New Staff
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($staff as $staffMember)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $staffMember->name }}</td>
                <td>{{ $staffMember->email }}</td>
                <td>{{ ucfirst($staffMember->role) }}</td>
                <td>
                  <!-- Edit Button -->
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $staffMember->id }}">
                    Edit
                  </button>

                  <!-- Delete Form -->
                  <form action="{{ route('staff.destroy', $staffMember->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>

              <!-- Edit Staff Modal -->
              <div class="modal fade" id="editStaffModal{{ $staffMember->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
                  <div class="modal-content">
                    <div class="modal-body">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Edit Staff</h4>
                          <form method="POST" action="{{ route('staff.update', $staffMember->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                              <label for="editName">Name</label>
                              <input type="text" name="name" id="editName" class="form-control" value="{{ $staffMember->name }}" required>
                            </div>

                            <div class="form-group mb-3">
                              <label for="editEmail">Email</label>
                              <input type="email" name="email" id="editEmail" class="form-control" value="{{ $staffMember->email }}" required>
                            </div>

                            <div class="form-group mb-3">
                              <label for="editPassword">New Password (Leave empty to keep unchanged)</label>
                              <input type="password" name="password" id="editPassword" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                              <label for="editPasswordConfirmation">Confirm New Password</label>
                              <input type="password" name="password_confirmation" id="editPasswordConfirmation" class="form-control">
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                              <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-warning">Save Changes</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create Staff Modal -->
<div class="modal fade" id="createStaffModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content">
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Create New Staff</h4>
            <form action="{{ route('staff.store') }}" method="POST">
              @csrf

              <div class="form-group mb-3">
                <label for="createName">Name</label>
                <input type="text" name="name" id="createName" class="form-control form-control-sm" required>
              </div>

              <div class="form-group mb-3">
                <label for="createEmail">Email</label>
                <input type="email" name="email" id="createEmail" class="form-control form-control-sm" required>
              </div>

              <div class="form-group mb-3">
                <label for="createPassword">Password</label>
                <input type="password" name="password" id="createPassword" class="form-control form-control-sm" required>
              </div>

              <div class="form-group mb-3">
                <label for="createPasswordConfirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="createPasswordConfirmation" class="form-control form-control-sm" required>
              </div>

              <div class="mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Staff</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
