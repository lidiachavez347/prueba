<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>U.E. Coronel Miguel Estenssoro</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Personaliza el alto del carrusel */
        .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
    </style>
    <!-- Styles -->
</head>

<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

    </div>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="#">Menu</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        @if (Route::has('login'))
                        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                            @auth
                            <a href="{{ url('/home') }}" class="nav-link active" aria-current="page">Home</a>
                            @else
                            <a href="{{ route('auth.qr-login') }}" class="nav-link active" aria-current="page">Iniciar </a>

                            @endauth
                        </div>
                        @endif
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/ESTE.jpeg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">

                    <p>U. E. Coronel Miguel Estenssoro</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/ESTE.jpeg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">

                    <p>U. E. Coronel Miguel Estenssoro</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/ESTE.jpeg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                <p>U. E. Coronel Miguel Estenssoro</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <div class="container text-center mt-4">
        <h1>Bienvenido</h1>
        <p></p>
    </div>


    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>


</html>