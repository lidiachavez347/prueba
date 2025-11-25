<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'U.E. Coronel Miguel Estenssoro',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'favicon' => [
        'use_ico_only' => true,
        'use_full_favicon' => false,
        'path' => 'favicon.ico',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>U.E. Cnl.</b> Miguel Estenssoro T.T. ',
    'logo_img' => 'images/logo.jpg',
    'logo_img_class' => 'brand-image img-circle elevation-3',

    'logo_img_xl' => null,
    'logo_img_xl_class' => null,
    'logo_img_alt' => 'Logo del sistema',

    'logo_img_login' => 'images/logo.jpg', // Ruta de la imagen del logo para la página de login
    'logo_img_login_class' => 'brand-image img-circle elevation-3',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-dark',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => true,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-light',
    'classes_auth_header' => 'bg-dark text-center',
    'classes_auth_body' => 'bg-light',
    'classes_auth_footer' => 'd-none', ///pra ocultar el footer del logian
    'classes_auth_icon' => 'text-dark',
    'classes_auth_btn' => 'btn-flat btn-dark',


    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => 'fixed',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-info elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration cogs
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-home',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
        [
            'type' => 'navbar-search',
            'text' => 'Buscar',
            'topnav_right' => false,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => false,
        ],
        //notificaciones
        [
            'type' => 'dropdown',
            'text' => 'Notificaciones',
            'topnav_right' => true,
            'icon' => 'fas fa-bell',  // Ícono de notificación
            'label' => 4,  // Cantidad de notificaciones no leídas
            'label_color' => 'warning',  // Color de la etiqueta
            'submenu' => [vendor/adminlte/dist/img/AdminLTELogo.png
                [
                    'text' => 'Nueva actualización disponible',
                    'url' => '#',
                    'icon' => 'fas fa-info-circle',
                    'label' => 'Nuevo',
                    'label_color' => 'success',  // Verde para una notificación nueva
                ],
                [
                    'text' => 'Problema de servidor',
                    'url' => '#',
                    'icon' => 'fas fa-exclamation-triangle',
                    'label' => 'Crítico',
                    'label_color' => 'danger',  // Rojo para una notificación crítica
                ],
                [
                    'text' => 'Nueva solicitud de soporte',
                    'url' => '#',
                    'icon' => 'fas fa-envelope',
                    'label' => 'Pendiente',
                    'label_color' => 'info',  // Azul para pendientes
                ],
                [
                    'text' => 'Ver todas las notificaciones',
                    'url' => 'notifications',
                    'icon' => 'fas fa-list',
                ],
            ],
        ],
        //alertas

        [
            'type' => 'dropdown',
            'text' => 'Alertas',
            'topnav_right' => true,
            'icon' => 'fas fa-exclamation-circle',  // Ícono de alertas
            'label' => 2,  // Cantidad de alertas activas
            'label_color' => 'danger',  // Rojo para alertas importantes
            'submenu' => [
                [
                    'text' => 'Alerta de seguridad',
                    'url' => '#',
                    'icon' => 'fas fa-shield-alt',
                    'label' => 'Urgente',
                    'label_color' => 'danger',  // Rojo para alertas de seguridad
                ],
                [
                    'text' => 'Mantenimiento programado',
                    'url' => '#',
                    'icon' => 'fas fa-tools',
                    'label' => 'Próximo',
                    'label_color' => 'warning',  // Amarillo para alertas de mantenimiento
                ],
            ],
        ],
        
        // mensajes
        [
            'type' => 'dropdown',
            'text' => 'Mensajes',
            'topnav_right' => true,
            'icon' => 'fas fa-envelope',  // Ícono de mensajes
            'label' => 5,  // Número de mensajes no leídos
            'submenu' => [
                [
                    'text' => 'Mensaje 1',
                    'url' => '#',
                    'icon' => 'fas fa-user-circle',
                    'label' => 'Nuevo',
                    'label_color' => 'success',
                ],
                [
                    'text' => 'Mensaje 2',
                    'url' => '#',
                    'icon' => 'fas fa-user-circle',
                    'label' => 'Leído',
                    'label_color' => 'secondary',
                ],
                [
                    'text' => 'Mensaje 3',
                    'url' => '#',
                    'icon' => 'fas fa-user-circle',
                    'label' => 'Importante',
                    'label_color' => 'danger',
                ],
                [
                    'text' => 'Ver todos los mensajes',
                    'url' => 'messages',
                    'icon' => 'fas fa-inbox',
                ],
            ],
        ],
    */

    'menu' => [
        // Navbar items:
        [

            'text' => 'Home',
            'url'  => 'home', // O simplemente la URL que desees
            'icon' => 'fas fa-home',
            'topnav_right' => true,
        ],

        /*[
            'type' => 'dropdown',
            'text' => 'Mensajes',
            'topnav_right' => true,
            'icon' => 'fas fa-envelope',  // Ícono de mensajes
            'label' => 5,  // Número de mensajes no leídos
            'submenu' => [
                [
                    'text' => 'Mensaje 1',
                    'url' => '#',
                    'icon' => 'fas fa-user-circle',
                    'label' => 'Nuevo',
                    'label_color' => 'success',
                ],
                [
                    'text' => 'Mensaje 2',
                    'url' => '#',
                    'icon' => 'fas fa-user-circle',
                    'label' => 'Leído',
                    'label_color' => 'secondary',
                ],
                [
                    'text' => 'Mensaje 3',
                    'url' => '#',
                    'icon' => 'fas fa-user-circle',
                    'label' => 'Importante',
                    'label_color' => 'danger',
                ],
                [
                    'text' => 'Ver todos los mensajes',
                    'url' => 'messages',
                    'icon' => 'fas fa-inbox',
                ],
            ],
        ],*/

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => ' Panel de datos',
            'url' => 'home',
            'icon' => 'fas fa-tachometer-alt',
        ],
        [
            'header' => 'Administracion',
            'can'  => 'admin.permisos.index',
        ],
        [
            'text' => 'Administracion',
            'icon' => 'fas fa-users',
            'submenu' => [],
        ],
        [
            'text' => ' Permisos',
            'url' => 'admin/permisos',
            'icon' => 'fa fa-lock',
            'can'  => 'admin.permisos.index',
        ],
        [
            'text' => 'Roles',
            'url' => 'admin/roles',
            'icon' => 'fa fa-cogs',
            'can'  => 'admin.roles.index',
        ],
        [
            'text' => 'Usuarios',
            'url' => 'admin/usuarios',
            'icon' => 'fa fa-users',
            'can'  => 'admin.usuarios.index',
        ],
        [
            'header' => 'Academico',
            'can'  => 'admin.permisos.index',
        ],
        [
            'text' => 'Configuración',
            'url' => 'admin/config',
            'icon' => 'fas fa-cogs', // Representa herramientas y configuración.
            'can'  => 'admin.config.index',
        ],

        [
            'text' => 'Grados',
            'url' => 'admin/grados',
            'icon' => 'fas fa-chalkboard', // Representa enseñanza o grupos académicos.
            'can'  => 'admin.grados.index',
        ],
        [
            'text' => 'Materias',
            'url' => 'admin/materias',
            'icon' => 'fas fa-book', // Representa materias o asignaturas.
            'can'  => 'admin.materias.index',
        ],

        [
            'text' => 'Estudiantes',
            'url' => 'admin/estudiantes',
            'icon' => 'fas fa-user-graduate',
            'can'  => 'admin.estudiantes.index',
        ],
        [
            'text' => 'Tutores',
            'url' => 'admin/tutores',
            'icon' => 'fas fa-user-tie',
            'can'  => 'admin.tutores.index',
        ],

        [
            'text' => 'Profesores',
            'url' => 'admin/profesores',
            'icon' => 'fas fa-graduation-cap',
            'can'  => 'admin.profesores.index',
        ],

        [
            'text' => 'Asignar profesor',
            'url' => 'admin/asignaturas',
            'icon' => 'fas fa-user-plus', // Representa la asignación o adición de un profesor.
            'can'  => 'admin.asignaturas.index',
        ],
        [
            'text' => 'Calendario',
            'url' => 'admin/calendario',
            'icon' => 'fas fa-calendar-alt',
            'can'  => 'admin.calendario.index',
        ],
//calendario.index   EN PERMISOS


        // [
        //'text' => 'Asistencias Docentes',
        ///'icon' => 'fas fa-chart-bar',
        //'submenu' => [
        //   [
        //         'text' => 'Registros',
        //        'url' => 'admin/asistencias',
        //        'icon' => 'fas fa-chalkboard-teacher',
        //      'can'  => 'admin.asistencias.index',
        //  ],

        //  ],
        // ],
        // [
        //'text' => 'Reportes y Estadisticas',
        //'icon' => 'fas fa-chart-bar',
        //'submenu' => [
        //  [
        ///       'text' => ' Estudiantes',
        //       'url' => '',
        //      'icon' => 'fas fa-user-graduate',
        // ],
        /// [
        //'text' => 'Docentes',
        //     'url' => '',
        //    'icon' => 'fas fa-chalkboard-teacher',
        // ],
        //  /[
        //    'text' => 'Modulos',
        //     'url' => '',
        //      'icon' => 'fas fa-book-open',
        //],
        //],
        // ],

        //[
        //  'text' => 'Soporte y Ayuda',
        //  'icon' => 'fas fa-question-circle',
        // 'submenu' => [
        // //    [
        // 'text' => ' Guia del sistema',
        //  'url' => '',
        //  'icon' => 'fas fa-book',
        // ],
        // [
        //  'text' => 'Contacto con soporte',
        //   'url' => '',
        //   'icon' => 'fas fa-headset',
        // ],
        // ],
        //],


        [
            'header' => 'Profesor',
            'can'  => 'profesor.contenidos.index',
        ],
        [
            'text' => 'Plan de clase',
            'url' => 'profesor/contenidos',
            'icon' => 'fas fa-book', // Icono de campana para alertas
            'can'  => 'profesor.contenidos.index',
        ],

        [
            'text' => 'Asistencias',
            'url' => 'profesor/asistencias',
            'icon' => 'fas fa-check-circle', // Icono para asistencias
            'can'  => 'profesor.asistencias.index',
        ],
        [
            'text' => 'Temas',
            'url' => 'profesor/temas',
            'icon' => 'fas fa-book', // Icono de libro para temas
            'can'  => 'profesor.temas.index',
        ],
        [
            'text' => 'Tareas',
            'url' => 'profesor/tareas',
            'icon' => 'fas fa-tasks', // Icono de tareas o actividades
            'can'  => 'profesor.tareas.index',
        ],
        [
            'text' => 'Resultado tareas',
            'url' => 'profesor/resultadotarea',
            'icon' => 'fas fa-clipboard-check', // Icono para resultados
            'can'  => 'profesor.resultadotarea.index',
        ],
        [
            'text' => 'Evaluaciones',
            'url' => 'profesor/evaluaciones',
            'icon' => 'fas fa-edit', // Icono de lápiz para evaluaciones
            'can'  => 'profesor.evaluaciones.index',
        ],
        [
            'text' => 'Resultado evaluaciones',
            'url' => 'profesor/resultadoevaluaciones',
            'icon' => 'fas fa-chart-bar', // Icono de estadísticas para resultados
            'can'  => 'profesor.resultadoevaluaciones.index',
        ],
        [
            'text' => 'Estudiantes',
            'url' => 'profesor/estudiantes',
            'icon' => 'fas fa-users', // Icono de grupo para estudiantes
            'can'  => 'profesor.estudiantes.index',
        ],
        [
            'header' => 'Centro de notas',
            'can'  => 'profesor.contenidos.index',
        ],[
            'text' => 'Ser',
            'url' => 'profesor/ser',
            'icon' => 'fas fa-brain',  // Icono de grupo para estudiantes
            'can'  => 'profesor.ser.index',
        ],
        
        [
            'text' => 'Saber',
            'url' => 'profesor/saber',
            'icon' => 'fas fa-book',  // Icono de grupo para estudiantes
            'can'  => 'profesor.ser.index',
        ],
        [
            'text' => 'Hacer',
            'url' => 'profesor/hacer',
            'icon' => 'fas fa-tools',  // Icono de grupo para estudiantes
            'can'  => 'profesor.ser.index',
        ],
        [
            'text' => 'Decidir',
            'url' => 'profesor/decidir',
            'icon' => 'fas fa-balance-scale',  // Icono de grupo para estudiantes
            'can'  => 'profesor.ser.index',
        ],
    [
            'text' => 'Centralizador',
            'url' => 'profesor/centralizador',
            'icon' => 'fas fa-layer-group',  // Icono de grupo para estudiantes
            'can'  => 'profesor.centralizador.index',
        ],




        [
            'text' => 'Alertas',
            'url' => 'profesor/alertas',
            'icon' => 'fas fa-bell', // Icono de campana para alertas
            'can'  => 'profesor.alertas.index',
        ],
        [
            'text' => 'Calendario académico',
            'url' => 'profesor/calendario',
            'icon' => 'fas fa-calendar-alt',
            'can'  => 'profesor.calendario.index',
        ],
        [
            'header' => 'Tutor',
            'can'  => 'tutores.notas.index',
        ],
                [
            'text' => 'Notas',
            'url' => 'tutores/notas',
            'icon' => 'fas fa-edit', // Icono de lápiz para evaluaciones
            'can'  => 'tutores.notas.index',
        ],

        [
            'text' => 'Contenido',
            'url' => 'estudiante/contenidos',
            'icon' => 'fas fa-book', // Icono de campana para alertas
            'can'  => 'estudiante.contenidos.index',
        ],

        [
            'text' => 'Alertas',
            'url' => 'tutores/alertas',
            'icon' => 'fas fa-bell', // Icono de campana para alertas
            'can'  => 'tutores.alertas.index',
        ],
        [
            'text' => 'Calendario',
            'url' => 'tutores/calendario',
            'icon' => 'fas fa-calendar-alt',
            'can'  => 'tutores.calendario.index',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
