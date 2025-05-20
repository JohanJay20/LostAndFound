@extends('tenant.layouts.admin')

@section('title', 'Report')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
          
                <div class="card-body d-flex justify-content-end">
                    <a href="{{ route('reports.download') }}" class="btn btn-primary">
                        <i class="mdi mdi-download"></i> Download Report
                    </a>
                </div>
        
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Items by Category</h4>
                    <div class="doughnutjs-wrapper d-flex justify-content-center">
                        <canvas id="pieChart" style="height: 250px !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Items by Status</h4>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Data for charts
window.chartData = {
    category: {
        labels: {!! json_encode($categoryData->pluck('category')) !!},
        data: {!! json_encode($categoryData->pluck('total')) !!}
    },
    status: {
        labels: {!! json_encode($statusData->pluck('status')) !!},
        data: {!! json_encode($statusData->pluck('total')) !!}
    }
};
</script>
<script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../../assets/vendors/chart.js/chart.umd.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../../assets/js/off-canvas.js"></script>
<script src="../../assets/js/template.js"></script>
<script src="../../assets/js/settings.js"></script>
<script src="../../assets/js/hoverable-collapse.js"></script>
<script src="../../assets/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="../../assets/js/chart.js"></script>
@endsection