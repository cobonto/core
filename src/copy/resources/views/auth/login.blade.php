@extends('auth.layout')
@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h1><strong>{{ transTpl('welcome','auth') }}</strong></h1>
            <div class="description">
                <p>
                    {{ transTpl('login_and_enjoy_our_services','auth') }}
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 form-box">
            <div class="form-top">
                <div class="form-top-left">
                    <h3>{{ transTpl('login_to_our_site','auth') }}</h3>
                    <p>{{ transTpl('enter_user_and_password','auth') }}</p>
                </div>
                <div class="form-top-right">
                    <i class="fa fa-key"></i>
                </div>
            </div>
            <div class="form-bottom">
                <form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="sr-only">{{ transTpl('email','auth') }}</label>

                        <input id="email" placeholder="{{ transTpl('email','auth') }}" type="email" class="form-control" name="email"
                               value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="sr-only">{{ transTpl('password','auth') }}</label>

                        <input id="password" placeholder="{{ transTpl('password','auth') }}" type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div>

                    <div class="form-group">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-sign-in"></i> Login
                        </button>

                        <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your
                            Password?</a>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection