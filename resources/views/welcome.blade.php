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
    <!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container-fluid">

            <a class="navbar-brand" href="#"><img src="{{ asset('images/logo.jpg') }}" 
        alt="Logo U.E." 
        style="width: 50px; height: auto; margin-bottom: 5px;"></a> <b>Bienvenido</b>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        @if (Route::has('login'))
                        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                            @auth
                            <a href="{{ url('/home') }}" class="nav-link active" aria-current="page"><i class="fa-solid fa-house" style="color: #20256a;"></i> Home</a>
                            @else
                            <a href="{{ route('auth.qr-login') }}" class="nav-link active" aria-current="page"><b> <i class="fa-solid fa-house" style="color: #20256a;"></i> Iniciar</b> </a>

                            @endauth
                        
                        </div>
                        @endif
                    </li>
                    <li></li>

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
<img src="{{ asset('images/lobo.png') }}" 
        alt="Logo U.E." 
        style="width: 400px; height: auto; margin-bottom: 5px;">
                <h1>U. E. Coronel Miguel Estenssoro T.T</h1>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/ESTE.jpeg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
<img src="{{ asset('images/lobo.png') }}" 
        alt="Logo U.E." 
        style="width: 400px; height: auto; margin-bottom: 5px;">
                    <h1>U. E. Coronel Miguel Estenssoro T.T</h1>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/ESTE.jpeg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
<img src="{{ asset('images/lobo.png') }}" 
        alt="Logo U.E." 
        style="width: 400px; height: auto; margin-bottom: 5px;">
                <h1>U. E. Coronel Miguel Estenssoro T.T</h1>
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
<style>
    .vertical-line {
        border-left: 3px solid #0d6efd; /* color azul, puedes cambiarlo */
        height: 100%;
        margin: 0 auto;
    }
</style>
<br>
    <div class="row ms-4">

    <!-- VISIÓN -->
    <div class="col-md-5 mb-4">
        <div class="text-center">
            <h2 class="fw-bold text-primary"><i class="fas fa-eye"></i> Visión</h2>
            <hr class="w-25 mx-auto mb-3" style="border-top: 3px solid #0d6efd;">
        </div>

        <p class="text-justify" >
            La Unidad Educativa Cnl. Miguel Estenssoro Tarde en el nivel primario tiene como visión 
            ofertar una calidad educativa con recursos humanos comprometidos y competitivos para formar 
            estudiantes en sus dimensiones del ser, saber, hacer y decidir; que desarrollen sus propias 
            experiencias de aprendizaje productivo a partir del análisis crítico de su contexto, 
            asimilando y aplicando conocimientos técnicos, tecnológicos y científicos para la vida 
            y para vivir bien en armonía con la Madre Tierra.
        </p>
    </div>
    <!-- LÍNEA VERTICAL -->
    <div class="col-md-2 d-flex justify-content-center">
        <div class="vertical-line"></div>
    </div>


    <!-- MISIÓN -->
    <div class="col-md-5 mb-4">
        <div class="text-center">
            <h2 class="fw-bold text-success"><i class="fas fa-bullseye"></i> Misión</h2>
            <hr class="w-25 mx-auto mb-3" style="border-top: 3px solid #198754;">
        </div>

        <p class="text-justify" >
            La Unidad Educativa Cnl. Miguel Estenssoro Tarde es una institución educativa pública, 
            consolidada institucional y académicamente; cuya misión es brindar una formación integral 
            y holística a estudiantes, desarrollando capacidades y competencias para la acción, 
            solidarios, éticos y respetuosos del entorno; capaces de afrontar problemas de la vida y 
            para la vida, acorde a las necesidades de la sociedad y la dinámica del conocimiento 
            e información; respondiendo a estándares nacionales e internacionales de calidad educativa.
        </p>
    </div>
</div>


<!-- BIENVENIDO -->
<div class="container text-center mt-5 mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-circle-info"></i> Informacion</h2>
    <hr class="w-25 mx-auto" style="border-top: 3px solid #000;">
    <a href="https://www.google.com/maps/search/?api=1&query=CALLE+CAMERO+ESQUINA+SANTA+CRUZ,+YACUIBA,+BOLIVIA" 
    target="_blank" 
    style="text-decoration: none; color: inherit;">
        <i class="fa-solid fa-map"></i> CALLE CAMERO ESQUINA SANTA CRUZ, YACUIBA, BOLIVIA
    </a>
    <p></p>
    <p><i class="fa-solid fa-at"></i>  <a href="mailto:estenssoroprimaria@ueestenssoro.com">
        estenssoroprimaria@ueestenssoro.com  
    </a></p><p>   <a href="https://wa.me/59176829247" target="_blank">
        <i class="fa-brands fa-whatsapp"></i> +591 76829247
    </a></p>
    <h4><i class="fas fa-chart-line"></i> Gestion vigente</h4>

</div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>


</html>