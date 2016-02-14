# 15강 - 중첩된 리소스

특정 리소스에 딸린 하위 리소스를 보여줘야 하는 경우가 있다. 가령, Post id 1번에 딸린 Comment 목록을 보여주거나, Comment를 생성/수정/삭제하는 경우 등이다.

## 중첩 리소스 Route를 만들자.

app/Http/routes.php에서 아래와 같이 작성해 보자.

```php
Route::resource('posts.comments', 'PostCommentController');
```

artisan CLI 로 PostCommentController 를 만들자.

```bash
$ php artisan make:controller PostCommentController --resource
```

`$ php artisan route:list`로 확인해 보자. `posts/{posts}/comments/{comments}` 형태의 라우트를 얻을 수 있다.

![](./15-nested-resources-img-01.png)

## Route Parameter 접근

Controller에서 `$postId`와 `$commentId`에 접근할 수 있다.

```php
class PostCommentController extends Controller
{
    public function index($postId)
    {
        // GET http://localhost:8000/posts/1/comments
        return '[' . __METHOD__ . "] \$postId = {$postId}"; 
        // [App\Http\Controllers\PostCommentController::index] $postId = 1
    }
    
    ...

    public function show($postId, $commentId)
    {
        // GET http://localhost:8000/posts/1/comments/20
        return $postId . '-' . $commentId;
        // [App\Http\Controllers\PostCommentController::show] $postId = 1, $commentId = 20
    }
}
```

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [14강 - 이름 있는 Route](14-named-routes.md)
- [16강 - 사용자 인증 기본기](16-authentication.md)
<!--@end-->
