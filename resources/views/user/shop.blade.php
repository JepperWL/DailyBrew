@extends('user.header')

@section('title', 'Shop - DailyBrew')

@section('content')
<div class="container-fluid mt-4 mb-5" style="padding-left: 10%; padding-right: 10%;">
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" id="alertMessage">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('fail'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" id="alertMessage">
            {{ session('fail') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Header and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-8 col-sm-12">
            <h1 class="fw-bold">All Menu</h1>
            @if(request('search'))
                <h5 class="text-muted">Based on search: <strong>"{{ request('search') }}"</strong></h5>
            @endif
            <p class="text-muted">{{ $beverages->total() }} products found</p>
        </div>
        <div class="col-md-4 col-sm-12">
            <!-- Filters Only -->
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle w-100 text-start" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-2"></i>
                            @if(request('brand'))
                                {{ $brands->find(request('brand'))->name ?? 'Filter by Brand' }}
                            @else
                                Filter by Brand
                            @endif
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['brand' => '']) }}">All Brands</a></li>
                            @foreach($brands as $brand)
                            <li>
                                <a class="dropdown-item {{ request('brand') == $brand->id ? 'active' : '' }}" 
                                   href="{{ request()->fullUrlWithQuery(['brand' => $brand->id]) }}">
                                    {{ $brand->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle w-100 text-start" type="button" id="typeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-coffee me-2"></i>
                            @if(request('category'))
                                {{ $categories->find(request('category'))->name ?? 'Filter by Category' }}
                            @else
                                Filter by Category
                            @endif
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="typeDropdown">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['category' => '']) }}">All Categories</a></li>
                            @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item {{ request('category') == $category->id ? 'active' : '' }}" 
                                   href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Cards -->
    <div class="row g-4 bg-white pb-3">
        @forelse($beverages as $beverage)
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 h-100 p-3 card-hover-animation 
                        {{ $beverage->is_available ? 'bg-coffee-lightest' : 'bg-light' }}" 
                 style="{{ !$beverage->is_available ? 'opacity: 0.6; filter: grayscale(20%);' : '' }}">
                <div class="position-relative">
                    <a href="{{ route('user.detail', $beverage->id) }}" class="text-decoration-none">
                        @if($beverage->image)
                            <img src="{{ asset('storage/' . $beverage->image) }}" 
                                 class="card-img-top rounded-3" alt="{{ $beverage->name }}" 
                                 style="height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image text-muted fa-2x"></i>
                            </div>
                        @endif
                    </a>
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">
                            {{ $beverage->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                    
                    <!-- Availability Badge -->
                    @if(!$beverage->is_available)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            Out of Stock
                        </span>
                    </div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.85rem;">
                        {{ $beverage->brand->name ?? 'Unknown' }}
                    </p>
                    <h5 class="card-title fw-bold {{ !$beverage->is_available ? 'text-muted' : '' }}">
                        {{ $beverage->name }}
                    </h5>
                    <div class="d-flex justify-content-between">
                        <p class="card-text {{ !$beverage->is_available ? 'text-muted' : '' }}">
                            {{ Str::limit($beverage->description, 20) }}
                        </p>
                        
                        @if($beverage->is_available)
                            <!-- Add to Cart Form -->
                            <form method="POST" action="{{ route('cart.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                                @csrf
                                <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Cart">
                                    <i class="fa fa-shopping-bag fa-2x"></i>
                                </button>
                            </form>
                        @else
                            <!-- Disabled Cart Button -->
                            <button class="btn btn-link text-muted p-0 border-0" disabled title="Out of Stock">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                            </button>
                        @endif
                    </div>
                    <div class="mt-auto"></div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="fw-bold mb-0 fs-5 {{ !$beverage->is_available ? 'text-muted' : '' }}">
                            Rp {{ number_format($beverage->price, 0, ',', '.') }}
                        </p>
                        
                        <!-- Wishlist Form -->
                        <form method="POST" action="{{ route('wishlist.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                            <button type="submit" class="btn btn-link p-0 border-0 {{ $beverage->is_available ? 'text-black' : 'text-muted' }}" title="Add to Wishlist">
                                <i class="fas fa-heart fa-2x"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5>No products found</h5>
                <p class="text-muted">Try adjusting your search or filters.</p>
                <a href="{{ route('user.shop') }}" class="btn btn-primary">View All Products</a>
            </div>
        </div>
        @endforelse
    </div>
    
    @if($beverages->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Beverage catalog pagination">
                {{ $beverages->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
     
    const cards = document.querySelectorAll('.card-hover-animation');
    cards.forEach(card => {
        card.addEventListener('mousedown', function(e) {
        
            if (!e.target.closest('form')) {
                this.classList.add('active');
            }
        });
        
        card.addEventListener('mouseup', function() {
            this.classList.remove('active');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('active');
        });
        
     
        card.addEventListener('click', function(e) {
            if (!e.target.closest('form')) {
                const detailLink = this.querySelector('a[href*="detail"]');
                if (detailLink) {
                    window.location.href = detailLink.href;
                }
            }
        });
    });

    
    const cartForms = document.querySelectorAll('form[action*="cart"]');
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button');
            const icon = button.querySelector('i');
            
         
            icon.className = 'fas fa-spinner fa-spin fa-2x';
            button.disabled = true;
            
         
            setTimeout(() => {
                icon.className = 'fa fa-shopping-bag fa-2x';
                button.disabled = false;
            }, 2000);
        });
    });

    const wishlistForms = document.querySelectorAll('form[action*="wishlist"]');
    wishlistForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button');
            const icon = button.querySelector('i');
            
           
            icon.className = 'fas fa-spinner fa-spin fa-2x';
            button.disabled = true;
       
            setTimeout(() => {
                icon.className = 'fas fa-heart fa-2x';
                button.disabled = false;
            }, 2000);
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