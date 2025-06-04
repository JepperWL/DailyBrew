@extends('admin.header')

@section('title', 'Dashboard - DailyBrew')

@section('content')
<div class="container py-4">

     <!-- Alert -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center p-3 mb-4 shadow rounded-3" role="alert">
        <i class="fas fa-check-circle me-2 fs-5 text-success"></i>
        <div class="fw-semibold text-success flex-grow-1">
          {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center p-3 mb-4 shadow rounded-3" role="alert">
        <i class="fas fa-exclamation-circle me-2 fs-5 text-danger"></i>
        <div class="fw-semibold text-danger flex-grow-1">
          {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-0"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Overview</h2>
            <p class="text-muted">Welcome to DailyBrew admin dashboard</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-5 g-4">
        <div class="col-md-4">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Total Sales</h4>
                    <i class="fas fa-money-bill-wave fs-4 text-success"></i>
                </div>
                <p class="fs-3 mb-0 fw-bold text-success">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</p>
                <small class="text-muted">All time sales</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Transactions</h4>
                    <i class="fas fa-receipt fs-4 text-primary"></i>
                </div>
                <p class="fs-3 mb-0 fw-bold text-primary">{{ $totalTransactions ?? 0 }}</p>
                <small class="text-muted">Total orders processed</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Products</h4>
                    <i class="fas fa-mug-hot fs-4 text-warning"></i>
                </div>
                <p class="fs-3 mb-0 fw-bold text-warning">{{ $beverages->total() }}</p>
                <small class="text-muted">Active products</small>
            </div>
        </div>
    </div>

    <!-- Product Table -->
    <div class="bg-white rounded-3 p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0"><i class="fas fa-coffee me-2 text-warning"></i>Products</h3>
            <a href="{{ route('beverage.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Add New Product
            </a>
        </div>
        
        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex">
                    <div class="input-group">
                        <span class="input-group-text  border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Search products by name, description, brand, or category..." 
                               value="{{ request('search') }}" autocomplete="off">
                        @if(request('search'))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" title="Clear search">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
            
            @if(request('search'))
            <div class="col-md-4 d-flex align-items-center">
                <div class="alert alert-info mb-0 py-2 px-3 flex-grow-1">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Found {{ $beverages->total() }} results for "<strong>{{ request('search') }}</strong>"
                    </small>
                </div>
            </div>
            @endif
        </div>
        
        @if($beverages->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="35%" class="fw-semibold">Product</th>
                        <th width="12%" class="fw-semibold text-center">Category</th>
                        <th width="12%" class="fw-semibold text-center">Brand</th>
                        <th width="13%" class="fw-semibold text-center">Price</th>
                        <th width="10%" class="fw-semibold text-center">Status</th>
                        <th width="13%" class="text-center fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beverages as $index => $beverage)
                    <tr class="{{ !$beverage->is_available ? 'table-secondary' : '' }}">
                        <td class="text-center fw-semibold text-muted">
                            {{ ($beverages->currentPage() - 1) * $beverages->perPage() + $index + 1 }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($beverage->image)
                                    <img src="{{ asset('storage/' . $beverage->image) }}" alt="{{ $beverage->name }}" 
                                         class="rounded-2 me-3" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class=" rounded-2 me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-semibold {{ !$beverage->is_available ? 'text-muted' : '' }}">
                                        {{ $beverage->name }}
                                    </h6>
                                    <small class="text-muted">{{ Str::limit($beverage->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center fw-semibold">
                            <span class="badge  text-dark">{{ $beverage->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="text-center fw-semibold">
                            <span class="badge  text-dark">{{ $beverage->brand->name ?? 'N/A' }}</span>
                        </td>
                        <td class="text-center fw-semibold {{ !$beverage->is_available ? 'text-muted' : 'text-success' }}">
                            Rp {{ number_format($beverage->price, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($beverage->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a href="{{ route('beverage.show', $beverage->id) }}" 
                                   class="btn btn-sm btn-outline-info border-0" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('beverage.edit', $beverage->id) }}" 
                                   class="btn btn-sm btn-outline-primary border-0" title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('beverage.destroy', $beverage->id) }}" 
                                      style="display: inline;" onsubmit="return confirmDelete('{{ $beverage->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Delete Product">
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
        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            @if(request('search'))
                <i class="fas fa-search fs-1 text-muted mb-3"></i>
                <h5>No products found</h5>
                <p class="text-muted">No products match your search criteria for "<strong>{{ request('search') }}</strong>"</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Clear Search
                </a>
                <a href="{{ route('beverage.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                </a>
            @else
                <i class="fas fa-coffee fs-1 text-muted mb-3"></i>
                <h5>No products available</h5>
                <p class="text-muted">Start by adding your first product to get started</p>
                <a href="{{ route('beverage.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                </a>
            @endif
        </div>
        @endif
    </div>
    
    <!-- Pagination -->
    @if($beverages->hasPages())
    <div class="card-footer bg-white py-3">
        <div class="d-flex justify-content-between align-items-center ms-4 me-4">
            <div class="text-muted small">
                Showing {{ $beverages->firstItem() }} to {{ $beverages->lastItem() }} of {{ $beverages->total() }} results
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
            </div>
            <nav aria-label="Product pagination">
                {{ $beverages->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
    @endif
</div>

<script>
   
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
       
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.value) {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    });

     
    function confirmDelete(productName) {
        return confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`);
    }
    
  
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        const searchForm = document.querySelector('form[action*="dashboard"]');
        
        if (searchInput && searchForm) {
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        }
    });
</script>

@endsection