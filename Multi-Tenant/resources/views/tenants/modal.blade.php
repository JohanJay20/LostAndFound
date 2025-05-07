<!-- resources/views/tenant/partials/modals.blade.php -->
@foreach($requests as $request)
  <!-- Edit Modal -->
  <div class="modal fade" id="editModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
      <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
        <div class="modal-body p-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Edit Tenant Request</h4>
              <p class="card-description">Update the tenant details below</p>
              <form method="POST" action="{{ route('tenant.requests.update', $request->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-6"><div class="form-group"><label>Username</label><input type="text" name="username" value="{{ $request->username }}" class="form-control" required></div></div>
                  <div class="col-md-6"><div class="form-group"><label>Organization</label><input type="text" name="organization" value="{{ $request->organization }}" class="form-control" required></div></div>
                  <div class="col-md-6"><div class="form-group"><label>Domain</label><input type="text" name="domain" value="{{ $request->domain }}" class="form-control" required></div></div>
                  <div class="col-md-6"><div class="form-group"><label>Address</label><input type="text" name="address" value="{{ $request->address }}" class="form-control" required></div></div>
                  <div class="col-md-6"><div class="form-group"><label>Email</label><input type="email" name="email" value="{{ $request->email }}" class="form-control" required></div></div>
                  <div class="col-md-6"><div class="form-group"><label>Plan</label>
                    <select name="plan" class="form-control" required>
                      <option value="Basic" {{ $request->plan == 'Basic' ? 'selected' : '' }}>Basic</option>
                      <option value="Pro" {{ $request->plan == 'Pro' ? 'selected' : '' }}>Pro</option>
                    </select>
                  </div></div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                  <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to delete this tenant request?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <form action="{{ route('tenant.requests.destroy', $request->id) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Reject Modal -->
  <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-secondary">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title">Reject Tenant</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to reject this tenant?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <form action="{{ route('tenant.requests.reject', $request->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-secondary">Reject</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Enable Modal -->
  <div class="modal fade" id="enableModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-success">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Enable Tenant</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to enable this tenant?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <form action="{{ route('tenant.requests.enable', $request->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">Enable</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Disable Modal -->
  <div class="modal fade" id="disableModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Disable Tenant</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to disable this tenant?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <form action="{{ route('tenant.requests.disable', $request->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">Disable</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Approve Modal -->
  <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-primary">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Approve Tenant</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to approve this tenant?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <form action="{{ route('tenant.requests.approve', $request->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary">Approve</button>
          </form>
        </div>
      </div>
    </div>
  </div>

@endforeach
