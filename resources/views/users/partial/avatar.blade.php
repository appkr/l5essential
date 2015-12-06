<?php $size = isset($size) ? $size : 48; ?>

@if (isset($user) and $user)
  <a class="pull-left hidden-xs hidden-sm" href="{{ gravatar_profile_url($user->email) }}">
    <img class="media-object img-thumbnail" src="{{ gravatar_url($user->email, $size) }}" alt="{{ $user->name }}">
  </a>
@else
  <a class="pull-left hidden-xs hidden-sm" href="{{ gravatar_profile_url('john@example.com') }}">
    <img class="media-object img-thumbnail" src="{{ gravatar_url('john@example.com', $size) }}" alt="Unknown User">
  </a>
@endif