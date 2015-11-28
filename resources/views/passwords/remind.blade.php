@extends('layouts.master')

@section('content')
  <form action="{{ route('remind.store') }}" method="POST" role="form" class="form-auth">

    {!! csrf_field() !!}

    <div class="page-header">
      <h4>{{ trans('auth.title_password_remind') }}</h4>
      <p class="text-muted">
        {{ trans('auth.title_password_remind_help') }}
      </p>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.email_address') }}" value="{{ old('email') }}" autofocus>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <button class="btn btn-primary btn-lg btn-block" type="submit">
      {{ trans('auth.button_send_reminder') }}
    </button>

  </form>
@stop