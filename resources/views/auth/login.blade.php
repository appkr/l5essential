@extends('layouts.master')

@section('content')
  <form action="{{ route('session.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <div class="page-header">
      <h4>Login</h4>
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
