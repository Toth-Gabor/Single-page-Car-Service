<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Car-Service') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/js/application.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div class="card-header bg-dark text-light text-md-center">
        <h1>Car Service App</h1>
    </div>
    <main class="py-4">
        <div class="container">
            <!-- search section -->
            <div class="col-md-4">
                <form id="search-client">
                    <div class="form-group">
                        <label for="name">Ügyfél neve</label>
                        <input type="text" class="form-control" id="name" placeholder="Írja be a nevet">
                    </div>
                    <div class="form-group">
                        <label for="idcard">Ügyfél okmányazonosítója</label>
                        <input type="number" class="form-control" id="idcard" placeholder="Írja be az azonosítót">
                    </div>
                    <button id="search" type="submit" class="btn btn-primary">Keresés</button>
                    <input type="hidden" id="csrf-token" name="csrf-token" value="{{ csrf_token() }}">
                </form>
            </div>
            <!-- client data section -->
            <section class="client_data">
                @include('client')
            </section>
            <hr>
            <!-- client section -->
            <section class="clients">
                @include('clients')
            </section>

            <section>
                @include('client_cars')
            </section>
            <section>
                @include('car_services')
            </section>
        </div>
    </main>
</div>

<script type="text/javascript">

</script>
</body>
</html>
