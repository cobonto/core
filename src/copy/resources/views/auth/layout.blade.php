<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ transTpl('login','auth') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/font-awesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/form-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/selecttwo/selecttwo.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body>
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            @yield('content')
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/selecttwo/selecttwo.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/js/jquery.backstretch.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('auth/js/scripts.js') }}"></script>
<!--[if lt IE 10]>
<script type="text/javascript" src="{{ asset('auth/js/placeholder.js') }}"></script>
<![endif]-->
<script type="text/javascript">
    var bg_img = "{{ asset('auth/img/backgrounds/') }}"
</script>
</body>
</html>