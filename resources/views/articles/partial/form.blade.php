<div class="form-group">
  <label for="title">{{ trans('forum.title') }}</label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $article->title) }}"/>
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <label for="content">{{ trans('forum.content') }}</label>
  <textarea name="content" id="content" class="form-control" rows="10">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <label for="my-dropzone">Files</label>
  <div id="my-dropzone" class="dropzone"></div>
</div>

<div class="form-group">
  <label for="tags">{{ trans('forum.tags') }}</label>
  <select class="form-control" name="tags[]" id="tags" multiple="multiple">
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ in_array($tag->id, $article->tags->lists('id')->toArray()) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
    @endforeach
  </select>
  {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="notification" checked="{{ $article->notification ?: 'checked' }}">
      {{ trans('forum.notification') }}
    </label>
  </div>
</div>

@section('style')
  <style>
    .dropzone {
      border: 1px solid #aaa;
      border-radius: 4px;
    }
  </style>
@stop

@section('script')
  <script>
    $("select#tags").select2({
      placeholder: "{{ trans('forum.tags_help') }}",
      maximumSelectionLength: 3
    });

    Dropzone.autoDiscover = false;

    var form = $("form.form__forum").first();

    var myDropzone = new Dropzone("div#my-dropzone", {
      url: "/files",
      params: {
        _token: "{{ csrf_token() }}",
        articleId: "{{ $article->id }}"
      },
      dictDefaultMessage: "<div class=\"text-center text-muted\">" +
      "<h2>Drop files to upload !</h2>" +
      "<p>(or Click to choose...)</p></div>",
      addRemoveLinks: true
    });

    var insertImage = function(objId, imgUrl) {
      var caretPos = document.getElementById(objId).selectionStart;
      var textAreaTxt = $("#" + objId).val();
      var txtToAdd = "![](" + imgUrl + ")";
      $("#" + objId).val(
        textAreaTxt.substring(0, caretPos) +
        txtToAdd +
        textAreaTxt.substring(caretPos)
      );
    };

    myDropzone.on("success", function(file, data) {
      file._id = data.id;
      file._name = data.name;

      $("<input>", {
        type: "hidden",
        name: "attachments[]",
        class: "attachments",
        value: data.id
      }).appendTo(form);

      if (/^image/.test(data.type)) {
        insertImage('content', data.url);
      }
    });

    myDropzone.on("removedfile", function(file) {
      $.ajax({
        type: "POST",
        url: "/files/" + file._id,
        data: {
          _method: "DELETE",
          _token: $('meta[name="csrf-token"]').attr('content')
        }
      }).success(function(data) {
        console.log(data);
      })
    });
  </script>
@stop
