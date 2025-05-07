<div class="modal fade" id="createLostAndFoundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create New Item</h4>
                        <form action="{{ route('lostandfound.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Item Name</label>
                                        <input type="text" name="item_name" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Found At</label>
                                        <input type="date" name="found_at" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Category</label>
                                        <input type="text" name="category" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="unclaimed">Unclaimed</option>
                                            <option value="claimed">Claimed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
