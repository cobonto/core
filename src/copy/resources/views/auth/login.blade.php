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

                    <div class="form-group{{ $errors->has('email') || $errors->has('mobile') ? ' has-error' : '' }}">
                        <label for="email" class="sr-only">{{ transTpl('email','auth') }}</label>

                        <input id="email" placeholder="{{ transTpl('email_or_mobile','auth') }}" type="text" class="form-control" name="email"
                               value="{{ old('email')?:old('mobile') }}">

                        @if ($errors->has('email') || $errors->has('mobile'))
                            <span class="help-block">
                                        <strong>{{ $errors->has('email')?$errors->first('email'):$errors->first('mobile') }}</strong>
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
                                <input type="checkbox" name="remember"> {{ transTpl('remember_me','auth') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">
                            {{ transTpl('login','auth') }}
                        </button>
                        <a class="btn" href="{{ url('/register') }}">
                            {{ transTpl('register','auth') }}
                        </a>
                        <a class="btn" href="{{ url('/password/reset') }}">{{ transTpl('forget_password','auth') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection