@extends('user.header')

@section('title', 'Order Details - DailyBrew')

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

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.order') }}" class="text-decoration-none">My Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
                    </ol>
                </nav>
                <a href="{{ route('user.order') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
            <h2 class="fw-bold mb-0">Order Details</h2>
            <p class="text-muted">Order #{{ $order->order_number }} placed on {{ $order->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Summary Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Order Summary</h5>
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
                            @if($order->payment_url)
                                <a href="{{ $order->payment_url }}" class="btn btn-primary" target="_blank">
                                    <i class="fas fa-credit-card me-2"></i>Continue Payment
                                </a>
                            @endif
                            <form action="{{ route('user.check-status', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info w-100">
                                    <i class="fas fa-sync-alt me-2"></i>Check Payment Status
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($order->status, ['pending', 'processing']))
                            <form action="{{ route('user.cancel-order', $order->id) }}" method="POST" 
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

            <!-- Shipping Address Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Shipping Address</h5>
                </div>
                <div class="card-body p-4">                    
                    <div class="mb-0">
                        <h6 class="fw-bold">Name: {{ $order->user->name }}</h6>
                        @if($order->user->email)
                            <p class="mb-1">Email: {{ $order->user->email }}</p>
                        @endif
                        <p class="mb-0">Address: {{ $order->delivery_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Order Items</h5>
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
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
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
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Need Help?</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center">
                        <p class="mb-4">If you have any questions or concerns about your order, please contact our customer support team.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="mailto:support@dailybrew.com" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-envelope me-2"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection