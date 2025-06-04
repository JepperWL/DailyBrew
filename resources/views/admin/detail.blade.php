@extends('admin.header')

@section('title', 'Product Detail - DailyBrew')

@section('content')
<div class=""></div> 
<div class="container-fluid mt-4 mb-5" style="padding-left: 10%; padding-right: 10%;">

    <!-- Breadcrumb Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product Detail</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Product Detail Card -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-5 mb-4 mb-md-0">
                            <div class="position-relative">
                                @if($beverage->image)
                                    <img src="{{ asset('storage/' . $beverage->image) }}" class="card-img-top rounded-3" alt="{{ $beverage->name }}" style="width: 100%; height: 300px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 100%; height: 300px;">
                                        <i class="fas fa-image text-muted fa-3x"></i>
                                    </div>
                                @endif
                                
                                <!-- Category Badge -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                        {{ $beverage->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>

                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if($beverage->is_available == true)
                                        <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h2 class="fw-bold mb-1">{{ $beverage->name }}</h2>
                                    <p class="text-muted mb-0">Brand: <strong>{{ $beverage->brand->name ?? 'Unknown' }}</strong></p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('beverage.edit', $beverage->id) }}">
                                            <i class="fas fa-edit me-2"></i>Edit Product
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('beverage.destroy', $beverage->id) }}" style="display: inline;" onsubmit="return confirmDelete('{{ $beverage->name }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>Delete Product
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <h3 class="fw-bold text-success fs-2 mb-0">Rp {{ number_format($beverage->price, 0, ',', '.') }}</h3>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-2">Description</h5>
                                <p class="text-muted">{{ $beverage->description }}</p>
                            </div>

                            

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 mt-4">
                               
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(productName) {
        return confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`);
    }

     
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