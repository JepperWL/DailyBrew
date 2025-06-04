@extends('admin.header')

@section('title', 'Order Details - DailyBrew')

@section('content')
<div class="container py-4">
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.order') }}" class="text-decoration-none">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
                    </ol>
                </nav>
                <a href="{{ route('admin.order') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
            <h2 class="fw-bold mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i>Order Details</h2>
           
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Summary Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Order ID:</span>
                        <span class="fw-bold">#{{ $order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Order Date:</span>
                        <span>{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Status:</span>
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning text-dark">Pending Payment</span>
                                @break
                            @case('paid')
                                <span class="badge bg-success">Paid</span>
                                @break
                            
                            @case('canceled')
                                <span class="badge bg-danger">Canceled</span>
                                @break
                            @case('expired')
                                <span class="badge bg-secondary">Expired</span>
                                @break
                            @case('failed')
                                <span class="badge bg-danger">Failed</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                        @endswitch
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total:</span>
                        <span class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <hr>


                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        @if($order->status === 'pending')
                            <form action="{{ route('admin.order.check-status', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info w-100">
                                    <i class="fas fa-sync-alt me-2"></i>Check Payment Status
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status === 'pending' )
                            <form action="{{ route('admin.order.cancel', $order->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                          
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-times me-2"></i>Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Customer Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-2 me-3">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $order->user->name }}</h6>
                            <p class="text-muted mb-0 small">Customer since {{ $order->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        @if($order->user->phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <span>{{ $order->user->phone }}</span>
                        </div>
                        @endif
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <span>{{ $order->user->email }}</span>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt text-muted me-2 mt-1"></i>
                            <span>{{ $order->delivery_address }}</span>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2 text-primary"></i>Order Items</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col" class="text-center">Quantity</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->beverage->image)
                                                <img src="{{ asset('storage/' . $item->beverage->image) }}" 
                                                     alt="{{ $item->beverage->name }}" class="rounded-3 me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $item->beverage->name }}</h6>
                                                <span class="text-muted small">{{ $item->beverage->brand->name ?? 'Unknown Brand' }}</span>
                                                @if($item->note)
                                                    <div class="mt-1">
                                                        <small class="text-info"><i class="fas fa-sticky-note me-1"></i>{{ $item->note }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->beverage->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->beverage->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-top">
                                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Shipping</td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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