@extends('admin.header')

@section('title', 'Add New Product - DailyBrew')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Product</li>
                    </ol>
                </nav>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
            <h2 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Product</h2>
            <p class="text-muted">Create a new coffee product for your store</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white rounded-3 p-4 shadow-sm">
        <form method="POST" action="{{ route('beverage.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            
            <div class="row mb-4 g-4">
                <!-- Left Column  -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-coffee me-2 text-warning"></i>Product Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-3">
                                <!-- Product Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Product Name</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="Enter product name" required>
                                        <div class="invalid-feedback">Please provide a product name.</div>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Brand -->
                                <div class="col-md-6">
                                    <label for="brand_id" class="form-label fw-semibold">Brand</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                                        <select class="form-select @error('brand_id') is-invalid @enderror" 
                                                id="brand_id" name="brand_id" required>
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a brand.</div>
                                    </div>
                                    @error('brand_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Price -->
                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-semibold">Price (Rp)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-light"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" 
                                               placeholder="0" min="0" required>
                                        <div class="invalid-feedback">Please provide a valid price.</div>
                                    </div>
                                    @error('price')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Category -->
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label fw-semibold">Category</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-light"><i class="fas fa-list"></i></span>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a category.</div>
                                    </div>
                                    @error('category_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <!-- Description -->
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" 
                                          placeholder="Describe your product..." required>{{ old('description') }}</textarea>
                                <div class="form-text">Provide a detailed description of the product.</div>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column  -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-image me-2 text-success"></i>Product Image</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 text-center">
                                <div class="mb-3 position-relative">
                                    <div class="bg-light rounded-3 py-5 px-3 mb-3 text-center d-flex align-items-center justify-content-center" 
                                         style="min-height: 200px;" id="image-preview">
                                        <div>
                                            <i class="fas fa-cloud-upload-alt text-secondary fa-3x mb-3"></i>
                                            <p class="mb-0 text-muted">Preview will appear here</p>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*" required>
                                    <div class="invalid-feedback">Please select a product image.</div>
                                    @error('image')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Upload a high-quality image (JPEG, PNG). Max size: 2MB.</div>
                            </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('image-preview');
                preview.innerHTML = `<img src="${event.target.result}" class="img-fluid rounded-3" style="max-height: 200px; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        }
    });
    
   
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

     
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bootstrapAlert = new bootstrap.Alert(alert);
            bootstrapAlert.close();
        });
    }, 8000);
</script>
@endsection