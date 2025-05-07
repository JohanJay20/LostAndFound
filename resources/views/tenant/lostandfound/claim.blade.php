<div class="modal fade" id="claimModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('lostandfound.claim', $item->id) }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Claim Item</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Claimant's Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Email (Optional)</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Submit Claim</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
