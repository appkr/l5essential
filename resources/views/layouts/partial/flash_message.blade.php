@if (session()->has('flash_notification'))
  @if (session()->has('flash_notification.overlay'))
    @include('layouts.partial.flash_modal', ['modalClass' => 'flash-modal', 'title' => session('flash_notification.title'), 'body' => session('flash_notification.message')])
  @else
    <div class="alert alert-{{ session('flash_notification.level') }} alert-dismissible flash-message" role="alert">
      <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
      </button>
      {{ session('flash_notification.message') }}
    </div>
  @endif
@endif

{{--@if ($errors->has())
  <div class="alert alert-danger alert-dismissible flash-message" role="alert">
    <button type="button" class="close" data-dismiss="alert">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
    {{ trans('errors.msg_form_error') }}
  </div>
@endif--}}
