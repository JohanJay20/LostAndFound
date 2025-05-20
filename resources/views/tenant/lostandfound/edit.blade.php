<div class="modal fade" id="editLostAndFoundModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('lostandfound.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Item</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Item Name</label>
                                        <input type="text" name="item_name" class="form-control" value="{{ $item->item_name }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required>{{ $item->description }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Found At</label>
                                        <input type="date" name="found_at" class="form-control" value="{{ $item->found_at->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Category</label>
                                        <input type="text" name="category" class="form-control" value="{{ $item->category }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-control" value="{{ $item->location }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="unclaimed" {{ $item->status == 'unclaimed' ? 'selected' : '' }}>Unclaimed</option>
                                            <option value="claimed" {{ $item->status == 'claimed' ? 'selected' : '' }}>Claimed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
