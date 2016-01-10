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

`all()` 이란 메소드를 이용해서, 리소스를 반환 하고 있다. `all|get|first\find|...` 등의 메소드를 이용하여, 컨트롤러에서 엘로퀀트 모델을 직접 반환하면, 라라벨이 자동으로 Json 으로 캐스팅해 주긴 한다. 그러나, 이렇게 엘로퀀트 모델을 직접 반환하면 다음과 같은 문제가 있다. 

### Why `all()` is bad.

1.  페이징
    가령 레코드가 10만개라고 생각해 보자. 응답 속도는 당연히 느릴 테고, 엄청난 네트워크 대역폭을 사용할 것이다. 그런데, 정작 클라이언트가 필요로 하는 레코드는 단 몇 개라면... 클라이언트가 필요한 데이터가 속한 구간을 탐색해서 사용할 수 있도록 API 에서 Pagination 은 필수이다.
    
2.  추가 데이터를 포함할 수 없다.
    엘로퀀트 모델을 그대로 반환한다면, 앞 강에서 보았던 JSON Web Token, HATEOAS 를 위한 링크, 페이지네이션을 위한 정보들을 어떻게 추가할 것인가? 엘로퀀트 모델에서 [Accessor](https://laravel.com/docs/5.2/eloquent-mutators#accessors-and-mutators) 를 사용할 수 있지만 한계가 있고, API 응답만 분리하기도 쉽지 않다.
      
3.  API 응답에 DB 의 구조가 그대로 드러난다.
    엘로퀀트 모델의 속성 중에는 API 클라이언트에게 필요하지 않은 필드가 있을 수 있다. 또, 클라이언트에게 DB 의 필드 이름이 아닌 다른 필드 이름을 반환하고 싶을 수 도 있다. DB 필드가 그대로 노출되는 것은 보안 측면에서도 좋지 않고, 혹, 나중에 DB 리모데링을 하게 될 경우, 모든 API 클라이언트가 갑자기 동작하지 않고, 변경된 API 로 마이그레이션하는데 오랜 시간이 걸릴 수 있다.
    
4.  HTTP 헤더와 응답 코드
    엘로퀀트 모델을 그대로 반환하게 되면, 200, 404, 500 3 가지 응답 코드 밖에 쓸 수 없다. 뿐만 아니라, 커스텀 HTTP 헤더를 붙이기도 쉽지 않다.
    
그럼, 어쩌라고? `Response::make()` 또는 `response()` Helper 를 이용해서 잘 포맷팅 해서 내 보내야 하는데, 앞 강에서 끌어온 `appkr/api` 패키지가 그 역할을 해 준다.
  
### Transformer

앞 강에서 계속 봤듯이, 컨트롤러에서 뷰를 반환할 때 뷰에 바인딩할 데이터를 모델로 부터 뽑아서 전달한다. 그런데, 뷰에서 모델의 모든 속성 값을 표시하던가? 그리고 필요에 따라서는, 가령 `$model->created_at->diffForHumans()` 처럼 값의 형태를 변경하기여 뷰에 뿌리기도 한 것을 기억할 것이다. 
 
그런데, API 에서는 뷰라는 것이 없다. 우리가 응답하는 JOSN, 즉 데이터 자체가 뷰 (==Presentation Layer) 라고 생각하면 되는데, 여기서도 필요한 데이터만을 표시하거나, 데이터 형태를 변경하는 일이 필요하다. 이 때 필요한 것이 Data Transformer (데이터 변환기) 이다. 

Transformer (데이터 변환기) 를 이용함으로써, API 클라이언트에게 전달되는 데이터를 완벽하게 제어할 수 있다. 다시 말하면, 데이터 타입/포맷을 마음대로 변경할 수 있을 뿐더러, 필드를 추가하거나 숨기는 일이 가능해 진다. 앞 절의 3 번에서 언급한 데이터베이스 필드가 바뀌었을 때도, 이 Transformer 가 완충 역할을 할 수 있다. 우리 프로젝트의 `Article` 모델을 반환할 때, `author` 관계를 중첩 (Nesting) 하는 등의 조작도 쉬워진다.
 
Transformer 는 아래 'Simple Trasformer' 의 예처럼 배열을 순회하면서 간단히 구현할 수 있기는 하지만, 지난 강좌에서 가져온 `appkr/api` 패키지가 의존하는 `league/fractal` 패키지에서 제공하는 [Transformer](http://fractal.thephpleague.com/transformers/) 를 이용할 것이다.

#### Simple Transformer

우리의 실전 프로젝트에서 쓰지는 않을 것이지만, 기본은 이렇다 정도로 알아 두자.

`Transformer` 라는 추상 클래스에 `transformCollection()`, `transform()` 등의 메소드를 정의하고 있다. 자세히 보면 `transformCollection()` 메소드는 인자로 넘겨 받은 `$collection` 을 `array_map()` PHP 내장 함수를 이용해서 순회하면서 같은 클래스에 있는 `transform()` 메소드를 호출하는 것을 볼 수 있다. 그리고, `transform()` 메소드 자체는 이름만 있고, 내용이 없는 `abstract` 로 정의되어 있다.
 
`ArticleTransformer` 는 `Transformer` 추상 클래스를 상속하고 있기 때문에, 부모 클래스에서 `abstract` 로 정의한 `transform()` 메소드를 반드시 구현해야 한다. 여기서, 앞서 언급했던 필요한 필드명을 바꾼다거나, 데이터 타입을 변경하는 등의 작업을 수행한다.
  
`ArticlesController::index()` 메소드에서 JSON 을 응답할 때, 앞서 구현한 `ArticleTransformer::transformCollection()` 메소드를 이용하는 것을 볼 수 있다. 모델을 쿼리해서 얻은 엘로퀀트 Collection 을 메소드 인자로 넘기고 있는 것을 확인할 수 있다. 엘로퀀트 Collection 은 PHP 의 ArrayAccess 와 ArrayIterator 를 구현하고 있기에, 배열처럼 순회하면서 우리가 원하는 일들을 할 수 있는 것이다.

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

#### Advanced Transformer

이제 이 프로젝트에서 사용할 Transformer 를 artisan CLI 로 만들것이다. CLI 사용법은 [`appkr/api` 문서](https://github.com/appkr/api) 를 참고하자.

```bash
$ php artisan make:transformer App\\Article --includes=App\\Comment:comments:true,App\\Author:author,App\\Tag:tags:true,App\\Attachment:attachments:true
$ php artisan make:transformer App\\Comment --includes=App\\Author:authors
$ php artisan make:transformer App\\Tag --includes=App\\Article::articles:true
$ php artisan make:transformer App\\Attachment
$ php artisan make:transformer App\\User --includes=App\\Article:articles:true,App\\Comment:comments:true
```

`ArticleTransformer` 하나만 살펴 보도록 하자.

```php
// app/Transformers/ArticleTransformer.php

<?php

namespace App\Transformers;

use App\Article;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ArticleTransformer extends TransformerAbstract
{
    // 클라이언트에서 /v1/articles?include=comments:limit(2|0):order(created_at|desc) 처럼
    // Nesting 된 하위 리소스를 JSON 응답에 포함할 때, 응답할 갯수와 정렬을 정의할 수 있다.
    protected $availableIncludes = ['comments', 'author', 'tags', 'attachments'];

    public function transform(Article $article)
    {
        return [
            'id'           => (int) $article->id, // 정수형으로 캐스팅
            'title'        => $article->title,
            'content_raw'  => strip_tags($article->content), // HTML 태그를 모두 제거 
            'contant_html' => markdown($article->content), // 마크다운으로 컴파일
            'created'      => $article->created_at->toIso8601String(),
            'view_count'   => (int) $article->view_count,
            'link'         => [
                'rel'  => 'self',
                'href' => route('api.v1.articles.show', $article->id), // URL
            ],
            'comments'     => (int) $article->comments->count(), // 댓글 수
            'author'       => sprintf('%s <%s>', $article->author->name, $article->author->email), 
            'tags'         => $article->tags->pluck('slug'), // ['laravel', 'eloquent', '...']
            'attachments'  => (int) $article->attachments->count(), // 첨부파일 수
        ];
    }

    // $availableIncludes 에 정의된 값들에 대응되는 includeXxx 이름의 메소드를 모두 정의해 주어야 한다.
    // 이 메소드가 있어야 /v1/articles?include=comments 처럼 쿼리스트링을 통해서 하위 리소스를 포함하는 것이 가능해 진다.

    // /v1/articles?include=comments 처럼 QueryString 이 달려 있으면, 
    // config('api.params.limit'), config('api.params.order') 에 정의한 개수와 정렬방식의 Collection 으로 응답된다.
    // Article 와 Comment 의 관계는 morphMany() 로 정의되어 있어, 
    // Article 컨텍스트에서 Comment 는 항상 Collection 이 되어야 한다는 점을 상기하자.
    public function includeComments(Article $article, ParamBag $params = null)
    {
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $comments = $article->comments()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($comments, new \App\Transformers\CommentTransformer);
    }

    // 얘는 belongsTo() 관계라 Item 을 응답한다.
    // Simple Transformer 구현에서 봤던 내용과 크게 다르지 않다.
    public function includeAuthor(Article $article)
    {
        return $this->item($article->author, new \App\Transformers\UserTransformer);
    }

    // 역시 마찬가지. 위에서 Transform 한대로 배열 형태의 Tag Slug 들만 나가지만,
    // ?include=tags 이 있다면 Tag Collection 이 JSON 배열로 반환될 것이다.
    public function includeTags(Article $article, ParamBag $params = null)
    {
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $tags = $article->tags()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($tags, new \App\Transformers\TagTransformer);
    }

    // Article 과 Attachment 는 hasMany 관계로 연결되어 있기 때문에 Collection 을 응답하는게 맞다. 
    public function includeAttachments(Article $article, ParamBag $params = null)
    {
        list($limit, $offset, $orderCol, $orderBy) = $this->calculateParams($params);

        $attachments = $article->attachments()->limit($limit)->offset($offset)->orderBy($orderCol, $orderBy)->get();

        return $this->collection($attachments, new \App\Transformers\AttachmentTransformer);
    }
}
```

### Serializer

`league/fractal` 의 개발자인 Phil Sturgeon 의 포스트 ['The Importance of Serializing API Output'](https://philsturgeon.uk/api/2015/05/30/serializing-api-output/) 을 꼭 읽어 보자. 

MSDN 정의에 따르면,

> 직렬화 (==Serialization) 란 객체를 메모리 데이터베이스, 또는 파일 등에 저장하기 위한 목적으로 바이트 스트림으로 변환하는 행위를 말한다. 직렬화를 하는 이유는, 현재 객체의 상태를 그대로 저장했다가 필요할 때 다시 꺼내 쓰기 위한 목적이다. 반대 개념은 역직렬화 (==Deserialization) 이다.

![](https://i-msdn.sec.s-msft.com/dynimg/IC20067.jpeg)

가령 객체의 속성이 변경된 상태에서 나중에 이전 상태 그대로 다시 꺼내 쓰고 싶으면 어떻게 할것인가? 객체를 `new` 키워드로 다시 생성하고, 속성값을 변경해서 사용할 것인가? 아니다, 이때 필요한 것이 직렬화이다. 쉽게 직렬화란 객체를 스트링 형태로 변환해서 저장했다가, 부활시키는 것이라고 보면 된다.

API 에서 직렬화란 Transformer 에서 변경된 모델/데이터의 상태에서의 직렬화를 의미한다. API 에서 직렬화란 위에서 얘기한 상태의 재복원 보다는 데이터를 전달하는 형태에 더 의미를 둔다. (그럼 직렬화가 맞나요? 라고 따지지 말고 그냥 그렇다고 수용하자.) `league/fractal` 에서도 여러가지 직렬화 방식 (==[Serializer](http://fractal.thephpleague.com/serializers/)) 을 지원하는데,

-   `ArraySerializer`
    : Collection 을 응답할 경우에만 `data` 필드를 사용한다.
    
-   `DataArraySerializer`
    : Item 이든 Collection 이든 무조건 `data` 필드를 사용한다.
    
-   `JsonApiSerializer`
    : [JSON API](http://jsonapi.org/) 스펙에 정의된 응답 형식을 따른다. `type`, `id`, `attributes` 란 필드를 필수적으로 사용한다.
    
우리 실전 프로젝트에서는 가장 간단한 `ArraySerializer` 를 사용할 것이다. `config/api.php` 에서 원하는 다른 Serializer 로 바꿀 수도 있고, 이미 정의된 Serializer 형식을 넘어서서 자신만의 Custom Serializer 를 만들 수도 있다.

### Controller

이제 이론을 배웠으니, 우리의 `ArticleController` 를 변경하자.
 
```php
// app/Http/Controllers/ArticlesController.php

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('author:article', ['only' => ['update', 'destroy', 'pickBest']]);

        if (! is_api_request()) {
            // \App\Http\Controllers\Api\V1\ArticlesController 에서 이 컨트롤러를 상속할 것이므로,
            // API 에 필요 없는 부분은 (! is_api_request()) 로 제외 시켰다.
            $this->middleware('auth', ['except' => ['index', 'show']]);

            $allTags = taggable()
                ? Tag::with('articles')->remember(5)->cacheTags('tags')->get()
                : Tag::with('articles')->remember(5)->get();

            view()->share('allTags', $allTags);
        }

        parent::__construct();
    }
    
    public function index(FilterArticlesRequest $request, $slug = null)
    {
        // ...

        return $this->respondCollection($articles);
    }
    
    public function store(ArticlesRequest $request)
    {
        // ...
        
        return $this->respondCreated($article);
    }
    
    public function show($id)
    {
        // ...

        return $this->respondItem($article, $commentsCollection);
    }
    
    public function update(ArticlesRequest $request, $id)
    {
        // ...

        return $this->respondUpdated($article);
    }
    
    public function destroy(Request $request, $id)
    {
        // ...

        return $this->respondDeleted($article);
    }
    
    // ...
    
    protected function respondCollection(LengthAwarePaginator $articles)
    {
        return view('articles.index', compact('articles'));
    }

    protected function respondCreated(Article $article)
    {
        flash()->success(trans('common.created'));

        return redirect(route('articles.index'));
    }

    protected function respondItem(Article $article, Collection $commentsCollection = null)
    {
        return view('articles.show', [
            'article'         => $article,
            'comments'        => $commentsCollection,
            'commentableType' => Article::class,
            'commentableId'   => $article->id,
        ]);
    }

    protected function respondUpdated(Article $article)
    {
        flash()->success(trans('common.updated'));

        return redirect(route('articles.show', $article->id));
    }

    protected function respondDeleted(Article $article)
    {
        flash()->success(trans('common.deleted'));

        return redirect(route('articles.index'));
    }
}
```

앞선 강의에서 언급했다시피, Web Response 랑 API Response 부분은 로직에서 큰 차이가 없다, 다행시 아직까지는... 다만 차이가 나는 부분은 HTML 을 응답하냐?, JSON 을 응답하느냐? 의 차이만 있을 뿐이다. DRY (Don't Repeat Yourself) 원칙에 따라 로직을 최대한 사용하면서, `respondCollection()`, `respondCreated`, `...` 의 응답 메소드만 다르게 정의한 것을 볼 수 있다.

이제 위 클래스의 응답 메소들을 Override 하는 API 응답 메소드들을 만들어 보자. 응답에 사용한 메소드들은 `appkr/api` 의 `Appkr\Api\Http\Response` 클래스의 메소드들이다.

-   `withPagination(\Illuminate\Pagination\LengthAwarePaginator $paginator, $transformer = null)`
    인자로 넘겨 받은 `Paginator` 객체와 `Transformer` 객체를 이용하여, 페이징이 포함된 JSON 콜렉션을 응답한다.

-   `withItem(\Illuminate\Pagination\LengthAwarePaginator $paginator, $transformer = null)`
    역시, 인자로 넘겨 받은 `Paginator` 객체와 `Transformer` 객체를 이용하여, 단일 아이템에 대한 JSON 을 응답한다.
    
-   `created(\Illuminate\Database\Eloquent\Model|array\String $primitive = 'Created')`
    201 응답을 반환한다. 엘로퀀트 모델을 받으면 엘로퀀트 모델을 JSON 캐스팅해서 Response Body 에 덧붙이고, 문자열을 넘기면 'config/api.php' 의 `successFormat` 키에 지정된 형태로 JSON 응답을 한다.  
    
-   `success(array|string $message = 'Success')`
    200 응답을 반환한다. `created` 와 유사하다.

-   `noContent()`
    204 응답을 반환한다.
    
```
// app/Http/Controllers/Api/V1/ArticlesController.php

class ArticlesController extends ParentController
{
    public function __construct()
    {
        // 'auth' 대신 'jwt.auth' 미들웨어를 사용하는데, 미들웨어를 적용시키지 않을 메소드는 동일하다.
        // 읽기 요청인 'index' 와 'show' 를 제외했는데, 나중에 Rate Limit 로 시간당 요청 가능 횟수를 제한할 것이다.
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);

        parent::__construct();
    }

    // 부모 클래스를 Override 해서 JSON 응답을 반환한다.
    protected function respondCollection(LengthAwarePaginator $articles)
    {
        return json()->withPagination($articles, new ArticleTransformer);
    }

    protected function respondCreated(Article $article)
    {
        return json()->created();
    }

    protected function respondItem(Article $article, Collection $commentsCollection = null)
    {
        return json()->withItem($article, new ArticleTransformer);
    }

    protected function respondUpdated(Article $article)
    {
        return json()->success('Updated');
    }

    protected function respondDeleted(Article $article)
    {
        return json()->noContent();
    }
}
```

### Test

어떻게 나오는 지 보자. Article Collection 을 먼저 요청해 본다.

```HTTP
GET /v1/articles HTTP/1.1
Host: api.myproject.dev:8000
Accept: application/json
```

![](48-all-is-bad-img-01.png)

Article 개별 인스턴스를 요청한다. 그런데, 여기서는 `?include=comments:limit(2|0):order(id|desc),tags` 쿼리스트링을 덧 붙였다. 해석하자면, Comment 를 네스팅하되 0 개를 건너 뛰고 총 2개만, Tag 는 전체 콜렉션을 전부 응답해 달라는 요청이다.

```HTTP
GET /v1/articles/1?include=comments:limit(2|0):order(id|desc),tags HTTP/1.1
Host: api.myproject.dev:8000
Accept: application/json
```

![](48-all-is-bad-img-02.png)

이번에는 Article 을 생성하는 요청을 한다. 먼저 API_DOMAIN/auth/login 을 방문하여 JWT Token 을 얻어서, 이번 테스트 요청의 Authorization 헤더에 붙인다. title, content, tags[] 등의 내용을 입력하고 요청해 보자. 아무 내용 없는 상태로도 요청해 보면, 아마 422 Unprocessable Entity 에 에러가 있는 필드에 대한 설명이 담긴 JSON 응답을 받았을 것이다.

```HTTP
POST /v1/articles HTTP/1.1
Host: api.myproject.dev:8000
Accept: application/json
Authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL2FwaS5teXByb2plY3QuZGV2OjgwMDBcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNDUyNDM0MjU4LCJleHAiOjE0NTI0NDE0NTgsIm5iZiI6MTQ1MjQzNDI1OCwianRpIjoiNWM4ZjRhOTAxZWQ2YzljYTkxMjQ5NzU2NjVmZTMyODEifQ.bsLX0u5ZvAX2ZD3w1SSSGyhk6tg0F5q_C6nzR2Ez5Tg
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW

----WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="title"

New Title
----WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="content"

New Content
----WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="tags[]"

1
----WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="tags[]"

5
----WebKitFormBoundary7MA4YWxkTrZu0gW
```

![](48-all-is-bad-img-03.png)

나머지들은 스스로 테스트해 보자.

### Form Request

`App\Http\Controllers\ArticlesController::store()` 메소드에서 입력값 유효성 검사를 위해서 `App\Http\Requests\ArticlesRequest` 라는 [Form Request](https://laravel.com/docs/5.2/validation#form-request-validation) 인스턴스를 주입하고 있다 ([37강 - Article 기능 구현](37-articles.md)). 그런데, 이 Form Request 는 입력값 유효성 검사에 실패하면 422 JSON 응답을 반환하긴 하지만, 우리가 원하는 모양의 JOSN 포맷이 아니다. `App\Http\Requests\ArticlesRequest` 의 부모 클래스를 계속 따라가다 적절한 포인트를 찾아서 기존 응답 형식을 우리 형식에 맞도록 메소드 오버라이딩을 해 주자.

```
// app/Http/Requests/Reqeust.php

abstract class Request extends FormRequest
{
    // ...
    
    public function response(array $errors)
    {
        if (is_api_request()) {
            // API 요청인데, 입력값 유효성 검사에 실패했을 때, 그래서 response() 메소드에 왔을 때는  
            // 부모 클래스인 Illuminate\Foundation\Http\FormRequest::response() 를
            // Override 해서 여기서 바로 JSON 응답을 우리 API 응답 포맷에 맞도록 내 보낸다.
            return json()->unprocessableError($errors);
        }

        // parent::response() 를 사용하지 않고, 완전히 Overwriting 하였다.
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

    public function forbiddenResponse()
    {
        if (is_api_request()) {
            // 역시 위와 동일하다.
            return json()->forbiddenError();
        }

        return response('Forbidden', 403);
    }
}
```

### Integration Test

특히 API 의 경우에는 UI 가 없어서 육안 테스트가 번거로울 뿐 아니라, 수정할 일도 많아서 통합 테스트를 작성하는 것이 좋다. 이번 강좌에서 테스트 코드 구현에 대한 내용은 설명하지 않지만, tests 디렉토리 아래에 있는 테스트 코드들을 살펴보도록 하자.

```bash
$ phpunit
```

![](48-all-is-bad-img-04.png)

테스트 코드를 짜는 과정에서 `App\Http\Controllers\ArticlesController::update()` 동작 관련 몇가지 버그를 잡았다.

```php
// app/Http/Requests/ArticlesRequest.php

public function rules()
{
    $rules = [];

    if ($this->isUpdate()) {
        // update 요청일 때와 아닐 때로 유효성 검사 규칙을 분리했다.
        $rules = ['tags' => ['array']];
    } else {
        $rules = [
            'title'   => 'required',
            'content' => 'required',
            'tags'    => 'required|array'
        ];
    }

    return $rules;
}
```

```php
// app/Http/Requests/Request.php

protected function isUpdate()
{
    $needle = ['put', 'patch'];

    return in_array(strtolower($this->input('_method')), $needle)
        or in_array(strtolower($this->header('x-http-method-override')), $needle)
        // _method=PUT 등으로 메소드 오버로딩을 하지 않아도 되는 클라이언트를 위해 아래 한줄을 보강했다.
        or in_array(strtolower($this->method()), $needle);
}
```

```php
// app/Http/Controllers/ArticlesController.php

public function update(ArticlesRequest $request, $id)
{
    // If Check 가 추가되었다. tags 필드를 넘기지 않으면 에러가 나므로...
    if ($request->has('tags')) {
        $article->tags()->sync($request->input('tags'));
    }
    // ...
}
```

또, Article 모델에 접근제한하는 부분에서도, API 요청일 경우에 적절한 JSON 응답을 하도록 고쳤다.

```php
// app/Http/Middleware/AuthorOnly.php

public function handle(Request $request, Closure $next, $param)
{
    //...
    if (! $model::whereId($modelId)->whereAuthorId($user->id)->exists() and ! $user->isAdmin()) {
        if (is_api_request()) {
            return json()->forbiddenError();
        }

        return back();
    }

    return $next($request);
}
```

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [47강 - 중복 제거 리팩토링](47-dry-refactoring.md)
- [49강 - API Rate Limit](49-rate-limit.md)
<!--@end-->