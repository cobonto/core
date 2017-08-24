@extends('auth.layout')
@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h1><strong>{{ transTpl('register','auth') }}</strong></h1>
            <div class="description">
                <p>
                    {{ transTpl('sign_up_in_our_site','auth') }}
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 form-box">
            <div class="form-top">
                <div class="form-top-left">
                    <h3>{{ transTpl('register_to_site','auth') }}</h3>
                    <p>{{ transTpl('fill_all_fields','auth') }}</p>
                </div>
                <div class="form-top-right">
                    <i class="fa fa-user-plus"></i>
                </div>
            </div>
            <div class="form-bottom">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                        <label for="firstname" class="control-label col-md-4">{{ transTpl('firstname','auth') }}</label>
                        <div class="col-md-6">
                            <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">
                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                        <label for="lastname" class="control-label col-md-4">{{ transTpl('lastname','auth') }}</label>

                        <div class="col-md-6">
                            <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">
                            @if ($errors->has('lastname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                        <label for="mobile" class="control-label col-md-4">{{ transTpl('mobile','auth') }}</label>
                        <div class="col-md-6">
                            <input id="mobile" type="text" class="form-control" name="mobile" value="{{ old('mobile') }}">
                            @if ($errors->has('mobile'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="control-label col-md-4">{{ transTpl('email','auth') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label col-md-4">{{ transTpl('password','auth') }}</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    {{--<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="control-label col-md-4">{{ transTpl('confirm_password','auth') }}</label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>--}}
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn">
                                {{ transTpl('register','auth') }}
                            </button>
                            <a class="btn" href="{{ url('/login') }}">
                                {{ transTpl('back','auth') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

