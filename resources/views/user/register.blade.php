<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - DailyBrew</title>

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
      
      <div class="col-12 d-flex justify-content-center align-items-center py-5">
        <div class="card bg-deep-green text-white rounded-4 shadow p-4 p-md-5 my-5 w-100 border-start border-warning border-4" style="max-width: 520px;">
          
          <!-- Logo dan Judul -->
          <div class="text-center mb-4">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="mb-3" style="height: 90px;" />
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
              <i class="fas fa-user-plus fa-2x text-warning"></i>
              <h2 class="fw-bold text-warning mb-0">Register Account</h2>
            </div>
            <p class="text-white-50">Create a new account to access the web</p>
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
          <form method="POST" action="{{ route('user.register') }}">
            @csrf

            <!-- Name Field -->
            <div class="mb-3">
              <label for="name" class="form-label text-warning fw-semibold">
                <i class="fas fa-user me-2"></i>Your Name
              </label>
              <div class="input-group">
                <span class="input-group-text border border-light bg-transparent text-warning">
                  <i class="fas fa-id-card"></i>
                </span>
                <input type="text" class="form-control rounded-end bg-transparent border border-light text-white @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
              </div>
              @error('name')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Email Field -->
            <div class="mb-3">
              <label for="email" class="form-label text-warning fw-semibold">
                <i class="fas fa-envelope me-2"></i>Email
              </label>
              <div class="input-group">
                <span class="input-group-text border border-light bg-transparent text-warning">
                  <i class="fas fa-at"></i>
                </span>
                <input type="email" class="form-control rounded-end bg-transparent border border-light text-white @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
              </div>
              @error('email')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-3">
              <label for="password" class="form-label text-warning fw-semibold">
                <i class="fas fa-lock me-2"></i>Password
              </label>
              <div class="input-group position-relative">
                <span class="input-group-text border border-light bg-transparent text-warning">
                  <i class="fas fa-key"></i>
                </span>
                <input type="password" class="form-control bg-transparent border border-light text-white @error('password') is-invalid @enderror" 
                       id="password" name="password" required placeholder="Enter your password">
                <button class="btn btn-outline-light border-0 position-absolute end-0 top-0 h-100" type="button" id="toggle-password" style="z-index: 10;">
                  <i class="fas fa-eye-slash text-warning" id="eye-icon"></i>
                </button>
              </div>
              @error('password')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-4">
              <label for="password_confirmation" class="form-label text-warning fw-semibold">
                <i class="fas fa-lock me-2"></i>Confirm Password
              </label>
              <div class="input-group position-relative">
                <span class="input-group-text border border-light bg-transparent text-warning">
                  <i class="fas fa-check-double"></i>
                </span>
                <input type="password" class="form-control bg-transparent border border-light text-white @error('password_confirmation') is-invalid @enderror" 
                       id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                <button class="btn btn-outline-light border-0 position-absolute end-0 top-0 h-100" type="button" id="toggle-confirmation" style="z-index: 10;">
                  <i class="fas fa-eye-slash text-warning" id="eye-icon-confirmation"></i>
                </button>
              </div>
              @error('password_confirmation')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Terms and Conditions -->
            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
              <label class="form-check-label text-white-50" for="terms">
                I agree to the <a href="#" class="text-warning text-decoration-none">Terms and Conditions</a>
              </label>
              @error('terms')
              <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success text-warning fw-bold w-100 rounded-pill py-3 mb-3">
              <i class="fas fa-user-plus me-2"></i>Register
            </button>
            
            <!-- Login Link -->
            <div class="text-center mt-4">
              <p class="text-white-50">
                Already have an account? <a href="{{ route('user.login') }}" class="text-warning text-decoration-none fw-semibold">Login Here</a>
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
    document.addEventListener('DOMContentLoaded', function() {
  
      const togglePassword = document.getElementById('toggle-password');
      const passwordField = document.getElementById('password');
      const eyeIcon = document.getElementById('eye-icon');
      
      togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        if (type === 'password') {
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash');
        } else {
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye');
        }
      });
      
    
      const toggleConfirmation = document.getElementById('toggle-confirmation');
      const confirmField = document.getElementById('password_confirmation');
      const eyeIconConfirm = document.getElementById('eye-icon-confirmation');
      
      toggleConfirmation.addEventListener('click', function() {
        const type = confirmField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmField.setAttribute('type', type);
        
        if (type === 'password') {
          eyeIconConfirm.classList.remove('fa-eye');
          eyeIconConfirm.classList.add('fa-eye-slash');
        } else {
          eyeIconConfirm.classList.remove('fa-eye-slash');
          eyeIconConfirm.classList.add('fa-eye');
        }
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
</body>
</html>