@extends('layouts.master')

@section('content')
  <form action="{{ route('user.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <div class="page-header">
      <h4>Sign up</h4>
    </div>

    <div class="form-group">
      <input type="text" name="name" class="form-control" placeholder="Full name" value="{{ old('name') }}" autofocus/>
      {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}"/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="Password, minimum 6 chars"/>
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" />
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-block" type="submit">Sign me up~</button>
    </div>

  </form>
@stop

