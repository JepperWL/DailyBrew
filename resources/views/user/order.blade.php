@extends('user.header')

@section('title', 'My Orders - DailyBrew')

@section('content')
<div class="container-fluid mt-5 mb-5" style="padding-left: 10%; padding-right: 10%;">
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

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">My Orders</h1>
            <p class="text-muted">Track and manage your DailyBrew coffee orders</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            @if($orders->count() > 0)
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4">Order ID</th>
                                    <th class="py-3">Product</th>
                                    <th class="py-3 text-center">Date</th>
                                    <th class="py-3 text-end">Price</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-medium">#{{ $order->order_number }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $firstItem = $order->orderItems->first();
                                                $itemCount = $order->orderItems->count();
                                            @endphp
                                            @if($firstItem && $firstItem->beverage->image)
                                                <img src="{{ asset('storage/' . $firstItem->beverage->image) }}" 
                                                     class="rounded-3 me-3" width="60" height="60" 
                                                     style="object-fit: cover;" alt="{{ $firstItem->beverage->name }}">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                @if($firstItem)
                                                    <div class="fw-medium">{{ $firstItem->beverage->name }} ({{ $firstItem->quantity }})</div>
                                                    @if($itemCount > 1)
                                                        <span class="text-muted small">+ {{ $itemCount - 1 }} other item{{ $itemCount > 2 ? 's' : '' }}</span>
                                                    @endif
                                                @else
                                                    <div class="fw-medium">No items</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="text-end fw-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                                @break
                                            @case('paid')
                                                <span class="badge bg-success px-3 py-2 rounded-pill">Paid</span>
                                                @break
                                           
                                            @case('canceled')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">Canceled</span>
                                                @break
                                            @case('expired')
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">Expired</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($order->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('user.order-detail', $order->id) }}" 
                                               class="btn btn-sm btn-outline-primary rounded-pill">
                                                Details
                                            </a>
                                            @if($order->status === 'pending')
                                                <form action="{{ route('user.check-status', $order->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-info rounded-pill">
                                                        Check
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="card-footer bg-white py-3">
                    <nav aria-label="Orders pagination">
                        {{ $orders->links() }}
                    </nav>
                </div>
                @endif
            </div>
            @else
            <!-- No Orders -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body py-5 text-center">
                    <div class="my-4">
                        <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                    </div>
                    <h3 class="fw-bold mb-3">No Orders Yet</h3>
                    <p class="text-muted mb-4">You haven't placed any orders with us yet.</p>
                    <a href="{{ route('user.shop') }}" class="btn btn-primary rounded-pill px-4">
                        Start Shopping
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection