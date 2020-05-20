<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ 'Admin - '. config('app.name', 'Laravel') }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">    

        @yield('styles')
    </head>
    <body>
        <div class="wrapper">
            <div class="container mb-5">
                <div class="d-flex align-items-center py-4 header">
                <h4 class="mb-0 ml-3"><strong>Permission</strong> - {{ config('app.name', 'Laravel') }}</h4>
                    @yield('topButtons')
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-2 sidebar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('permission.listRoles') }}">Roles</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permission.listAbilities') }}">Abilities</a>
                        </li>
                    </ul>
                </div>
                <div class="col-10">
                    @yield('content')
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        @yield('scripts')
        
    </body>
</html>

