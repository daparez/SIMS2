<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', 'Sistem Informasi Sekolah')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS Sneat -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendor CSS Tambahan -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    @stack('page-styles')

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- CSS Kustom untuk Perbaikan Smooth Toggle Sidebar -->
    <style>
        /* Transisi halus saat sidebar dibuka/ditutup */
        .layout-menu, .layout-page {
            transition: all 0.25s ease-in-out !important;
        }

        /* Saat Desktop dalam mode Collapsed (Menutup Sidebar) */
        @media (min-width: 1200px) {
            html.layout-menu-collapsed #layout-menu {
                transform: translate3d(-100%, 0, 0) !important;
            }
            html.layout-menu-collapsed .layout-page {
                padding-left: 0 !important;
            }
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- 1. Include Sidebar -->
            @include('layouts.sidebar')

            <!-- Layout container -->
            <div class="layout-page">

                <!-- 2. Include Navbar / Header -->
                @include('layouts.header')

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content Utama Halaman -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- 3. Include Footer -->
                    @include('layouts.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->

            </div>
            <!-- / Layout page -->

        </div>

        <!-- Overlay untuk Mode Mobile/HP -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS Sneat -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS Sneat -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Script Kustom Buka-Tutup (Toggle) Sidebar Universal -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleElements = document.querySelectorAll('.layout-menu-toggle');

            toggleElements.forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const htmlTag = document.documentElement;

                    if (window.innerWidth >= 1200) {
                        // Untuk Layar Desktop: Toggle Sembunyikan/Tampilkan Sidebar
                        htmlTag.classList.toggle('layout-menu-collapsed');
                    } else {
                        // Untuk Layar HP/Mobile: Toggle Slide Sidebar
                        htmlTag.classList.toggle('layout-menu-expanded');
                    }
                });
            });
        });
    </script>

    @stack('page-scripts')
</body>
</html>