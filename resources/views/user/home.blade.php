@extends('user.header')

@section('title', 'Home - DailyBrew')

@section('content')

 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container-fluid mt-4 mb-5" style="padding-left: 10%; padding-right: 10%;">

     
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" id="alertMessage">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" id="alertMessage">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Banner -->
    <div class="row">
        <div class="col-12">
            <div class="banner rounded-4 p-5 mb-5"
                style="background: url('{{ asset('storage/bg2.jpg') }}') center/cover no-repeat;">
                <div class="p-4 text-center">
                    <h2 class="display-4 fw-bold text-white" style="font-family:'Poppins'">Start with Coffee, End with Peace</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Brand Carousel -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="text-center mb-4 fw-bold">Trusted by Coffee Lovers Everywhere</h1>
            
            <div class="bg-white rounded-3 shadow-sm py-4">
                <div id="brandCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        @if($brands->count() > 0)
                            @foreach($brands->chunk(4) as $index => $brandChunk)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="row justify-content-around align-items-center px-4">
                                    @foreach($brandChunk as $brand)
                                    <div class="col-6 col-md-3 text-center my-2">
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" 
                                                 class="img-fluid opacity-75 px-3" style="max-height: 60px;">
                                        @else
                                            <div class="bg-light rounded p-3" style="height: 60px; line-height: 34px;">
                                                <span class="fw-bold">{{ $brand->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="text-center py-4">
                                    <p class="text-muted">No brands available yet</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if($brands->count() > 4)
                    <button class="carousel-control-prev" type="button" data-bs-target="#brandCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark bg-opacity-25 rounded-circle p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#brandCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark bg-opacity-25 rounded-circle p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Recommendations -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Today's Recommendations</h1>
        </div>
    </div>

    <!-- Product Cards - Today's Recommendations -->
    <div class="row g-4 bg-white pb-3">
        @forelse($todaysRecommendations as $beverage)
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <a href="{{ route('user.detail', $beverage->id) }}" class="text-decoration-none text-black">
                <div class="card border-0 bg-coffee-lightest h-100 p-3 card-hover-animation">
                    <div class="position-relative">
                        @if($beverage->image)
                            <img src="{{ asset('storage/' . $beverage->image) }}" class="card-img-top rounded-3" alt="{{ $beverage->name }}" style="height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image text-muted fa-2x"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">{{ $beverage->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.85rem;">{{ $beverage->brand->name ?? 'Unknown' }}</p>
                        <h5 class="card-title fw-bold">{{ $beverage->name }}</h5>
                        <div class="d-flex justify-content-between">
                            <p class="card-text">{{ Str::limit($beverage->description, 20) }}</p>
                             <form method="POST" action="{{ route('cart.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Cart">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                            </button>
                        </form>
                        </div>
                        <div class="mt-auto"></div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="fw-bold mb-0 fs-5">Rp {{ number_format($beverage->price, 0, ',', '.') }}</p>
                             <form method="POST" action="{{ route('wishlist.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                            <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Wishlist">
                                <i class="fas fa-heart fa-2x"></i>
                            </button>
                        </form>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-coffee fa-3x text-muted mb-3"></i>
                <h5>No products available</h5>
                <p class="text-muted">Check back later for new recommendations!</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Find Your Best Coffee Section -->
    <div class="row mt-5 mb-4">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Find Your Best Coffee!</h1>
            <a href="{{ route('user.shop') }}" class="btn btn-primary rounded-pill fw-bold fs-5 ms-4">View All</a>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="row g-4 bg-white pb-3">
        @forelse($featuredProducts as $beverage)
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <a href="{{ route('user.detail', $beverage->id) }}" class="text-decoration-none text-black">
                <div class="card border-0 bg-coffee-lightest h-100 p-3 card-hover-animation">
                    <div class="position-relative">
                        @if($beverage->image)
                            <img src="{{ asset('storage/' . $beverage->image) }}" class="card-img-top rounded-3" alt="{{ $beverage->name }}" style="height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image text-muted fa-2x"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">{{ $beverage->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.85rem;">{{ $beverage->brand->name ?? 'Unknown' }}</p>
                        <h5 class="card-title fw-bold">{{ $beverage->name }}</h5>
                        <div class="d-flex justify-content-between">
                            <p class="card-text">{{ Str::limit($beverage->description, 20) }}</p>
                             <form method="POST" action="{{ route('cart.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Cart">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                            </button>
                        </form>
                        </div>
                        <div class="mt-auto"></div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="fw-bold mb-0 fs-5">Rp {{ number_format($beverage->price, 0, ',', '.') }}</p>
                            <form method="POST" action="{{ route('wishlist.store') }}" class="d-inline" onsubmit="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="beverage_id" value="{{ $beverage->id }}">
                            <button type="submit" class="btn btn-link text-black p-0 border-0" title="Add to Wishlist">
                                <i class="fas fa-heart fa-2x"></i>
                            </button>
                        </form>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-coffee fa-3x text-muted mb-3"></i>
                <h5>No featured products available</h5>
                <p class="text-muted">Check back later for new products!</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card-hover-animation');
    cards.forEach(card => {
        card.addEventListener('mousedown', () => card.classList.add('active'));
        card.addEventListener('mouseup', () => card.classList.remove('active'));
        card.addEventListener('mouseleave', () => card.classList.remove('active'));
        card.addEventListener('click', function() {
            const parentAnchor = this.closest('a');
            if (parentAnchor && parentAnchor.href) {
                window.location.href = parentAnchor.href;
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