<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
  <label for="title">{{ trans('forum.title') }}</label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $article->title) }}"/>
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
  <label for="tags">{{ trans('forum.tags') }}</label>
  <select class="form-control select2-multiple" name="tags[]" id="tags" multiple="multiple">
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ in_array($tag->id, $article->tags->lists('id')->toArray()) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
    @endforeach
  </select>
  {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
  <a href="#" class="help-block pull-right hidden-xs" id="md-caller">
    <small>{!! icon('preview') !!} {{ trans('common.cheat_sheet') }}</small>
  </a>
  <label for="content">{{ trans('forum.content') }}</label>
  <textarea name="content" id="content" class="form-control forum__content" rows="10">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
  <div class="preview__forum">{{ markdown(old('content', trans('common.markdown_preview'))) }}</div>
</div>

<div class="form-group">
  <label for="my-dropzone">
    Files
    <small class="text-muted">
      Click to attach files <i class="fa fa-chevron-down"></i>
    </small>
    <small class="text-muted" style="display: none;">
      Click to close pane <i class="fa fa-chevron-up"></i>
    </small>
  </label>
  <div id="my-dropzone" class="dropzone"></div>
</div>

<div class="form-group">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="notification" {{ $article->notification ? 'checked="checked"': ''}}>
      {{ trans('forum.notification') }}
    </label>
  </div>
</div>

@if ($currentUser and $currentUser->isAdmin())
  <div class="form-group">
    <div class="checkbox">
      <label>
        <input type="checkbox" name="pin" {{ $article->pin ? 'checked="checked"': ''}}>
        {{ trans('forum.pin') }}
      </label>
    </div>
  </div>
@endif

@include('layouts.partial.markdown')

@section('script')
  <script>
    var form = $("form.form__forum").first(),
        dropzone  = $("div.dropzone"),
        dzControl = $("label[for=my-dropzone]>small");

    dzControl.on("click", function(e) {
      dropzone.fadeToggle(0);
      dzControl.fadeToggle(0);
    });

    /* Activate select2 for a nicer tag selector UI */
    $("select#tags").select2({
      placeholder: "{{ trans('forum.tags_help') }}",
      maximumSelectionLength: 3,
      theme: "bootstrap"
    });

    /* Dropzone Related */
    Dropzone.autoDiscover = false;

    /* Instantiate Dropzone for a nicer attachment upload UI */
    var myDropzone = new Dropzone("div#my-dropzone", {
      url: "/files",
      params: {
        _token: window.csrfToken,
        articleId: "{{ $article->id }}"
      },
      dictDefaultMessage: "<div class=\"text-center text-muted\">" +
      "<h2>{{ trans('forum.msg_dropfile') }}</h2>" +
      "<p>{{ trans('forum.msg_dropfile_sub') }}</p></div>",
      addRemoveLinks: true
    });

    var handleImage = function(objId, imgUrl, remove) {
      var caretPos = document.getElementById(objId).selectionStart;
      var textAreaTxt = $("#" + objId).val();
      var txtToAdd = "![](" + imgUrl + ")";

      if (remove) {
// Todo write remove logic
//        var pattern = new RegExp(txtToAdd);
//
//        if (pattern.test(textAreaTxt)) {
//          textAreaTxt.match(pattern);
//        }
        return;
      }

      $("#" + objId).val(
        textAreaTxt.substring(0, caretPos) +
        txtToAdd +
        textAreaTxt.substring(caretPos)
      );
    };

    myDropzone.on("success", function(file, data) {
      // File upload success handler
      // 1. make a hidden input to give hint to the server side what has been attached
      // 2. if the attached file was image type, call handleImage();
      file._id = data.id;
      file._name = data.name;
      file._url = data.url;

      $("<input>", {
        type: "hidden",
        name: "attachments[]",
        class: "attachments",
        value: data.id
      }).appendTo(form);

      if (/^image/.test(data.type)) {
        handleImage('content', data.url);
      }
    });

    myDropzone.on("removedfile", function(file) {
      // When user removed a file from the Dropzone UI,
      // the image will be disappear in DOM level, but not in the service
      // The following code send ajax request to the server to handle that situation
      $.ajax({
        type: "POST",
        url: "/files/" + file._id,
        data: {
          _method: "DELETE"
        }
      }).success(function(file, data) {
        handleImage('content', file._url, true);
      })
    });
  </script>
@stop
