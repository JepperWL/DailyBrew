@extends('admin.header')

@section('title', 'Orders - DailyBrew')

@section('content')
<div class="container ">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
            <h2 class="fw-bold mb-0"><i class="fas fa-shopping-bag me-2 text-primary"></i>Customer Orders</h2>
            <p class="text-muted">Manage and track all customer orders</p>
        </div>
    </div>



    <!-- Orders List -->
    <div class="bg-white rounded-3 p-4 shadow-sm">
        @if($orders->count() > 0)
        @foreach($orders as $order)
        <!-- Order Item -->
        <div class="border-bottom pb-3 mb-3">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary fw-normal">#{{ $order->order_number }}</span>
                    <span class="text-muted small"><i class="fas fa-calendar-alt me-1"></i> {{
                        $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @switch($order->status)
                    @case('pending')
                    <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                    @break
                    @case('paid')
                    <span class="badge bg-success px-3 py-2">Paid</span>
                    @break

                    @case('canceled')
                    <span class="badge bg-danger px-3 py-2">Canceled</span>
                    @break
                    @case('expired')
                    <span class="badge bg-secondary px-3 py-2">Expired</span>
                    @break
                    @case('failed')
                    <span class="badge bg-danger px-3 py-2">Failed</span>
                    @break
                    @default
                    <span class="badge bg-secondary px-3 py-2">{{ ucfirst($order->status) }}</span>
                    @endswitch

                    @if($order->status === 'pending')
                    <form action="{{ route('admin.order.check-status', $order->id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-info" title="Check Payment Status">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap">
                <!-- Product Image & Info -->
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    @php
                    $firstItem = $order->orderItems->first();
                    $itemCount = $order->orderItems->count();
                    @endphp
                    @if($firstItem && $firstItem->beverage->image)
                    <img src="{{ asset('storage/' . $firstItem->beverage->image) }}"
                        alt="{{ $firstItem->beverage->name }}" class="rounded-3 shadow-sm"
                        style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-image text-muted"></i>
                    </div>
                    @endif
                    <div>
                        @if($firstItem)
                        <h5 class="fw-bold mb-1">{{ $firstItem->beverage->name }}
                            @if($itemCount > 1)
                            <small class="text-muted">(+ {{ $itemCount - 1 }} other item{{ $itemCount > 2 ? 's' : ''
                                }})</small>
                            @endif
                        </h5>
                        @else
                        <h5 class="fw-bold mb-1">No items</h5>
                        @endif
                        <p class="text-muted mb-0 small">
                            <span class="me-2"><i class="fas fa-user me-1"></i> {{ $order->user->name }}</span>
                            @if($order->user->phone)
                            <span><i class="fas fa-phone me-1"></i> {{ $order->user->phone }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <!-- Order Info -->
                <div class="d-flex align-items-center justify-content-between mt-3 mt-md-0">
                    <div class="me-4 text-center">
                        <span class="d-block text-muted small">Items</span>
                        <span class="fw-bold">{{ $itemCount }}</span>
                    </div>
                    <div class="me-4 text-center">
                        <span class="d-block text-muted small">Total</span>
                        <span class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <!-- No Orders -->
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-4"></i>
            <h3>No Orders Found</h3>
            <p class="text-muted mb-4">No orders match your current filters.</p>
        </div>
        @endif
    </div>
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="card-footer bg-white py-3">
        <div class="d-flex justify-content-between align-items-center ms-4 me-4">
            <div class="text-muted small">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
            </div>
            <nav aria-label="Product pagination">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
  
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bootstrapAlert = new bootstrap.Alert(alert);
                bootstrapAlert.close();
            });
        }, 5000);
    });
</script>
@endsection