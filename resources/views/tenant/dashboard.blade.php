@extends('tenant.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <button
        id="updateBtn"
        class="bg-blue-600 text-white px-4 py-2 rounded"
        onclick="handleUpdate()"
        aria-label="Update Application"
        tabindex="0"
    >
        Update Application
    </button>
    <div id="updateStatus" class="mt-2 text-sm"></div>
</div>
<script>
function handleUpdate() {
    const status = document.getElementById('updateStatus');
    status.textContent = 'Updating...';
    fetch('{{ route('admin.update.perform') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ version: 'v1.1.0' }) // Replace with the version you want
    })
    .then(res => res.json())
    .then(data => {
        status.textContent = data.message || data.error || 'Done.';
    })
    .catch(() => {
        status.textContent = 'Update failed.';
    });
}
</script>
@endsection