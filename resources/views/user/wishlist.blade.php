@extends('user.header')

@section('title', 'Wishlist - DailyBrew')

@section('content')
<div class="container-fluid mt-4 mb-5" style="padding-left: 10%; padding-right: 10%;">
    
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">My Wishlist</h1>
            <p class="text-muted">{{ $wishlists->count() }} item(s) in your wishlist</p>
        </div>
    </div>

    @if($wishlists->count() > 0)
    <!-- Wishlist Items -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @foreach($wishlists as $wishlist)
                    <!-- Wishlist Item -->
                    <div class="row align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="col-md-2 col-sm-3 mb-3 mb-md-0">
                            @if($wishlist->beverage->image)
                                <img src="{{ asset('storage/' . $wishlist->beverage->image) }}" 
                                     class="img-fluid rounded-3" alt="{{ $wishlist->beverage->name }}" 
                                     style="max-height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                     style="height: 100px;">
                                    <i class="fas fa-image text-muted fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-9 mb-3 mb-md-0">
                            <h5 class="fw-bold mb-1">{{ $wishlist->beverage->name }}</h5>
                            <p class="text-muted mb-1">{{ $wishlist->beverage->brand->name ?? 'Unknown Brand' }}</p>
                            <p class="text-muted mb-0 small">{{ Str::limit($wishlist->beverage->description, 60) }}</p>
                        </div>
                        <div class="col-md-2 col-sm-4 text-md-center mb-3 mb-md-0">
                            <span class="fw-bold fs-5">Rp {{ number_format($wishlist->beverage->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="col-md-4 col-sm-8 text-end">
                            <!-- Add to Cart Form -->
                            <form method="POST" action="{{ route('cart.store') }}" style="display: inline;">
                                @csrf
                                <input type="hidden" name="beverage_id" value="{{ $wishlist->beverage->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                </button>
                            </form>
                            
                            <!-- Remove from Wishlist Form -->
                            <form method="POST" action="{{ route('wishlist.destroy', $wishlist->id) }}" 
                                  style="display: inline;" onsubmit="return confirm('Remove this item from wishlist?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Wishlist Message -->
    <div class="row mt-4">
        <div class="col-12 text-center py-5">
            <i class="fas fa-heart-broken fa-4x text-muted mb-4"></i>
            <h3>Your wishlist is empty</h3>
            <p class="text-muted mb-4">Explore our products and add items you love to your wishlist</p>
            <a href="{{ route('user.shop') }}" class="btn btn-primary px-4 py-2">Browse Products</a>
        </div>
    </div>
    @endif

    <!-- Continue Shopping Button -->
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('user.shop') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-dismiss alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bootstrapAlert = new bootstrap.Alert(alert);
        bootstrapAlert.close();
    });
}, 5000);
</script>
@endsection