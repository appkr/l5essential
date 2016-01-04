# WorkInProgress

# 실전 프로젝트 3 - RESTful API

## 48강 - `all()` is bad

앞 강에서 작성한 `App\Http\Controllers\Api\V1\ArticlesController::index()` 메소드를 살펴 보자.

```php
class ArticlesController extends Controller
{
    // ...
    
    public function index()
    {
        return \App\Article::all();
    }
}
```

`all()` 이란 메소드를 이용해서, 리소스를 반환 하고 있다. `all|get|first\find|...` 등의 메소드를 이용하여, 컨트롤러에서 엘로퀀트 모델을 직접 반환하면, 자동으로 Json 으로 캐스팅된다. 그러나, 이렇게 엘로퀀트 모델을 직접 반환하면 다음과 같은 문제가 있다. 

### Why `all()` is bad.

1.  페이징
    가령 레코드가 10만개라고 생각해 보자. 응답 속도는 당연히 느릴 테고, 엄청난 네트워크 대역폭을 사용할 것이다. 그런데, 클라이언트가 사용할 레코드는 단 1개라면... API 에서 Pagination 은 필수이다.
    
2.  추가 데이터를 포함할 수 없다.
    엘로퀀트 모델을 그대로 반환한다면, 앞 강에서 보았던 JSON Web Token, HATEOAS 를 위한 링크, 페이지네이션을 위한 정보들을 어떻게 추가할 것인가?
      
3.  API 응답에 DB 의 구조가 그대로 드러난다.
    엘로퀀트 모델의 속성 중에는 API 클라이언트에게 필요하지 않은 필드가 있을 수 있다. 또, 클라이언트에게 DB 의 필드 이름이 아닌 다른 필드 이름을 반환하고 싶을 수 도 있다. DB 필드가 그대로 노출되는 것은 보안 측면에서도 좋지 않고, 혹, 나중에 DB 리모데링을 하게 될 경우, 모든 API 클라이언트가 갑자기 동작하지 않을 수도 있다.
    
4.  HTTP 헤더와 응답 코드
    엘로퀀트 모델을 그대로 반환하게 되면, 200, 404, 500 3 가지 응답 코드 밖에 쓸 수 없다. 뿐만 아니라, 커스텀 HTTP 헤더를 붙이기도 쉽지 않다.
    
그럼, 어쩌라고? `Response::make()` 또는 `response()` Helper 를 이용해서 잘 포맷팅 해서 내 보내야 하는데, 앞 강에서 끌어온 `appkr/api` 패키지가 그 역할을 해 준다.
  
### Transformer

Transformer (데이터 변환기) 를 이용함으로써, API 클라이언트에게 전달되는 데이터를 완벽하게 제어할 수 있다. 다시 말하면, 숫자, 날짜, 문자 포맷을 마음대로 변경할 수 있을 뿐더러, 필드를 추가하거나 숨기는 일이 가능해 진다. 앞 절의 3 번에서 언급한 데이터베이스 필드가 바뀌었을 때도, 이 Transformer 가 완충을 해 준다. 우리 프로젝트의 `Article` 모델을 반환할 때, `author` 관계를 중첩 (Nesting) 하는 등의 조작도 쉬워진다.
 
Transformer 는 아래 처럼 간단히 구현할 수 있기는 하지만, 지난 강좌에서 가져온 `appkr/api` 패키지가 의존하는 `league/fractal` 패키지에서 제공하는 [Transformer](http://fractal.thephpleague.com/transformers/) 를 이용할 것이다.

#### Simple Transformer

쓰지는 않을 것이지만, 기본은 이렇다 정도로 알아 두자.

```php
// Transformer.php

abstract class Transformer
{
    public function transformCollection(\Illuminate\Database\Eloquent\Collection $collection)
    {
        return array_map([$this, 'transform'], $collection);
    }
    
    public function transformPagination() { /* ... */ }
    
    public abstract function transform($item);
}
```

```php
// ArticleTransformer.php

class ArticleTransformer extends Transformer
{
    public function transform($article)
    {
        return [
            'id' => (int) $article->id,
            // ...
            'created' => $article->created_at->toISO8601String(),
            'author' => [
                'name' => $article->author->name,
                 // ...
            ],
        ];
    }
}
```

```php
// ArticlesController.php

class ArticlesController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => (new ArticleTransformer)->transformCollection(App\Article::get())    
        ]);
    }
}
```

#### `league/fractal` Transformer

이제 실제 사용할 Transformer 를 artisan CLI 로 만들것이다. CLI 사용법은 [`appkr/api` 문서](https://github.com/appkr/api) 를 참고하자.

'comments', 'author', 'tags', 'solution', 'attachments'

```bash
$ php artisan make:transformer App\\Article --includes=App\\Comment:comments:true,App\\Author:author,App\\Tag:tags:true,App\\Attachment:attachments:true
$ php artisan make:transformer App\\Comment --includes=App\\Author:authors
$ php artisan make:transformer App\\Tag --includes=App\\Article::articles:true
$ php artisan make:transformer App\\Attachment
$ php artisan make:transformer App\\User --includes=App\\Article:articles:true,App\\Comment:comments:true
```

### Serializer

![](https://i-msdn.sec.s-msft.com/dynimg/IC20067.jpeg)



<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [47강 - 중복 제거 리팩토링](lessons/47-dry-refactoring.md)
- [49강 - 자동 증가 리소스 ID 숨기기](49-autoincrement-id.md)
<!--@end-->