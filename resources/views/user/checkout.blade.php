@extends('user.header')

@section('title', 'Checkout - DailyBrew')

@section('content')
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

<div class="container-fluid mt-5 mb-5" style="padding-left: 10%; padding-right: 10%;">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Checkout</h1>
        </div>
    </div>
    
    @if($carts->count() > 0)
    <form action="{{ route('payment.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <div class="col-lg-8 mb-4">
                <!-- Order Items -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-4">Product</th>
                                        <th class="py-3 text-center">Quantity</th>
                                        <th class="py-3 text-end pe-4">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carts as $cart)
                                    <tr>
                                        <td class="py-3 ps-4">
                                            <div class="d-flex align-items-start">
                                                @if($cart->beverage->image)
                                                    <img src="{{ asset('storage/' . $cart->beverage->image) }}" 
                                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" 
                                                         alt="{{ $cart->beverage->name }}">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{ $cart->beverage->name }}</h6>
                                                    <p class="text-muted mb-0 small">{{ $cart->beverage->brand->name ?? 'Unknown Brand' }}</p>
                                                    @if($cart->note)
                                                        <div class="mt-2">
                                                            <div class="d-flex align-items-start">
                                                                <i class="fas fa-sticky-note text-info me-2 mt-1" style="font-size: 12px;"></i>
                                                                <div>
                                                                    <small class="text-info fw-medium">Special Instructions:</small>
                                                                    <small class="text-muted d-block">{{ $cart->note }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">{{ $cart->quantity }}</td>
                                        <td class="py-3 text-end pe-4">Rp {{ number_format($cart->beverage->price * $cart->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Shipping Address</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="shipping_address" class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" 
                                          placeholder="Enter your complete shipping address" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
               
                @php
                    $itemsWithNotes = $carts->filter(function($cart) {
                        return !empty($cart->note);
                    });
                @endphp
                
                
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3" style="top: 20px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Order Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Order Summary Details -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        
                       
                        
                        <hr>
                       
                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-4" id="totalAmount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <!-- Payment Button -->
                        <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold">
                            Proceed to Payment
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">By clicking 'Proceed to Payment', you agree to our <a href="#" class="text-decoration-none">Terms & Conditions</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
    <!-- Empty Cart -->
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
        <h3>Your cart is empty</h3>
        <p class="text-muted mb-4">Add some items to your cart before checkout.</p>
        <a href="{{ route('user.shop') }}" class="btn btn-primary px-4 py-2">Start Shopping</a>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const shippingAddress = document.getElementById('shipping_address').value.trim();
    
    if (!shippingAddress) {
        e.preventDefault();
        alert('Please enter your shipping address');
        document.getElementById('shipping_address').focus();
        return;
    }
});
</script>
@endsection