# 16강 - 사용자 인증 기본기

좀 과장해서, 어떤 프로젝트든 로그인이 거의 업무량의 절반이라고들 한다. 그만큼 User 모델과 연결된 기능들이 많다는 의미로 이해하면 되겠다. 서비스에 들어온 사용자를 식별하는 방법을 인증(Authentication)이라 한다. 바꾸어 말하면, 사용자가 제시한 신분증이 DB에 저장된 User 정보와 동일한지 확인하고, 맞으면 통과시켜 주면서, 세션이라는 명찰을 하나 나눠 주는 행위라 이해할 수 있다. 

이번 강좌에서는 라라벨이 제공하는 메소드들을 이용해서 사용자 인증을 직접 구현해 보고, 다음 강좌에서 라라벨에 포함되어 배포(Shipping)되는 네이티브 인증 구현을 살펴보자.
 
# User 모델

라라벨에는 User 모델과 마이그레이션이 이미 포함되어 있다. app/Users.php를 살펴보자.
 
```php
class User extends Model implements ...
{
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
```

`$fillable` 속성을 통해 name, email, password 필드는 MassAssign이 가능하다는 것을 알 수 있다. `$hidden` 속성은 외부에 노출되지 않는 필드들을 정의한 것이다. 그럼, 사용자를 하나 만들어 보자.

```bash
$ php artisan tinker
>>> $user = new App\User;
>>> $user->name = 'John Doe';
>>> $user->email = 'john@example.com';
>>> $user->password = bcrypt('password');
>>> $user->save();
```

**`참고`** 마이그레이션을 실행한 적이 없다면, `$ php artisan migrate` 을 먼저 하자.

잘 생성되었나 확인해 보자.

```bash
# $hidden 속성에 의해 password와 remember_token은 노출되지 않는다.
$ php artisan tinker
>>> App\User::first();
=> App\User {#684
     id: 1,
     name: "John Doe",
     email: "john@example.com",
     created_at: "2015-11-10 09:20:09",
     updated_at: "2015-11-10 09:20:09",
   }
```

## 사용자를 인증해 보자.

기본을 이해하기 위해 이번에도 app/Http/routes.php를 이용하자.

```php
Route::get('auth', function () {
    $credentials = [
        'email'    => 'john@example.com',
        'password' => 'password'
    ];

    if (! Auth::attempt($credentials)) {
        return 'Incorrect username and password combination';
    }

    return redirect('protected');
});

Route::get('auth/logout', function () {
    Auth::logout();

    return 'See you again~';
});

Route::get('protected', function () {
    if (! Auth::check()) {
        return 'Illegal access !!! Get out of here~';
    }

    return 'Welcome back, ' . Auth::user()->name;
});
```

서버를 부트업하고 브라우저에서 'auth' Route로 접근해 보자. 인증이 성공되고 바로 'protected' Route로 넘어가는 것을 확인할 수 있다. 

이해를 돕기 위해, 'auth' Route의 Closure에다 인증에 필요한 정보를 하드코드롤(`$credentials`) 박았다. 실전에서는 `Request::input('email')`과 같은 식으로 받아야 한다. **`Auth::attempt()` 메소드에 `$credentials`를 넘기면, 단순히 true/false만 리턴하는 것이 아니라, 백그라운드에서는 서버에 로그인한 사용자의 세션도 생성한다**는 것을 기억하자.

정상적으로 로그인되고 Redirect되어 'protected' Route로 들어 왔다면 사용자의 세션 정보가 서버에 생성되었을 것이다. 사용자가 로그인되어 있는 지 확인하는 메소드가 `Auth::check()` 이다. 실험을 위해 브라우저에서 'auth/logout' Route로 접근하여 (`Auth::logout()`) 사용자를 로그아웃 시킨 후, 'protected' Route로 다시 접근해 보자.
 
## 'auth' 미들웨어 

여기서 잠깐! 만약 로그아웃한 후 'protected' Route를 방문했는데 if 블럭이 없었다면 어떻게 될까? `ErrorException-Trying to get property of non-object` 가 발생했을 것이다. 로그인 되지 않았기 때문에 `Auth::user()` 는 null 이고, `null->name`은 성립되지 않기 때문에 예외가 발생한 것이다. if 블럭을 사용하지 않고 Null Pointer를 예방하는 방법이 바로 'auth' 미들웨어 이다.

'auth' 미들웨어를 사용하도록 app/Http/routes.php 를 수정해 보자.
 
```php
Route::get('protected', [
    'middleware' => 'auth',
    function () {
        return 'Welcome back, ' . Auth::user()->name;
    }
]);
```
 
로그아웃한 후 'protected' Route로 다시 접근해 보자.

## NotFoundHttpException

'auth' 미들웨어는 app/Http/Middleware/Authenticate.php 에 위치하고 있다. 코드를 들여다 보면, 로그인이 안되어 있으면 'auth/login' 으로 Redirect 하도록 되어 있다. 해당 Route가 없어서 그런 것이니, 만들자. app/Http/routes.php 를 다시 수정하고, 'protected' Route를 방문해 보자.

```php
Route::get('auth/login', function() {
    return "You've reached to the login page~";
});
```

**`참고`** 방금 본 'auth' 미들웨어는 Route by Route로 사용자가 선택해서 적용할 수 있는 'Route 미들웨어'라 한다. 반면, 앞 강좌에서 보았던 'csrf' 미들웨어는 'HTTP 미들웨어'라 칭한다. HTTP 미들웨어는 모든 HTTP 요청이 무조건 거쳐야 하는 글로벌 미들웨어라고 생각하면 된다.

---

- [15강 - 중첩된 리소스](15-nested-resources.md)
- [17강 - 라라벨에 내장된 사용자 인증](17-authentication-201.md)