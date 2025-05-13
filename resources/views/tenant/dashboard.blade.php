@extends('tenant.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <button 
        id="updateTenantBtn"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
        aria-label="Update Tenant"
        tabindex="0"
        onclick="handleTenantUpdate()"
        onkeydown="if(event.key==='Enter'){handleTenantUpdate();}"
    >
        Update to Latest Version
    </button>
    <div id="updateStatus" class="mt-4 text-sm"></div>
</div>
<script>
    function handleTenantUpdate() {
        const btn = document.getElementById('updateTenantBtn');
        const status = document.getElementById('updateStatus');
        btn.disabled = true;
        status.textContent = 'Updating...';

        fetch('{{ route('tenant.dashboard.update') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                status.textContent = 'Update successful! Reloading...';
                setTimeout(() => location.reload(), 2000);
            } else {
                status.textContent = data.message || 'Update failed.';
            }
        })
        .catch(() => {
            status.textContent = 'Update failed. Please try again.';
        })
        .finally(() => {
            btn.disabled = false;
        });
    }
</script>
@endsection