<!DOCTYPE html>
<html
  lang="es"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Facturaci&oacute;n Electr&oacute;nica</title>

    <meta name="description" content="Sistema de Facturación Electrónica desarrollado por Mytems E.I.R.L." />
    <meta name="author" content="Mytems EIRL.">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon-mytems.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />


    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pnotify/pnotify.custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/spinkit/spinkit.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    @yield('styles')

    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <div id="ID-load" class="ID-load">
        <div>
            <div>
                <img src="{{ asset('assets/img/branding/load.png') }}" alt="" style="width: 55px;">
            </div>
            <div class="id-load-content">
                <div class="id-load-icon id-shop">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path class="fa-primary"
                            d="M490.3 13.1l57.3 90.7c29.7 46.9 3.4 112-52.1 119.4c-4 .5-7.9 .8-12.1 .8c-26.1 0-49.2-11.4-65.2-29c-15.9 17.6-39 29-65.2 29c-26.1 0-49.3-11.4-65.2-29c-15.9 17.6-39 29-65.2 29c-26.1 0-49.3-11.4-65.2-29c-15.9 17.6-39.1 29-65.2 29c-4.1 0-8.2-.3-12.1-.8c-55.3-7.4-81.5-72.6-51.9-119.4L85.7 13.1C90.8 5 99.9 0 109.6 0H466.4c9.7 0 18.8 5 23.9 13.1z" />
                        <path class="fa-secondary"
                            d="M64 219.1V384v64c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V384 219.1c-5.1 2-10.6 3.4-16.5 4.1c-4 .5-7.9 .8-12.1 .8c-12.7 0-24.6-2.7-35.4-7.5V384H128V216.5c-10.8 4.8-22.9 7.5-35.6 7.5c-4.1 0-8.2-.3-12.1-.8c-5.7-.8-11.2-2.2-16.2-4.1z" />
                    </svg>
                </div>
                <div class="id-load-icon id-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path class="fa-primary"
                            d="M0 24C0 10.7 10.7 0 24 0H69.5c26.9 0 50 19.1 55 45.5l51.6 271c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                        <path class="fa-secondary"
                            d="M170.7 288H459.2c32.6 0 61.1-21.8 69.5-53.3l41-152.3C576.6 57 557.4 32 531.1 32h-411c2 4.2 3.5 8.8 4.4 13.5L170.7 288z" />
                    </svg>
                </div>
    
                <div class="id-load-icon id-register">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path class="fa-primary"
                            d="M0 448V378.4c0-4.8 .4-9.6 1.1-14.4L23.8 214.4C28.5 183.1 55.4 160 87 160H425c31.6 0 58.5 23.1 63.3 54.4l22.7 149.6c.7 4.8 1.1 9.6 1.1 14.4V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64zm64-16c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16s-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm48-216a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72 24a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48z" />
                        <path class="fa-secondary"
                            d="M64 0C46.3 0 32 14.3 32 32V96c0 17.7 14.3 32 32 32h80v32h64V128h80c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H64zM96 48H256c8.8 0 16 7.2 16 16s-7.2 16-16 16H96c-8.8 0-16-7.2-16-16s7.2-16 16-16zm16 168a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72 24a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48z" />
                    </svg>
                </div>
                <div class="id-load-icon id-report">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path class="fa-primary"
                            d="M505 44c11 13.8 8.8 33.9-5 45L340 217c-11.4 9.1-27.5 9.4-39.2 .6L192.6 136.5 52 249c-13.8 11-33.9 8.8-45-5s-8.8-33.9 5-45L172 71c11.4-9.1 27.5-9.4 39.2-.6l108.2 81.1L460 39c13.8-11 33.9-8.8 45 5z" />
                        <path class="fa-secondary"
                            d="M192 224c-17.7 0-32 14.3-32 32V448c0 17.7 14.3 32 32 32s32-14.3 32-32V256c0-17.7-14.3-32-32-32zM64 320c-17.7 0-32 14.3-32 32v96c0 17.7 14.3 32 32 32s32-14.3 32-32V352c0-17.7-14.3-32-32-32zm224 0V448c0 17.7 14.3 32 32 32s32-14.3 32-32V320c0-17.7-14.3-32-32-32s-32 14.3-32 32zm160-96c-17.7 0-32 14.3-32 32V448c0 17.7 14.3 32 32 32s32-14.3 32-32V256c0-17.7-14.3-32-32-32z" />
                    </svg>
                </div>
    
                <div class="id-load-icon id-sale">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path class="fa-primary"
                            d="M477.6 19.8c-1.5-3.7-3.8-7.3-6.9-10.3c0 0-.1-.1-.1-.1C464.8 3.6 456.8 0 448 0h0H352c-17.7 0-32 14.3-32 32s14.3 32 32 32h18.7l-84.4 84.4L196.8 71.7c-12-10.3-29.7-10.3-41.7 0l-112 96c-13.4 11.5-15 31.7-3.5 45.1s31.7 15 45.1 3.5L176 138.1l91.2 78.1c12.7 10.9 31.6 10.2 43.5-1.7L416 109.3V128c0 17.7 14.3 32 32 32s32-14.3 32-32V32v0c0 0 0-.1 0-.1c0-4.3-.9-8.4-2.4-12.2z" />
                        <path class="fa-secondary"
                            d="M48 256c-26.5 0-48 21.5-48 48V464c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V304c0-26.5-21.5-48-48-48H48zM96 464H48V416c26.5 0 48 21.5 48 48zM48 352V304H96c0 26.5-21.5 48-48 48zM416 464c0-26.5 21.5-48 48-48v48H416zm48-112c-26.5 0-48-21.5-48-48h48v48zM256 320a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                    </svg>
                </div>
                <div class="id-load-icon id-invetory">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path class="fa-primary"
                            d="M176 48A48 48 0 1 0 80 48a48 48 0 1 0 96 0zM144 306.7V241.7l19 28.5c4.6 7 11 12.6 18.5 16.3l60.2 30.1c15.8 7.9 35 1.5 42.9-14.3s1.5-35-14.3-42.9l-56.4-28.2L166.5 160c-13.3-20-35.8-32-59.9-32C74.2 128 48 154.2 48 186.6v88.1c0 17 6.7 33.3 18.7 45.3l79.4 79.4 14.3 85.9c2.9 17.4 19.4 29.2 36.8 26.3s29.2-19.4 26.3-36.8l-15.2-90.9c-1.6-9.9-6.3-19-13.4-26.1l-51-51zM2.3 468.1c-6.6 16.4 1.4 35 17.8 41.6s35-1.4 41.6-17.8l37.6-94L50.1 348.6 2.3 468.1zM464 48a48 48 0 1 0 96 0 48 48 0 1 0 -96 0zm32 258.7l-51 51c-7.1 7.1-11.8 16.2-13.4 26.1l-15.2 90.9c-2.9 17.4 8.9 33.9 26.3 36.8s33.9-8.9 36.8-26.3l14.3-85.9L573.3 320c12-12 18.7-28.3 18.7-45.3V186.6c0-32.4-26.2-58.6-58.6-58.6c-24.1 0-46.5 12-59.9 32l-47.4 71.1-56.4 28.2c-15.8 7.9-22.2 27.1-14.3 42.9s27.1 22.2 42.9 14.3l60.2-30.1c7.5-3.7 13.8-9.4 18.5-16.3l19-28.5v65.1zM637.7 468.1L589.9 348.6l-49.2 49.2 37.6 94c6.6 16.4 25.2 24.4 41.6 17.8s24.4-25.2 17.8-41.6z" />
                        <path class="fa-secondary"
                            d="M248.6 319.2c-2.4-.6-4.7-1.4-7-2.5l-.6-.3c2.4 1.2 4.9 2.2 7.5 2.8zm134.7 .8H256.6c11.5-.2 22.5-6.7 28-17.7c7.9-15.8 1.5-35-14.3-42.9L224 236.2V160c0-17.7 14.3-32 32-32H384c17.7 0 32 14.3 32 32v76.2l-46.3 23.2c-15.8 7.9-22.2 27.1-14.3 42.9c5.5 11 16.5 17.4 28 17.7zm8.5-1c2.5-.6 4.8-1.5 7-2.7l-.5 .3c-2.1 1.1-4.3 1.9-6.5 2.4z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Layout wrapper -->
    <div id="layout-content" class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <!-- Layout container -->
        <div class="layout-page" style="padding-left: 0px;">
          <!-- Navbar -->
          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar" style="left: 0px; width: calc(100% - 0rem); max-width: 100%;">
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper mb-0">
                    
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                  <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                    <i class="ti ti-md"></i>
                  </a>
                </li>
                <!--/ Style Switcher -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('assets/img/avatars/avatar.png') }}" alt class="h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ asset('assets/img/avatars/avatar.png') }}" alt class="h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">{{ Auth::user()['nombres'] }}</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>

            <!-- Search Small Screens -->
            <div class="navbar-search-wrapper search-input-wrapper d-none">
              <input
                type="text"
                class="form-control search-input container-xxl border-0"
                placeholder="Buscar..."
                aria-label="Search..." />
              <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper" style="margin-top: -10px;">
            <!-- Content -->
            @yield('content')
            <!-- / Content -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pnotify/pnotify.custom.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/functions.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-ecommerce.js') }}"></script>
    @include('admin.js-home')
    @yield('scripts')
  </body>
</html>
