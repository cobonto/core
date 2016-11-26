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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('admin/dashboard') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">{{ transTpl('admin_title_mini') }}</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{ transTpl('admin_title') }}</span>
        </a>
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">{{ transTpl('toggle_nav','tpl') }}</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    {!! hook('displayAdminNav') !!}
                </ul>
            </div>
        </nav>
    </header>
@include('admin.layout.sidebar')
