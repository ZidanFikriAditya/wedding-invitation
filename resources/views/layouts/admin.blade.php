<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? '' }} | {{ config('app.name') }}</title>
  <link rel="shortcut icon" type="image/png" href="{{ url('assets') }}/images/logos/favicon.png" />
  <link rel="stylesheet" href="{{ url('assets') }}/css/styles.min.css" />
  <link rel="stylesheet" href="{{ url('summernote') }}/summernote.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
</head>

<body aria-live="polite" aria-atomic="true" class=" position-relative">
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('partials.admin.sidebar')
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
        @include('partials.admin.header')
      <!--  Header End -->
      <div class="container-fluid">
        {{-- Breadcumbs --}}
        @if (isset($breadcrumbs))
        <div class="page-header">
          <div class="row">
            <div class="col">
              <div class="page-header-left">
                <h3>@yield('title')</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="ti ti-home text-dark"></i></a></li>
                  @foreach($breadcrumbs->attributes as $key => $attributes)
                    @if ($key == "route")
                      @foreach ($attributes as $attribute)
                        @if ($loop->last)
                          @break
                        @endif
                        <li class="breadcrumb-item"><a href="{{ route($attribute['route']) }}" class="text-dark">{{ $attribute['name'] }}</a></li>
                      @endforeach
                        <li class="breadcrumb-item"><a href="{{ isset(end($attributes)['route']) ? route(end($attributes)['route']) : 'javascript:;' }}">{{ end($attributes)['name'] }}</a></li>
                    @endif
                  @endforeach
                  {{ $breadcrumbs ?? '' }}
                </ol>
              </div>
            </div>
          </div>            
        @endif
        {{-- End Breadcrumbs --}}
        <!--  Row 1 -->
          {{ $slot }}
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Developed by <a href="https://zidanfikriaditya.vercel.app" target="_blank" class="pe-1 text-primary text-decoration-underline">Zidan Fikri Aditya</a> Powered template by <a href="https://themewagon.com">ThemeWagon</a></p>
        </div>
      </div>

    </div>
  </div>
  {{-- Toast --}}
  <div class="toast-container position-fixed end-0 p-3" style="top: 8%">
    <div class="toast align-items-center" id="toast-general-alert" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Hello, world! This is a toast message.
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
{{-- End Toast --}}



  <script src="{{ url('assets') }}/libs/jquery/dist/jquery.min.js"></script>
  <script src="{{ url('assets') }}/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ url('assets') }}/js/sidebarmenu.js"></script>
  <script src="{{ url('assets') }}/js/app.min.js"></script>
  <script src="{{ url('summernote') }}/summernote.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

  <script>
    function showToast(type, message) {
      let toast = document.getElementById('toast-general-alert')
      let toastBody = toast.querySelector('.toast-body')
      toastBody.innerHTML = message
      toast.classList.add(`text-bg-${type}`)
      let bsToast = new bootstrap.Toast(toast)
      bsToast.show()
    }

    function hideToast() {
      let toast = document.getElementById('toast-general-alert')
      let bsToast = new bootstrap.Toast(toast)
      bsToast.hide()
    }
  </script>

  @isset($scripts)
      {{ $scripts }}
  @endisset
  @stack('scripts')
</body>

</html>