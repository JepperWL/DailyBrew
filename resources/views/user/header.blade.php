<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'DailyBrew')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Fredoka:wght@300..700&family=Gabarito:wght@400..900&display=swap" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body class="bg-coffee-light" style="font-family:'poppins'">
   
<header class="bg-dark-green py-1 px-1 shadow-sm sticky-top">
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            
            <!-- Left Section-->
            <div class="d-flex align-items-center flex-grow-1 flex-md-grow-0">
                <!-- Mobile Menu Button -->
                <button class="navbar-toggler d-md-none border-0 text-white me-2" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                
                <!-- Logo -->
                <a href="{{ route('user.home') }}" class="me-2 me-md-3">
                    <img src="{{ asset('storage/logo.png') }}" alt="Logo" height="80" class="d-none d-sm-block">
                    <img src="{{ asset('storage/logo.png') }}" alt="Logo" height="60" class="d-block d-sm-none">
                </a>
                
                <!-- Search Bar (desktop only) -->
                <div class="d-flex align-items-center">
                    <form action="{{ route('user.shop') }}" method="GET" class="flex-grow-1 me-2">
                        <div class="input-group w-100">
                            <input type="text" name="search" class="form-control rounded-start" placeholder="Search..." value="{{ request('search') }}">
                            <button class="btn   bg-white rounded-end" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    @if(request('search'))
                        <a href="{{ route('user.shop') }}" class="btn bg-white ">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>


            </div>
          
            <div class="d-none d-lg-block position-absolute start-50 translate-middle-x">
                <h1 class="text-white  fw-bold m-0" style="font-family:'Poppins' ">Daily Brew</h1>
            </div>
            
            <!-- Right Section: Navigation Icons -->
            <div class="d-flex align-items-center">
                <!-- Desktop Navigation Icons -->
                <div class="d-none d-md-flex align-items-center gap-2 gap-lg-4 text-white fs-5">
                    <a href="{{route('cart.index')}}" class="text-white p-2" title="Cart"><i class="fas fa-shopping-cart"></i></a>
                    <a href="{{route('wishlist.index')}}" class="text-white p-2" title="Wishlist"><i class="fas fa-heart"></i></a>
                    <a href="{{route('user.order')}}" class="text-white p-2" title="Orders"><i class="fas fa-receipt"></i></a>
                    <form method="POST" action="{{ route('user.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn p-2 text-white border-0 bg-transparent" title="Logout" style="font-size: inherit;">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Mobile Nav Icons -->
                <div class="d-flex d-md-none align-items-center">
                    <a href="{{route('cart.index')}}" class="text-white p-2" title="Cart"><i class="fas fa-shopping-cart"></i></a>
                    <form method="POST" action="{{ route('user.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn p-2 text-white border-0 bg-transparent" title="Logout" style="font-size: inherit;">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mobile Collapsed Menu -->
        <div class="collapse navbar-collapse mt-2" id="mobileMenu">
            <!-- Mobile Search -->
            <div class="py-2">
                <form action="{{ route('user.shop') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-start" placeholder="Search..." />
                        <span class="input-group-text bg-white rounded-end">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </form>
            </div>
            
            <!-- Mobile Menu Links -->
            <div class="py-2">
                <div class="d-grid gap-1">
                    <a href="{{route('user.home')}}" class="btn btn-outline-light text-start"><i class="fas fa-home me-2"></i>Home</a>
                    <a href="{{route('user.shop')}}" class="btn btn-outline-light text-start"><i class="fas fa-coffee me-2"></i>Shop</a>
                    <a href="{{route('wishlist.index')}}" class="btn btn-outline-light text-start"><i class="fas fa-heart me-2"></i>Wishlist</a>
                    <a href="{{route('user.order')}}" class="btn btn-outline-light text-start"><i class="fas fa-receipt me-2"></i>Orders</a>
                </div>
            </div>
        </div>
    </div>
</header>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>