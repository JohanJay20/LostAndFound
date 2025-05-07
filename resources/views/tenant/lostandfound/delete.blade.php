<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <form action="{{ route('lostandfound.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
