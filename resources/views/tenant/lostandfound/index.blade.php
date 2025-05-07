@extends('tenant.layouts.admin')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h4 class="card-title">Lost and Found Items</h4>
                    <p class="card-description">Manage lost and found items</p>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <form method="GET" action="{{ route('lostandfound.index') }}" class="me-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search for items..." value="{{ request()->search }}">
                            <button class="btn btn-secondary" type="submit">Search</button>
                        </div>
                    </form>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLostAndFoundModal">Create New Item</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Found At</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Actions</th>
                            <th>Claimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                        <tr id="lost-and-found-{{ $item->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ optional($item->found_at)->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge {{ $item->status === 'claimed' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->location }}</td>
                            <td>{{ $item->category }}</td>
                            <td>
                               
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editLostAndFoundModal{{ $item->id }}">Edit</button>

                                    @if($item->status === 'unclaimed')
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#claimModal{{ $item->id }}">Claim</button>
                                    @endif

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">Delete</button>
                                
                            </td>
                            <td>
                                @if($item->claimer)
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#claimerModal{{ $item->id }}">View</button>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Include modals per item --}}
                        @include('tenant.lostandfound.edit', ['item' => $item])
                        @include('tenant.lostandfound.claim', ['item' => $item])
                        @include('tenant.lostandfound.delete', ['item' => $item])
                        @include('tenant.lostandfound.claimer', ['item' => $item])
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
@include('tenant.lostandfound.create')

@endsection
