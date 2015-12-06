<div class="dropdown pull-right">
  <span class="dropdown-toggle btn btn-default btn-xs" type="button" data-toggle="dropdown">
    {!! icon('dropdown', null) !!}
  </span>
  <ul class="dropdown-menu" role="menu">
    <li role="presentation">
      <a role="menuitem" tabindex="-1" alt="edit" class="btn__edit">
        {!! icon('update') !!} {{ trans('common.edit') }}
      </a>
    </li>
    <li role="presentation">
      <a role="menuitem" tabindex="-1" alt="delete" class="btn__delete">
        {!! icon('delete') !!} {{ trans('common.delete') }}
      </a>
    </li>
  </ul>
</div>