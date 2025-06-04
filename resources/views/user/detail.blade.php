@extends('user.header')

@section('title', 'Beverage Detail - DailyBrew')

@section('content')
<div class="py-2"></div> 
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

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.shop') }}" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $beverage->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Detail Card -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-5 mb-4 mb-md-0">
                            <div class="position-relative">
                                @if($beverage->image)
                                    <img src="{{ asset('storage/' . $beverage->image) }}" 
                                         class="card-img-top rounded-3" alt="{{ $beverage->name }}"
                                         style="height: 400px; object-fit: cover; width: 100%;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                         style="height: 400px;">
                                        <i class="fas fa-image text-muted fa-3x"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">
                                        {{ $beverage->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                                
                                <!-- Availability Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if($beverage->is_available)
                                        <span class="badge bg-success rounded-pill px-3 py-2">Available</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h2 class="fw-bold">{{ $beverage->name }}</h2>
                                
                                <!-- Wishlist Form -->
                                <form method="POST" action="{{ route('wishlist.store') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                                    <button type="submit" class="btn btn-link text-danger fs-4 p-0 border-0" title="Add to Wishlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>

                            <p class="text-muted mb-3 text-uppercase fw-bold">{{ $beverage->brand->name ?? 'Unknown Brand' }}</p>

                            <div class="mb-3">
                                <h3 class="fw-bold text-primary-emphasis fs-3 mb-4">
                                    Rp {{ number_format($beverage->price, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold mb-2">Description</h5>
                                <p class="text-muted">{{ $beverage->description }}</p>
                            </div>

                            <!-- Add to Cart Button Only -->
                            @if($beverage->is_available)
                            <form method="POST" action="{{ route('cart.store') }}" class="mt-4">
                                @csrf
                                <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                                <input type="hidden" name="quantity" value="1">
                                
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 fs-5" id="addToCartBtn">
                                    <i class="fa fa-shopping-bag me-2"></i> Add to Cart
                                </button>
                            </form>
                            @else
                            <div class="mt-4">
                                <button class="btn btn-secondary rounded-pill px-5 py-3 fw-bold w-100 fs-5" disabled>
                                    <i class="fas fa-times me-2"></i> Out of Stock
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations Column -->
        <div class="col-lg-4 col-md-12">
            <h4 class="fw-bold mb-3">Recommended for You</h4>

            @forelse($recommendedProducts as $product)
            <!-- Recommended Product Card -->
            <div class="card border-0 bg-coffee-lightest mb-4 p-3 position-relative card-hover-animation">
                <div class="row g-0">
                    <div class="col-4">
                        <div class="position-relative">
                            <a href="{{ route('user.detail', $product->id) }}" class="text-decoration-none">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="card-img-top rounded-3" alt="{{ $product->name }}"
                                         style="height: 150px; object-fit: cover; width: 100%;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                         style="height: 100px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="position-absolute top-0 start-0 m-1">
                                <span class="badge bg-primary rounded-pill px-2 py-1 small">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card-body p-2">
                            <h6 class="card-title fw-bold mb-1">{{ Str::limit($product->name, 20) }}</h6>
                            <p class="card-text small mb-1 text-muted">{{ $product->brand->name ?? 'Unknown' }}</p>
                            <p class="fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <div class="d-flex gap-3 px-1">
                                <!-- Add to Cart -->
                                <form method="POST" action="{{ route('cart.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="beverage_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Cart">
                                        <i class="fa fa-shopping-bag"></i>
                                    </button>
                                </form>
                                
                                <!-- Add to Wishlist -->
                                <form method="POST" action="{{ route('wishlist.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="beverage_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Wishlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- No Recommended Products -->
            <div class="card border-0 bg-light p-4 text-center">
                <i class="fas fa-coffee fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">No recommendations available</p>
                <a href="{{ route('user.shop') }}" class="btn btn-outline-primary btn-sm mt-2">Browse All Products</a>
            </div>
            @endforelse

            <!-- Browse All Products Button -->
            @if($recommendedProducts->count() > 0)
            <div class="text-center mt-3">
                <a href="{{ route('user.shop') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-th me-2"></i>Browse All Products
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
   
    const addToCartForm = document.querySelector('form[action*="cart"]');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            const button = document.getElementById('addToCartBtn');
            const originalContent = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding...';
            button.disabled = true;
            
          
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check me-2"></i> Added to Cart';
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
                
                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                    button.disabled = false;
                }, 1500);
            }, 500);
        });
    }

 
    const wishlistForms = document.querySelectorAll('form[action*="wishlist"]');
    wishlistForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button');
            const icon = button.querySelector('i');
            
            icon.className = 'fas fa-spinner fa-spin';
            button.disabled = true;
            
            setTimeout(() => {
                icon.className = 'fas fa-heart text-danger';
                button.disabled = false;
            }, 1000);
        });
    });

  
    const recommendedCards = document.querySelectorAll('.card-hover-animation');
    recommendedCards.forEach(card => {
        card.addEventListener('click', function(e) {
     
            if (!e.target.closest('form')) {
                const link = this.querySelector('a[href*="detail"]');
                if (link) {
                    window.location.href = link.href;
                }
            }
        });
    });

  
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