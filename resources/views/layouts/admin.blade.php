<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | {{ config('app.name') }}</title>
  <link rel="shortcut icon" type="image/png" href="{{ url('assets') }}/images/logos/favicon.png" />
  <link rel="stylesheet" href="{{ url('assets') }}/css/styles.min.css" />
</head>

<body>
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
                        <li class="breadcrumb-item"><a href="{{ isset(end($attributes)['route']) ? route(end($attributes)['route']) : 'javascript::void(0);' }}">{{ end($attributes)['name'] }}</a></li>
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
  <script src="{{ url('assets') }}/libs/jquery/dist/jquery.min.js"></script>
  <script src="{{ url('assets') }}/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ url('assets') }}/js/sidebarmenu.js"></script>
  <script src="{{ url('assets') }}/js/app.min.js"></script>

  @isset($scripts)
      {{ $scripts }}
  @endisset
  @stack('scripts')
</body>

</html>