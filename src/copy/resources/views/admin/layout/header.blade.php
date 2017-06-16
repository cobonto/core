<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @foreach ($css as $file)
        <link href="{{ asset($file) }}" rel="stylesheet" media="all"/>
        @endforeach

                <!--hook header-->
        {!! $HOOK_HEADER !!}
                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>
<body>
<div class="wrapper">
    <div class="header">
        <!-- Logo -->
        <div class="logo"><a href="{{ adminRoute('dashboard.index') }}" class="logo">
                <img src="{{ asset('img/logo.png') }}" class="img-responsive">
        </a>
        </div>
        {!! hook('displayAdminNav') !!}
    </div>
@include('admin.layout.sidebar')
