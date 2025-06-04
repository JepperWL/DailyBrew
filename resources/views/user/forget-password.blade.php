<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forget Password - DailyBrew</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

  <div class="container-fluid p-0">
    <div class="row g-0 min-vh-100" style="background: url('{{ asset('storage/admin-login-bg.jpg') }}') no-repeat center center fixed; background-size: cover;">
      
      <div class="col-12 d-flex justify-content-center align-items-center pt-5">
        <div class="card text-white bg-deep-green rounded-4 shadow p-4 p-md-5 my-5 w-100 border-start border-warning border-4" style="max-width: 520px; min-height: 500px;">
          
          <!-- Logo dan Judul -->
          <div class="text-center mb-4">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="mb-3" style="height: 90px;" />
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
              <i class="fas fa-key fa-2x text-warning"></i>
              <h2 class="fw-bold text-warning mb-0">Forget Password</h2>
            </div>
            <p class="text-white-50">Input your email to verify your account</p>
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
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif


          <!-- Form -->
          <form method="POST" action="{{ route('user.forget-password.submit') }}">
            @csrf

            <div class="mb-4">
              <label for="email" class="form-label text-warning fw-semibold">
                <i class="fas fa-envelope text-warning me-2"></i>Email Address
              </label>
              <div class="input-group">
                <span class="input-group-text border border-light bg-transparent text-warning">
                  <i class="fas fa-at"></i>
                </span>
                <input type="email" class="form-control rounded-end bg-transparent border border-light text-white @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autofocus>
              </div>
              @error('email')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-success text-warning fw-bold w-100 rounded-pill py-3 mb-3">
              <i class="fas fa-paper-plane me-2"></i>Verify Email
            </button>
            
            <div class="text-center mt-4">
              <p class="text-white-50">
                <a href="{{ route('user.login') }}" class="text-warning text-decoration-none">
                  <i class="fas fa-arrow-left text-warning me-1"></i> Back to Login
                </a>
              </p>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bootstrapAlert = new bootstrap.Alert(alert);
        bootstrapAlert.close();
      });
    }, 5000);
  </script>
</body>
</html>