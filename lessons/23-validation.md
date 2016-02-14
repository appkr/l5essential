# 23강 - 입력 값 유효성 검사

항상 듣는 말이다. **"사용자가 입력한 값은 절대 신뢰하지 마라."** 유효성 검사는 사용자의 악의적인 해킹 시도 또는 잘못된 데이터 입력으로 부터 서비스를 보호하는 기본 중에 기본이므로 굉장히 중요하다. 프론트엔드에서 자바스크립트로 한번 걸렀다고 해도, HTTP 클라이언트를 통해서 직접 요청할 수 있으므로 백엔드에서도 반드시 유효성 검사를 수행해야 한다.
 
## 라라벨이 지원하는 유효성 검사 규칙

이 예제에서 사용하는 `required`, `min` 뿐 아니라, `string`, `confirmed`, `exists`, `required_if`, 등등등 굉장히 많다. [공식 문서](http://laravel.com/docs/validation#available-validation-rules)를 참고하자.

## 유효성 검사 레이어를 만들어 보자.

app/Http/routes.php 를 이용하자. 여러 번 말하지만 학습 목적이며, 실제로는 컨트롤러에 작성되어야 한다.

```php
Route::post('posts', function(\Illuminate\Http\Request $request) {
    $rule = [
        'title' => ['required'], // == 'title' => 'required'
        'body' => ['required', 'min:10'] // == 'body' => 'required|min:10'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
        return redirect('posts/create')->withErrors($validator)->withInput();
    }

    return 'Valid & proceed to next job ~';
});

Route::get('posts/create', function() {
    return view('posts.create');
});
```

`Validator::make(array $formData, array $rule)` 메소드로 `Validator` 인스턴스를 만든다. 첫번 째 인자는 폼 데이터인데, 예제에서는 `$request` 인스턴스를 주입하고 `$request->all()` 로 접근했다. 두번 째 인자는 유효성 규칙이다. `Validator` 인스턴스에 대해 `fails()` 메소드로 if 테스트를 수행하고, 유효성 검사를 통과하지 못했을 경우, 'posts/create' Route로 Redirect 시켰다. 이때, `withErrors($validator)` 로 Sesson 에 에러 데이터를 굽는다. 또, `withInput()`으로 Session에 방금 넘겨 받은 폼 데이터를 'posts/create' Route로 돌아갔을 때도 쓸 수 있도록 한다.

**`참고`** `$request` 인스턴스 주입 대신, `Request::input(string $key)/Input::get(string $key)`, `Request::all()/Input::all()` 과 같이 라라벨 Facade를 이용할 수도 있다. 

## 폼을 만들자.

resources/views/posts/create.blade.php 를 만들어 보자.

```html
@extends('master')

@section('content')
  <h1>New Post</h1>
  <hr/>
  <form action="/posts" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    <div>
      <label for="title">Title : </label>
      <input type="text" name="title" id="title" value="{{ old('title') }}"/>
      {!! $errors->first('title', '<span>:message</span>') !!}
    </div>

    <div>
      <label for="body">Body : </label>
      <textarea name="body" id="body" cols="30" rows="10">{{ old('body') }}</textarea>
      {!! $errors->first('body', '<span>:message</span>') !!}
    </div>

    <div>
      <button type="submit">Create New Post</button>
    </div>
  </form>
@stop
```

이 폼에서는 `{!! $errors->first('title', '<span>:message</span>') !!}` 와 같이 블레이드 문법으로 POST 'posts' Route에서 넘겨 받은 `$errors` 인스턴스에 접근하고 있다. `$errors->all()`로 전체 에러 메시지백을 받은 후, 루프를 돌면서 에러를 뿌리는 방법도 있다. `$errors` 변수는 모든 뷰에서 항상 존재하기 때문에 `if (isset($errors))` 등과 같은 방어 조치를 할 필요가 없다는 것을 기억해 두자. `$errors`는 `withErrors()`에 의해 세션에 구워진 값이다.

`{{ old(string $key) }}` Helper Function 을 사용한 것도 주의 깊게 보자. `old()`는 이전 입력 값이 Session에 없으면 '' 를 반환하고, 값이 있으면 반환한다. `withInput()`에 의해 세션에 구워진 값이다.

## 테스트 해 보자.

브라우저에서 'posts/create' Route를 열자. New Post 입력 폼이 열렸을 것이다. Title 입력박스에만 'First Article'이라고만 입력하고, '폼 제출' 버튼을 눌러 보자. 'post/create' `$validator->fails()` 블럭에서 'posts/create' Route로 Redirect되면서, Body 텍스트 영역 옆에 에러 메시지가 출력되었을 것이다. Title에 방금 입력했던 값이 폼에 입력되어 있는 것도 확인하자.

**`참고`** `create.blade.php` 에서 `{{ var_dump(Session::all()) }}`을 넣고, 폼 전송을 다시 해 보자.

## 다양한 폼 유효성 검사 방법

[공식문서](http://laravel.com/docs/validation)를 보면 크게 3가지 유효성 검사 방법을 설명하고 있다.
 
1. `Validator` 인스턴스를 직접 만드는 방법
2. 컨트롤러에서 기본으로 사용할 수 잇는 `validate()` 메소드를 이용하는 방법
3. `FormRequest`를 이용하는 방법

1 번은 우리 예제에서 이미 다루었다.

2 번은 모든 컨트롤러가 `App\Http\Controllers\Controller` 라는 베이스 컨트롤러를 상속하고 있고, 베이스 컨트롤러가 포함하고 있는 `ValidatesRequests` 트레이트에 정의된 `validate()` 메소드를 이용하는 방법이다. 사용법은 1번과 거의 유사하다. 메소드의 첫 번째 인자로, 1번 방법에서는 `Request::all()` 배열을 넘긴 반면, 이 방법에서는 `Illuminate\Http\Request` 인스턴스 자체를 넘기면 된다. 많이 하는 질문 중에 하나가 유효성 검사 규칙을 어디에 두어야 하냐는 것인데, 정답은 없다. 컨트롤러에 두어도 되는데, 보통 모델 클래스에 Static 속성으로 정의하는 것을 많이 보았다. 아래 Post 모델과 컨트롤러의 예제를 보자.
 
```
// app/Http/routes.php
Route::resource('posts', 'PostsController');

// app/Post.php
class Post extends Model
{
    public static $rules = [
        'title' => ['required'],
        'body' => ['required', 'min:10']
    ];
}

// app/Http/Controllers/PostsController.php
class PostsController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, \App\Post::$rules);

        return '[' . __METHOD__ . '] ' . 'validate the form data from the create form and create a new instance';
    }
}
```

3 번 Form Request를 이용하는 방법이 가장 깔끔하긴 하다. [공식 문서](http://laravel.com/docs/validation#form-request-validation)를 참고하자.

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [22강 - 이벤트](22-events.md)
- [24강 - 예외 처리](24-exception-handling.md)
<!--@end-->
