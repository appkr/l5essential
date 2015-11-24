@if ($user)
  <a class="pull-left hidden-xs hidden-sm" href="{{ gravatar_profile_url($user->email) }}">
    <img class="media-object img-thumbnail" src="{{ gravatar_url($user->email, 64) }}" alt="{{ $user->name }}">
  </a>
@else
  <a class="pull-left hidden-xs hidden-sm" href="{{ gravatar_profile_url('john@example.com') }}">
    <img class="media-object img-thumbnail" src="{{ gravatar_url('john@example.com', 64) }}" alt="Unknown User">
  </a>
@endif