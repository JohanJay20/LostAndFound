@extends('tenant.layouts.admin')

@section('title', 'Customize Dashboard')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <!-- Header -->
           <!-- Header with Reset Button -->
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h4 class="card-title mb-0">Customize Dashboard</h4>
        <p class="card-description mt-4">Manage the staff members</p>
    </div>
    <div class="col-md-4 text-end">
        <form action="{{ route('customize.reset') }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-danger">Reset</button>
        </form>
    </div>
</div>


            <!-- Form Start -->
            <form action="{{ route('customize.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Font Selection -->
                        <div class="form-group mb-4" style="max-width: 300px;">
                            <label for="font-select">Select Font</label>
                            <select name="font" id="font-select" class="form-control">
                                <option value="Arial" {{ $font == 'Arial' ? 'selected' : '' }}>Arial</option>
                                <option value="Helvetica" {{ $font == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                                <option value="Times New Roman" {{ $font == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                            </select>
                        </div>

                        <!-- Sidebar Color -->
                        <div class="form-group mb-4" style="max-width: 300px;">
                            <label for="sidebar-color">Sidebar Color</label>
                            <input type="color" name="sidebar_color" id="sidebar-color" class="form-control" value="{{ $sidebarColor }}">
                        </div>

                        <!-- Navbar Color -->
                        <div class="form-group mb-4" style="max-width: 300px;">
                            <label for="navbar-color">Navbar Color</label>
                            <input type="color" name="navbar_color" id="navbar-color" class="form-control" value="{{ $navbarColor }}">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Logo Upload -->
                        <!-- Logo Upload -->
                        <div class="form-group mb-4">
    <label for="logo">Current Logo</label>
    <div class="d-flex align-items-center gap-3">
        <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
            <img id="logo-preview" src="{{ $logo }}" alt="Current Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <input type="file" name="logo" id="logo" class="form-control" onchange="previewImage(this, 'logo-preview')" style="max-width: 250px;">
    </div>
</div>



                       <!-- Mini Logo Upload -->
<div class="form-group mb-4">
    <label for="logo_mini">Current Mini Logo</label>
    <div class="d-flex align-items-center gap-3">
        <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
            <img id="logo-mini-preview" src="{{ $logoMini }}" alt="Mini Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <input type="file" name="logo_mini" id="logo_mini" class="form-control" onchange="previewImage(this, 'logo-mini-preview')" style="max-width: 250px;">
    </div>
</div>


                <!-- Submit Button -->
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary mt-3">Save Customizations</button>
                
                
                </div>
            </form>
           
        </div>
    </div>
</div>

<!-- Live Preview Script -->
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
