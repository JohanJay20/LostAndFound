@extends('layouts.admin')

@section('title', 'version')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Version Information</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('version.check') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Version:</label>
                        <input type="text" class="form-control" value="{{ $currentVersion }}" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Check for Updates</button>
                </form>

                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endsection