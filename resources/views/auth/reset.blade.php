@extends('layouts.master')

@section('content')
  <form action="{{ route('reset.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <h4>Reset Password</h4>

    <p class="text-muted">
      Provide your email address and NEW PASSWORD.
    </p>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}" autofocus>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="New password">
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <button class="btn btn-primary btn-block" type="submit">Reset My Password</button>

  </form>
@stop