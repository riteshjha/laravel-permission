<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> @yield('pageTitle') - {{ config('app.name', 'Laravel') }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" rel="stylesheet">
        <link href="{{ asset('vendor/permission/css/style.css') }}" rel="stylesheet"/>
        @yield('styles')
    </head>
    <body>
        <div class="container mb-5">
            <div class="d-flex align-items-center py-4 header">
                <i class="fas fa-house-user fa-lg" style="color:#4040c8"></i>
                <h4 class="mb-0 ml-2"><strong>Permission</strong> - {{ config('app.name', 'Laravel') }}</h4>
                @yield('topButtons')
            </div>
            <div class="row mt-4">
                <div class="col-2 sidebar">
                    @include('permission::layouts.sidebar')
                </div>
                <div class="col-10">
                    <!-- Alert section -->
                    @yield('content')
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @yield('scripts')
    </body>
</html>

