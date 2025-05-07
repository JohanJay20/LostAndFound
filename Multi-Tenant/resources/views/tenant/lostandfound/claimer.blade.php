@if($item->claimer)
<div class="modal fade" id="claimerModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Claimer Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Name</label>
                                    <input type="text" class="form-control" value="{{ $item->claimer->name }}" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control" value="{{ $item->claimer->contact_number }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Email</label>
                                    <input type="text" class="form-control" value="{{ $item->claimer->email ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
