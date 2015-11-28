@extends('layouts.master')

@section('content')
  <form action="{{ route('reset.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="page-header">
      <h4>{{ trans('auth.title_reset_password') }}</h4>
      <p class="text-muted">
        {{ trans('auth.title_reset_password_help') }}
      </p>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.email_address') }}" value="{{ old('email') }}" autofocus>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.new_password') }}">
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('auth.password_confirmation') }}">
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <button class="btn btn-primary btn-lg btn-block" type="submit">
      {{ trans('auth.button_reset_password') }}
    </button>

  </form>
@stop