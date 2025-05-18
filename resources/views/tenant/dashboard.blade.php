@extends('tenant.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="d-flex justify-content-start">
    <button class="btn btn-primary mb-4" id="updateBtn" onclick="handleUpdate()" aria-label="Update Application" tabindex="0">
        Update Application
    </button>
</div>
<div id="updateStatus" class="text-muted small mt-2"></div>

</div>

<script>
async function handleUpdate() {
    const status = document.getElementById('updateStatus');
    const updateBtn = document.getElementById('updateBtn');
    
    try {
        status.textContent = 'Checking for updates...';
        status.className = 'mt-2 text-sm text-gray-600';
        updateBtn.disabled = true;

        // First check for available updates
        const checkResponse = await fetch('{{ route('admin.update.check') }}');
        const checkData = await checkResponse.json();

        if (!checkData.has_update) {
            status.textContent = 'No updates available.';
            status.className = 'mt-2 text-sm text-gray-600';
            return;
        }

        // If updates are available, proceed with update
    status.textContent = 'Updating...';
        
        const response = await fetch('{{ route('admin.update.perform') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
            body: JSON.stringify({ 
                version: checkData.available_updates[0]?.version || 'v1.1.0' 
            })
        });

        if (!response.ok) {
            const data = await response.json();
            throw new Error(data.error || 'Update failed');
        }

        const data = await response.json();
        status.textContent = data.message || 'Update completed successfully.';
        status.className = 'mt-2 text-sm text-green-600';
    } catch (error) {
        console.error('Update error:', error);
        status.textContent = `Error: ${error.message}`;
        status.className = 'mt-2 text-sm text-red-600';
    } finally {
        updateBtn.disabled = false;
    }
}
</script>
@endsection