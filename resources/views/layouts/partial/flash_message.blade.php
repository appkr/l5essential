@if (session()->has('flash_notification.message'))
  <div class="alert alert-{{ session('flash_notification.level') }} alert-dismissible flash-message" role="alert">
    <button type="button" class="close" data-dismiss="alert">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
    {{ session('flash_notification.message') }}
  </div>
@endif

@if ($errors->has())
  <div class="alert alert-danger alert-dismissible flash-message" role="alert">
    <button type="button" class="close" data-dismiss="alert">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
    {{ trans('errors.msg_form_error') }}
  </div>
@endif
