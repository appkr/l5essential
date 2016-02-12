# 14강 - 이름 있는 Route

Named Routes는 여러모로 유용하다. 컨트롤러에서 `redirect(string $to)` Helper Function 으로 이동할 Url을 만들거나, 뷰 안에서 다른 Url로 이동하는 링크를 만들 때, 하드코드로 Url을 써 놓는 것 보다 여러 모로 관리상 편리하다. 가령, posts 라는 Url 엔트포인트를 어느날 갑자기 articles 로 모두 바꾸어야 한다고 생각해 보라. 모든 컨트롤러와 뷰를 찾아 다니면서 Url을 변경하는 것이 얼마나 귀찮을까?

## Route 에 이름을 주자

app/Http/routes.php 에서 공부해 보자. Route 메소드의 두번째 인자로 배열을 사용하고, 배열의 키로 'as'를 사용하여 라우트의 이름을 지정한다. 컨트롤러로의 연결은 'uses' 키를 사용한다.

```php
Route::get('posts', [
    'as'   => 'posts.index',
    'uses' => 'PostsController@index'
]);
```

`$ php artisan route:list`로 Name 컬럼을 확인해 보자. 이제 컨트롤러나 뷰에서 'posts.index'란 이름을 사용할 수 있다. 가령 `return redirect(route('posts.index'))` 또는 `<a href="{{ route('posts.index') }}">목록으로 돌아가기</a>`와 같은 식으로 말이다.

```bash
$ php artisan tinker
>>> route('posts.index');
=> "http://localhost/posts"
```

어 틀린데... 포트번호 8000이 빠졌잖아. 클라이언트가 브라우저일 경우 host와 port를 자동으로 인지하지만, 코맨드 라인에서는 HTTP 환경 변수가 없기 때문이다. `config/app.php`을 수정하자.

```php
'url' => 'http://localhost:8000',
```

Closure로 쓸 때도 이름을 부여할 수 있다.

```php
Route::get('posts', [
    'as' => 'posts.index',
    function() {
        return view('posts.index');
    }
]);
```

## RESTful Resource Route의 이름

```php
Route::resource('posts', 'PostsController');
```

`$ php artisan route:list` 로 확인해 보자. Route 이름이 자동으로 부여 된다. 이제 팅커링 해 보자.

```bash
$ php artisan tinker
>>> route('posts.index');
=> "http://localhost:8000/posts"
>>> route('posts.store');
=> "http://localhost:8000/posts"
>>> route('posts.create');
=> "http://localhost:8000/posts/create"
>>> route('posts.destroy', 1);
=> "http://localhost:8000/posts/1"
>>> route('posts.show', 1);
=> "http://localhost:8000/posts/1"
>>> route('posts.update', 1);
=> "http://localhost:8000/posts/1"
>>> route('posts.edit', 1);
=> "http://localhost:8000/posts/1/edit"
```

**`참고`** 중복된 Route의 경우, 항상 위에 정의된 것이 아래에 정의된 것을 오버라이드 한다. 가령 posts/count 라는 Route가 있다면 RESTful Resource 정의보다 먼저(== 위에) 정의하는게 안전하다.
<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [13강 - RESTful 리소스 컨트롤러](13-restful-resource-controller.md)
- [15강 - 중첩된 리소스](15-nested-resources.md)

<!--@end-->