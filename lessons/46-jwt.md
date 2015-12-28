# 실전 프로젝트 3 - RESTful API

## 46강 - JWT 를 이용한 인증

### HTTP Stateless 특성에 대한 이해

HTTP 의 가장 큰 특징은 무상태 (==Stateless) 이다. 무슨 의미냐하면, 클라이언트 A 에서 Request A 와 Request B 를 했을 때, 서버 입장에서는 Request A 와 B 가 같은 클라이언트 A 로 부터의 요청인지 알 수 없다는 것이다. 공용 컴퓨터가 아닌 이상 "클라이언트"는 "사용자"랑 동일한 의미이다. 그럼, 서버에서 사용자를 어떻게 인식하는가? 라는 의문이 생긴다.
  
Request A 와 B 가 같은 클라이언트라는 것을 서버에게 말하는 방법은 Cookie 를 이용하거나 Url Paremeter (http:://example.com/?user=foo) 를 이용하는 방법 등이 있을 것이다. 그런데, 문제는 클라이언트 쪽에서 사용자의 신분을 조작하기가 너무 쉽다는 것이다.

이 문제를 해결하기 위해, HTTP 를 다루는 웹 서버 및 웹 프레임웍에서는 세션이라는 개념을 사용한다. 서버에서 Request A 에 대해서 고유한 세션 'key=foo' 를 생성하고, 클라이언트 A 에게 알려주면, 클라이언트 A 는 Request B 를 날릴 때 'key=foo' 를 달아서 "난 Request A 를 했던 클라이언트와 같은 놈이요" 라고 서버에게 말하는 식이다. 클라이언트 A 에서 사용자 'john@example.com' 이 자신의 신분을 서버에게 밝히면, 서버는 자신의 저장장치에서 사용자 'john@example.com' 의 신분을 확인하고 세션 정보에 기록해 두어 사용자까지도 인식하는 것이다. 이 과정을 우리는 흔히 "로그인"이라 한다.

실전에서 세션은 서버에서 생성되고, 클라이언트와 HTTP Cookie 메커니즘을 이용해서 교환된다. 아래 그림을 보자. 

![](46-jwt-img-01.png)

### API 인증 방법

위 그림은 브라우저의 경우이다. 브라우저는 쿠키를 파싱하여 내부 저장소에 보관하고, 다음 요청때 저장된 쿠키가 만료되지 않았다면 달아서 보내는 동작을 한다. 

그런데, API 컨텍스트에서는 클라이언트가 꼭 브라우저라 할 수 없다. Android/iOS/PC 와 같은 다양한 플랫폼이 API 서버에 접속하게 되며, 심지어는 CURL 과 같은 콘솔형 HTTP Client 가 될 수도 있는데, 위에서 설명한 쿠키 메카니즘이 동작한다고 보장할 수 없다.

그래서, 일반적으로 아래와 같은 방법으로 API 클라이언트로 부터의 요청에 대한 유효성 검사를 수행한다.
 
1.  HTTP Basic 인증
    
    클라이언트에서 API 서버에 리소스 요청을 할 때, `Authorization: Basic xyz` 를 달아서 보내는 식이다. 여기서 'xyz' 는 `base64_encode('john@example.com:password')` 처럼, 사용자 인증을 위한 username:password 를 Base64 인코딩한 문자열이다. 이 방식의 좋은 점은 사용하기 쉽다는 점인데, 나쁜점은 보안에 굉장히 취약하다는 점이다. 네트워크 구간에서 탈취되면 그냥 빵 털리고, 서비스는 안드로메다로 가게 된다. https 를 이용하여 HTTP Header 의 탈취를 원천 봉쇄하는 방법이 있기는 하나, username 및 password 대한 만료 기간도 없고, 클라이언트 앱이 사용자 인증을 위한 정보를 어딘가에 저장을 해야한다는 위협도 존재한다.  
      
    ```bash
    $ php artisan tinker
    >>> base64_encode('john@example.com:password');
    => "am9obkBleGFtcGxlLmNvbTpwYXNzd29yZA=="
    >>> base64_decode('am9obkBleGFtcGxlLmNvbTpwYXNzd29yZA==');
    => "john@example.com:password"
    ```

    **`참고`** 따지고 보면, HTML 폼을 이용한 사용자 인증도 마찬가지다. 따라서, 보안 전문가들은 https 를 항상 권장한다. 다만, API 와 달리 HTML 폼에서는 로그인을 위한 POST 요청때만 사용자 정보가 평문으로 날아간다. https 을 위한 SSL 인증서가 돈이기 때문에, 보안과 비용 사이에서 의사결정을 해야 한다. 해커들 입장에서도 사용자가 좀 되어야 털 이유가 있으니, SSL 인증서 도입 시기를 적절하게 결정해야 한다. 

2.  Oauth 인증

    조대협님의 [REST API 의 이해와 설계 #3 API 보안](http://bcho.tistory.com/955) 편을 읽어 보자. 이 강좌에서 소셜 인증에 사용하는 기술이 Oauth2 이다. API 사용자 인증을 위해 자체 Oauth 인증 서버를 구축하고자 한다면 [`league/oauth2-server`](https://github.com/thephpleague/oauth2-server) 를 이용하자. 단점은 복잡하고 무겁다는 점이다. 이름만 대면 아는 대형 서비스들은 대부분 Oauth 를 이용한다는 점을 기억하자. 사용자가 많아 지면, 1 번이나 3 번으로 부터 적절한 시기에 Oauth 로 마이그레이션을 해야 한다.


3.  JWT 인증

    역시 조대협님의 [REST JWT 소개 #1 개념 소개](http://bcho.tistory.com/999) 을 읽어 보시기 바란다. 한마디로 말하자면, 사용자를 인식하기 위한 정보 (e.g. 사용자 ID) 가 이미 담겨 있는 변조가 불가능한 토큰이라 할 수 있다. 클라이언트가 API 서버에 리소스를 요청할 때 이 토큰을 `Authorization: Bearer header.payload.signature` 와 같은 HTTP Header 로 전달하고, 서버는 이 값을 해독하여 사용자를 인식하는 방식이다. 1 번과 2 번 사이에 있는, 즉, 무겁지 않지만 보안에도 강한 방식이라 할 수 있다.


### JWT 패키지 

스펙을 이해하고 JWT 를 직접 구현한다는 것은 엄청난 일이다. 이미 만들어진, 그리고 커뮤니티에서도 검증된 [`tymon/jwt-auth`](https://github.com/tymondesigns/jwt-auth) 패키지를 끌어 와서 사용하자.
 
#### 설치
 
```bash
$ composer require "tymon/jwt-auth:0.5.*"
```

패키지 매뉴얼에 써진대로 서비스 프로바이더와 Facade 를 추가하자.

```php
// config/app.php

'providers' => [
    // ...
    Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,
],

'aliases' => [
    // ...
    'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
    'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
];
```

패키지에서 제공하는 config 파일을 배포하고, 암호화 알고리즘에 사용할 씨드 키를 생성하자.

```bash
$ php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
$ php artisan jwt:generate
```

#### 설정

설정 파일을 열어 보면..

```php
// config/jwt.php

return [ 
    'ttl' => 120,
    'refresh_ttl' => 20160,
    'identifier' => 'id',
    // ...
];
```

중요한 설정만 살펴 보자.

-   `ttl` 은 토큰의 유효 기간을 위한 설정이다. 120 이라고 한 것은 2 시간을 뜻하며, 토큰을 발행하고 난 이후 2 시간 동안 클라이언트와 서버간에 한번이라도 성공적인 인증이 없었다면 토큰은 만료된다는 의미이다. 바꾸어 말하면, 2시간 내에 한번이라도 서버에서 발급한 토큰을 이용하여 클라이언트가 서버 측에 `Authorization: Bearer header.payload.signature` HTTP Header 를 달아서 리소스 요청을 했고, 인증에 성공했다면 그 시점으로 부터 다시 2 시간 동안 토큰이 유효해진다는 의미이다.

-   `refresh_ttl` 은 처음 발급 받은 토큰을 새 토큰으로 교체 발행 (==Token Refresh) 받을 수 있는 기간에 대한 설정이다. 가령 2 주 동안 클라이언트와 서버가 인터랙션이 없었다면, 처음 발급 받은 토큰을 이용해서 새 토큰을 교체 받을 수 없게 되며, 사용자이름과 비밀번호를 이용해서 새로 로그인하고 토큰을 발급 받아야 한다. `ttl` 로 지정한 2 시간을 지나서 API 서버에 리소스 요청을 하면, 401 응답을 받게 되고, 이 때 클라이언트는 토큰 교체를 위한 Endpoint 로 요청해서 토큰을 교체 받은 후, 교체 받은 토큰으로 리소스 요청을 계속 하면 된다. `refresh_ttl` 역시 토큰을 한번 교체하면 토큰의 교체 가능 기간은 다시 2주로 리셋된다.

    보안 전문가들은 `ttl` 을 짧게 가져가고, Token Refresh 할 것을 권장한다. Token 이 털리더라도 `ttl` 로 지정한 시간이 지나면, 해커의 노력이 허무해지기 때문이다. 
    
-   `identifier` 는 토큰의 Subject 필드 값이며, 사용자 인증에 사용되는 필드 값이다. 아래는 User 1 번에 대한 토큰을 디코딩한 것인데, `Subject` 부분을 살펴 보자. 가령 `identifier => email` 로 지정했다면 `Subject::$value` 는 '1' 이 아니라 'john@example.com' 이었을 것이다.

    ```php
    Payload {#283
      -claims: array:6 [
        0 => Subject {#251
          #name: "sub"
          -value: 1
        }
        1 => Issuer {#252
          #name: "iss"
          -value: "http://api.myproject.dev:8000/auth/login"
        }
        2 => IssuedAt {#250
          #name: "iat"
          -value: 1451288973
        }
        3 => Expiration {#280
          #name: "exp"
          -value: 1451296173
        }
        4 => NotBefore {#281
          #name: "nbf"
          -value: 1451288973
        }
        5 => JwtId {#282
          #name: "jti"
          -value: "e7e045b1c2f5c716b0ec19ac184344e6"
        }
      ]
    }
    ```

### JWT Integration

앞서도 얘기했다시피 꼭 기억해야할 점은, API 서비스는 HTTP Cookie 메커니즘을 이용할 수 없는 진정한 Stateless 라는 것이다. 그래서, 매번 리소스 요청시마다 서버 측에서는 HTTP Header 에 달린 token 에 해당하는 User 를 Resolve 해야 한다.

#### 미들웨어

`tymon/jwt-auth` 의 Integration 가이드에 제시된 패키지 내장 `Tymon\JWTAuth\Middlewar\GetUserFromToken`미들웨어를 사용하지 말고, 나름의 미들웨어를 만들 것이다. 왜냐하면, JSON 응답 포맷을 마음대로 변경할 수 없어서 이다.

```php
// app/Http/Middleware/GetUserFromToken.php

<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class GetUserFromToken extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            // HTTP Header 나 URL Parameter 에 token 값이 없으면 400 JWTException 을 던진다. 
            throw new JWTException('token_not_provided', 400);
        }

        if (! $user = $this->auth->authenticate($token)) {
            // token 값으로 사용자 로그인을 한다. 해당 사용자가 없으면 404 JWTException 을 던진다. 
            throw new JWTException('user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);
        
        // 미들웨어는 Chain of Responsibility 디자인 패턴의 구현이다
        // @see https://en.wikipedia.org/wiki/Chain-of-responsibility_pattern
        return $next($request);
    }
}
```

혹시, 필자가 꼭 설명할 내용을 빼먹고 개떡같이 말해도, 이제 독자 여러분들은 찰떡같이 이해할 수 있다고 생각한다. 다행히 빼먹지 않았다, 'Kernel.php' 에 방금 만들 미들웨어를 등록해주는 일 말이다.
 
```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // ...
        'jwt.auth'    => \App\Http\Middleware\GetUserFromToken::class,
        'jwt.refresh' => \App\Http\Middleware\RefreshToken::class,
    ];
}
```

`jwt.auth` 란 별칭은 곧 써야 하니 잘 기억해 두자. `RefreshToken` 미들웨어도 만들었지만 지금 당장은 사용할 계획은 없다. 강좌를 진행하면서 필요할 일이 있기를..
  
#### 컨트롤러

앞 강에서 JWT 관련 Todo 주석만 달아놓고 구현하지 않은 부분을 구현할 것이다.
 
```php
// app/Http/Controllers/SessionsController.php

class SessionsController extends Controller
{
    public function store(Request $request)
    {
        // ...
        
        $token = is_api_request()
            // Auth::once() 를 JWTAuth::attempt() 로 변경했다.
            // 이 메소드는 HTTP Request Header 의 token 을 파싱하여 일회용 로그인을 하는 역할을 한다.
            ? \JWTAuth::attempt($request->only('email', 'password'))
            : Auth::attempt($request->only('email', 'password'), $request->has('remember'));
            
        if (! $token) {
            return $this->respondLoginFailed();
        }

        return $this->respondCreated($request->input('return'), $token);
    }
    
    // $token 인자가 추가 되었다.
    protected function respondCreated($return = '', $token = '') {/*...*/}
}
```

`ParentController` 의 생성자에서 지정한 미들웨어를, API 인증 관련 모든 컨트롤러에서 `$this->middleware = [];` 로 무효화 시켰다. 이후 이 문서에 포함된 코드에서 생성자는 생략한다.

```php
// app/Http/Controllers/Api/SessionsController.php

class SessionsController extends ParentController
{
    public function __construct()
    {
        // ParentController 의 미들웨어 정의 무력화.
        $this->middleware = [];
        parent::__construct();
    }
    
    // ...
    
    protected function respondCreated($return = '', $token = '')
    {
        return response()->json([
            'code' => 201,
            'message' => 'success',
            // 인자로 넘겨 받은 token (JSON Web Token) 을 반환한다.
            // 클라이언트 사이드에서는 이 토큰을 저장하고 있다가 
            // Resource 요청시 Authorization Header 에 사용해야 한다.
            'token' => $token,
        ], 201);
    }
```

`UsersController` 에서는 `JWTAuth::fromUser()` 메소드를 사용하고 있다.

```php
// app/Http/Controllers/Api/UsersController.php

class UsersController extends ParentController
{
    // ...
    
    protected function respondCreated(User $user)
    {
        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => \JWTAuth::fromUser($user),
        ], 201);
    }
}
```

Postman 을 구동하고 로그인을 해 보자. 응답으로 받은 `token` 은 곧 사용해야 하니, 어딘가에 저장해 두자, 마치 API 클라이언트가 자체 저장소에 token 을 저장해 놓는 것 처럼.

![](46-jwt-img-02.png)

#### Exception 처리

`tymon/jwt` 는 라라벨의 철학을 따라, 여러가지 Exception 을 던지게 구현되어 있다, 우리가 자체 구현한 미들웨어에서도 그렇고. 척하면 착, 'app/Exceptions/Handler.php' 가 생각나야 한다.

```php
// app/Exceptions/Handler.php

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        // ...
        // 이 부분이 없으면 storage/logs/laravel.log 에 기록될 뿐 아니라,
        // production 환경에서는 Slack 으로 Exception 이 리포트된다.
        TokenExpiredException::class,
        TokenInvalidException::class,
        JWTException::class,
    ];
    
    public function render($request, Exception $e)
    {
        // ...
        
        if (is_api_request()) {
            // Exception 별로 메시지를 다르게 처리한다.
            // 특히, 같은 400, 401 이라도 클라이언트가 이해하고 다음 액션을 취할 수 있는
            // 메시지를 주는 것이 중요하다. 해서 xxx_yyy 식의 영어 메시지를 쓰고 있다.
            if ($e instanceof TokenExpiredException) {
                $message = 'token_expired';
            } else if ($e instanceof TokenInvalidException) {
                $message = 'token_invalid';
            } else if ($e instanceof JWTException) {
                $message = $e->getMessage() ?: 'could_not_create_token';
            } else if ($e instanceof Exception){
                $message = $e->getMessage() ?: 'Something broken :(';
            }

            $code = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : $e->getCode();

            return response()->json([
                'code' => $code ?: 400,
                'errors' => $message,
            ], $code ?: 400);
        }

        return parent::render($request, $e);
    }
}
```

### JWT 적용 및 테스트

실제 Resource 를 처리 하는 'GET /v1/articles' Route 와 `ArticlesController` 를 만들고, JWT 미들웨어를 적용해 보자. 

```php
// app/Http/routes.php

Route::group(['domain' => $domain, 'as' => 'api.', 'namespace' => 'Api'], function() {
    // ...
    
    Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function() {
        // ...

        Route::resource('articles', 'ArticlesController', ['only' => ['index']]);
    });
});
```

`__construct()` 생성자에서 `$this->middleware('jwt.auth');` 를 적용한 것을 눈여겨 봐야 한다. 우선 테스트를 위한 임시 컨트롤러이므로 아래와 같이 작성한 것이고, 나중에 기존에 만들었던 컨트롤러를 상속하고 수정할 것이다.

```php
// app/Http/Controller/Api/V1/ArticlesController.php

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');

        parent::__construct();
    }

    public function index()
    {
        return \App\Article::all();
    }
}
```

token 없이 'GET /v1/articles' 요청을 하거나, token 이 틀리면 아래와 같이 된다.

![](46-jwt-img-03.png)

테스트를 위해 좀 전에 저장해 두었던 토큰이 필요하다.

![](46-jwt-img-04.png)

인증이 완료되었으니, 다음 강좌에서는 지저분한 코드들을 좀 정리하도록 하자.

**`주의`** Apache 웹 서버를 사용한다면 `tymon/jwt-auth` 패키지의 가이드 대로 아래 내용을 꼭 추가하자. 필자의 라이브 데모 서버에서도 방금 겪은 문제이다.
 
```apache
<IfModule mod_rewrite.c>
    # ...

    # Handle Authorization Header...
    RewriteCond %{HTTP:Authorization} ^(.*)
    RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
</IfModule>
```

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [45강 - 기본 구조 잡기](45-api-big-picture.md)
- [47강 - 중복 제거 리팩토링](47-dry-refactoring.md)
<!--@end-->