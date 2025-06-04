@extends('user.header')

@section('title', 'Shopping Cart - DailyBrew')

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

    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Shopping Cart</h1>
            <p class="text-muted">{{ $carts->count() }} item(s) in your cart</p>
        </div>
    </div>
    
    <!-- Cart Content -->
    <div class="row">
        <div class="col-12">
            @if($carts->count() > 0)
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <!-- Cart Items List -->
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4" style="width: 35%;">Product</th>
                                    <th class="py-3 text-center" style="width: 15%;">Price</th>
                                    <th class="py-3 text-center" style="width: 15%;">Quantity</th>
                                    <th class="py-3 text-center" style="width: 15%;">Total</th>
                                    <th class="py-3 text-center" style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $cart)
                                <tr>
                                    <td class="py-3 ps-4">
                                        <div class="d-flex align-items-start">
                                            @if($cart->beverage->image)
                                                <img src="{{ asset('storage/' . $cart->beverage->image) }}" alt="{{ $cart->beverage->name }}" 
                                                     class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-1">{{ $cart->beverage->name }}</h5>
                                                <p class="text-muted mb-1 small">{{ $cart->beverage->brand->name ?? 'Unknown Brand' }}</p>
                                                <p class="text-muted mb-2 small">{{ Str::limit($cart->beverage->description, 50) }}</p>
                                                
                                                <!-- Note Section -->
                                                <div class="mt-2">
                                                    <form method="POST" action="{{ route('cart.update', $cart->id) }}" id="note-form-{{ $cart->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="quantity" value="{{ $cart->quantity }}">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="fas fa-sticky-note text-muted"></i>
                                                            </span>
                                                            <input type="text" 
                                                                   name="note" 
                                                                   class="form-control border-start-0" 
                                                                   placeholder="Add special instructions..." 
                                                                   value="{{ $cart->note }}"
                                                                   maxlength="500"
                                                                   onchange="updateNote({{ $cart->id }})">
                                                        </div>
                                                    </form>
                                                    @if($cart->note)
                                                        <small class="text-info mt-1 d-block">
                                                            <i class="fas fa-info-circle me-1"></i>{{ $cart->note }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">Rp {{ number_format($cart->beverage->price, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        <div class="d-flex justify-content-center">
                                            <form method="POST" action="{{ route('cart.update', $cart->id) }}" class="d-flex" id="quantity-form-{{ $cart->id }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="note" value="{{ $cart->note }}">
                                                <div class="input-group" style="width: 140px;">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity({{ $cart->id }})">-</button>
                                                    <input type="number" name="quantity" class="form-control text-center" 
                                                           value="{{ $cart->quantity }}" min="1" max="10" 
                                                           id="quantity-{{ $cart->id }}" onchange="updateQuantity({{ $cart->id }})">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity({{ $cart->id }})">+</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center fw-bold">Rp {{ number_format($cart->beverage->price * $cart->quantity, 0, ',', '.') }}</td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                          
                                            
                                            <!-- Remove Item Button -->
                                            <form method="POST" action="{{ route('cart.destroy', $cart->id) }}" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to remove this item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" type="submit" title="Remove item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="d-flex justify-content-between align-items-center p-4 bg-light">
                        <div>
                            <a href="{{ route('user.shop') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <span class="me-3">Subtotal ({{ $carts->count() }} items):</span>
                                <span class="fw-bold fs-4">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('user.checkout') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty Cart -->
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('user.shop') }}" class="btn btn-primary px-4 py-2">Start Shopping</a>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
function increaseQuantity(cartId) {
    const input = document.getElementById('quantity-' + cartId);
    const currentValue = parseInt(input.value);
    if (currentValue < 10) {
        input.value = currentValue + 1;
        updateQuantity(cartId);
    }
}

function decreaseQuantity(cartId) {
    const input = document.getElementById('quantity-' + cartId);
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        updateQuantity(cartId);
    }
}

function updateQuantity(cartId) {
    const form = document.getElementById('quantity-form-' + cartId);
    form.submit();
}

function updateNote(cartId) {
    const form = document.getElementById('note-form-' + cartId);
    form.submit();
}

function toggleNoteEdit(cartId) {
   
    const currentQuantity = document.getElementById('quantity-' + cartId).value;
    const currentNote = document.querySelector(`#note-form-${cartId} input[name="note"]`).value;
    
    
}


setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bootstrapAlert = new bootstrap.Alert(alert);
        bootstrapAlert.close();
    });
}, 5000);


</script>
@endsection