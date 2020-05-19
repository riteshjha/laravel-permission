<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ 'Admin - '. config('app.name', 'Project') }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">    
        <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

    </head>
    <body>
        <div class="wrapper">
            <div class="container mb-5">
                <div class="d-flex align-items-center py-4 header">
                    <h4 class="mb-0 ml-3"><strong>Laravel</strong> Telescope - ProjectX</h4>

                    @yield('topButtons')
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-2 sidebar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="/roles">Roles</a>
                        </li>
                        <li class="nav-item">
                            <a>Abilities</a>
                        </li>
                    </ul>
                </div>
                <div class="col-10">
                    @yield('content')
                </div>
            </div>
        </div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    </body>
</html>

