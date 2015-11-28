@extends('layouts.master')

@section('style')
  <style>
    .login-or {
      position: relative;
      font-size: 16px;
      color: #aaa;
      margin-top: 20px;
      margin-bottom: 20px;
      padding-top: 15px;
      padding-bottom: 15px;
    }
    .span-or {
      display: block;
      position: absolute;
      left: 50%;
      top: -2px;
      margin-left: -25px;
      background-color: #f5f5f5;
      width: 50px;
      text-align: center;
    }
    .hr-or {
      background-color: #cdcdcd;
      height: 1px;
      margin-top: 0px !important;
      margin-bottom: 0px !important;
    }
  </style>
@stop

@section('content')
  <form action="{{ route('sessions.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <div class="page-header">
      <h4>{{ trans('auth.title_login') }}</h4>
      <p class="text-muted">
        {{ trans('auth.title_login_help') }}
      </p>
    </div>

    <div class="form-group">
      <a class="btn btn-default btn-lg btn-block" href="{{ route('social.login', ['github']) }}">
        <strong>{!! icon('github') !!} {{ trans('auth.login_with_github') }}</strong>
      </a>
    </div>

    <div class="login-or">
      <hr class="hr-or">
      <span class="span-or">or</span>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.email_address') }}" value="{{ old('email') }}" autofocus/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.password') }}">
      {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember" value="{{ old('remember', 1) }}" checked>
          {{ trans('auth.remember_me') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-lg btn-block" type="submit">
        {{ trans('auth.button_login') }}
      </button>
    </div>

    <div class="description">
      <p>&nbsp;</p>
      <p class="text-center">{{ trans('auth.recommend_signup') }}
        <a href="{{ route('users.create', ['return' => urlencode($currentUrl)]) }}">
          {{ trans('auth.button_signup') }}
        </a>
      </p>
      <p class="text-center">
        <a href="{{ route('remind.create')}}">
          {{ trans('auth.button_remind_password') }}
        </a>
        <br>
        <small class="text-center text-muted">
            {{ trans('auth.button_remind_password_help') }}
        </small>
      </p>
    </div>

  </form>
@stop
