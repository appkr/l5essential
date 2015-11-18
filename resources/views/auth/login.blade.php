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
  <form action="{{ route('session.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <div class="page-header">
      <h4>Login</h4>
    </div>

    <div class="form-group">
      <a class="btn btn-default btn-block" href="{{ route('session.github.login') }}">
        <strong><i class="fa fa-github icon"></i> Login with Github</strong>
      </a>
    </div>

    <div class="login-or">
      <hr class="hr-or">
      <span class="span-or">or</span>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}" autofocus/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="Password">
      {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember" value="{{ old('remember', 1) }}" checked> Remember me
        </label>
      </div>
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-block" type="submit">Get me in~</button>
    </div>

    <div class="description">
      <p>&nbsp;</p>
      <p class="text-center">Not a member? <a href="{{ route('user.create') }}">Sign up</a></p>
      <p class="text-center"><a href="{{ route('reminder.create')}}">Remind my password</a></p>
    </div>

  </form>
@stop
