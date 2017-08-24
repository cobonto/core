@extends('auth.layout')
@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h1><strong>{{ transTpl('forget_password','auth') }}</strong></h1>
            <div class="description">
                <p>
                    {{ transTpl('enter_your_email_for_reset_password','auth') }}
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 form-box">
            <div class="form-top">
                <div class="form-top-left">
                    <h3>{{ transTpl('enter_your_email_for_reset_password','auth') }}</h3>
                </div>
                <div class="form-top-right">
                    <i class="fa fa-key"></i>
                </div>
            </div>
            <div class="form-bottom">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="login-form" role="form" method="POST" action="{{ url('/password/email') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') || $errors->has('mobile') ? ' has-error' : '' }}">
                        <label for="email" class="control-label">{{ transTpl('email_or_mobile','auth') }}</label>

                            <input id="email" type="text"  class="form-control" name="email" value="{{ old('email')?: old('mobile') }}">

                            @if ($errors->has('email')|| $errors->has('mobile'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email')?:$errors->first('mobile') }}</strong>
                                    </span>
                            @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ transTpl('send_password_link','auth') }}
                        </button>
                        <a class="btn" href="{{ url('/login') }}">
                            {{ transTpl('back','auth') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
