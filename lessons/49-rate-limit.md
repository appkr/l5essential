# 실전 프로젝트 3 - RESTful API

## 49강 - API Rate Limit

### Why?

API 엔드포인트의 사용량을 제한하는 이유는 여러가지이다.

-   DDoS 공격으로 부터 서비스를 보호한다.
-   API 클라이언트 간에 API Resource 의 사용에 공평성을 제공한다. 가령, 소수의 Heavy 클라이언트로 인해 다른 선량한 클라이언트가 피해를 입지 않도록 말이다.
-   API 를 통해 받는 데이터 자체가 돈과 직결되는 경우, 그 Business Model 로서의 역할을 한다.

### 적용

라라벨 5.2 를 사용하고 있다면, `\Illuminate\Routing\Middleware\ThrottleRequests` 라는 HTTP Middleware 가 이미 내장되어 있다. 우리의 `ArticlesController` 에 적용하기 전에 Route Middleware 로 잘 등록되어 있나 확인해 보자.

```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    // ...
    
    protected $routeMiddleware = [
        // ...
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
```

적용하자.

```php
// app/Http/Controllers/Api/V1/ArticlesController.php


class ArticlesController extends ParentController
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
        $this->middleware('throttle:60,1');

        parent::__construct();
    }
    
    // ...
}
```

아주 간단하다. `throttle:60,1` 이란, 클라이언트당 1 분에 60번 요청을 허용하겠다는 의미이다. `throttle:3,1` 정도로 수정하고 GET /v1/articles 요청을 여러번 연달아 해 보자. 네번째 요청에서 아래와 같은 응답을 받고, 1분 후에 다시 사용 가능한 상태가 되는 것을 확인할 수 있다.  
  
```HTTP
HTTP/1.1 429 Too Many Requests
Host: api.myproject.dev:8000
# ...
Retry-After: 60
X-RateLimit-Limit: 3
X-RateLimit-Remaining: 0
```

### 다듬기

API 응답 포맷의 일관성을 위해, `text/application` 이 아니라 `application/json` 으로 응답하도록 수정하자. 라라벨 내장 `\Illuminate\Routing\Middleware\ThrottleRequests` 를 상속 받아 `\App\Http\Middleware\ThrottleApiRequests` 를 만들고 여기서 Rate Limit 에 걸렸을 경우 JSON 을 응답하게 수정하면 될 것이다.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;

class ThrottleApiRequests extends ThrottleRequests
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            return json()->setHeaders([
                'Retry-After' => $this->limiter->availableIn($key),
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
            ])->tooManyRequestsError();
        }

        // ...
    }
}
```

새로 만들어진 HTTP Middleware 를 등록하고, `ArticlesController` 에 적용하자.

```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    // ...
    
    protected $routeMiddleware = [
        // ...
        'throttle.api' => \App\Http\Middleware\ThrottleApiRequests::class,
    ];
}
```

```php
// app/Http/Controllers/Api/V1/ArticlesController.php


class ArticlesController extends ParentController
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
        $this->middleware('throttle.api:60,1');

        parent::__construct();
    }
    
    // ...
}
```

이제 `throttle.api:3,1` 정도로 수정하고 테스트해 보면, 우리의 일관된 포맷의 JSON 응답을 볼 수 있을 것이다.
  
```HTTP
HTTP/1.1 429 Too Many Requests
Host: api.myproject.dev:8000
Content-Type: application/json
# ...
Retry-After: 60
X-RateLimit-Limit: 3
X-RateLimit-Remaining: 0

{
  "error": {
    "code": 429,
    "message": "Too Many Requests"
  }
}
```

### 추가 적용

API 컨트롤러들, `SessionsController`, `UsersController`, `PasswordsController` 등에도 적용하자. 필자의 경우 `throttle.api:10,1` 로 정의했다. 상용에서는 서비스를 하면서 여러가지 통계 정보들에 기반해서 클라이언트 당 허용할 적절한 Rate Limit 값을 정해야 한다. 

**`참고`** Laravel 5.2 를 이용하지 않는다면, [`graham-campbell/throttle`](https://github.com/GrahamCampbell/Laravel-Throttle) 패키지를 이용하자. 

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [48강 - all() is bad](48-all-is-bad.md)
- [50강 - 리소스 id 난독화](50-id-obfuscation.md)
<!--@end-->