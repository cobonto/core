<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLTE 2 | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/css/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('admin/css/ionicons/css/ionicons.min.css') }}">
    <!-- Theme style
    <link rel="stylesheet" href="{{ asset('admin/css/AdminLTE.css') }}"> -->
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
    <!-- auth -->
    <link rel="stylesheet" href="{{ asset('admin/css/auth.css') }}">
    <!-- rtl
    <link rel="stylesheet" href="{{ asset('admin/css/rtl.css') }}"> -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="auth">
        <div class="login-box">
            <div class="login-head">
                <div class="title">
                    <i class="fa fa-sign-in"></i>
                    {{ transTpl('welcome_title','auth') }}
                </div>
            </div>
            <div class="login-body">
                <form method="POST" action="{{ route('admin.login') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group username {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" placeholder="{{ transTpl('email','auth')}}" name="email">
                        <span class="icon"></span>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group password {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" placeholder="{{ transTpl('password','auth')}}" name="password">
                        <span class="icon"></span>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group clearfix">
                        <div class="checkbox icheck">
                            <label>
                                <input name="remember" type="checkbox"> {{ transTpl('remember','auth') }}
                            </label>
                        </div>
                        <button type="submit" class="login-btn">{{ transTpl('sign_in_btn','auth') }}</button>
                    </div>
                    <div class="form-group forget-pwd">
                        <a href="{{ url('admin/password/reset') }}">{{ transTpl('forget_passwd','auth') }}</a>
                    </div>
               </form>
            </div>
        </div>
        <div class="copyright">{{ transTpl('copy_right','auth') }}</div>
    </div>

<!-- jQuery 2.2.0 -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
