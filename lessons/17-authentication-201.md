# 17강 - 라라벨에 내장된 사용자 인증

16강에 이어 이번 강좌에서는 라라벨에 딸려서 배포(Shipping)되는 인증 기능을 살펴 보자. 내장된 사용자 인증 기능은 특정 시간내에 로그인 실패가 많으면 로그인을 제한하는 쓰로틀링 기능이 포함되어 있고, 좀 더 나이스하게 코드를 구조화해 놓았다. 핵심은 16강과 동일하다.

## Controllers

app/Http/Controllers/Auth 디렉토리를 보면 2개의 파일이 보인다.
- AuthController.php - 새 사용자 등록과 로그인/로그아웃 로직을 포함하고 있다.
- PasswordController.php - 패스워드 리셋 로직을 포함하고 있다. (이 강좌에서는 다루지 않는다.)

## Routes

app/Http/routes.php 를 열어 아래 내용을 추가 하자.

```php
Route::get('/', function() {
    return 'See you soon~';
});

Route::get('home', [
    'middleware' => 'auth',
    function() {
        return 'Welcome back, ' . Auth::user()->name;
    }
]);

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
```

**`참고`** `AuthController`를 눈 씻고 찾아 봐도, `getLogin()` 메소드를 찾을 수 없다. 이들 메소드는 `Illuminate\Foundation\Auth\AuthenticateUsers` 트레이트 에서 찾을 수 있다.

## Views

뷰는 라라벨에 기본 포함되어 있지 않다. [공식 문서](http://laravel.com/docs/authentication#included-views)를 참조해서 뷰를 만들자.

### 로그인 폼 - resources/views/auth/login.blade.php

```html
@extends('master')

@section('content')
  <form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div>
      Email
      <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
      Password
      <input type="password" name="password" id="password">
    </div>

    <div>
      <input type="checkbox" name="remember"> Remember Me
    </div>

    <div>
      <button type="submit">Login</button>
    </div>
  </form>
@stop
```

### 사용자 등록 폼 - resources/views/auth/register.blade.php

```html
@extends('master')

@section('content')
  <form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div>
      Name
      <input type="text" name="name" value="{{ old('name') }}">
    </div>

    <div>
      Email
      <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
      Password
      <input type="password" name="password">
    </div>

    <div>
      Confirm Password
      <input type="password" name="password_confirmation">
    </div>

    <div>
      <button type="submit">Register</button>
    </div>
  </form>
@stop
```

## 실험해 보자.

서버를 부트업하고, 'auth/register' 와 'auth/login' Route를 방문해 보자. 사용자를 등록하고, 로그인/로그아웃('auth/logout') 해보자.

**`참고`** 라라벨에서 기본 제공하는 인증에서 패스워드는 최소 6자리 이상이어야 한다. `App\Http\Controllers\Auth\AuthController@validator` 메소드를 보면 `'password' => 'required|confirmed|min:6'` 라고 유효성 검사 규칙이 지정되어 있는 것을 확인할 수 있다. 유효성 검사, 뷰에 쓰인 `old()` 함수에 대해서는 뒤에서 다시 살펴보도록 하자.

**`참고`** 다음으로 넘어가기 전에 로그인 뷰에서 소스보기를 해 보자. `<input type="hidden" name="_token" value="jPR...nO2">` 란 라인을 볼 수 있다. 바로 `csrf_field()` Helper Function이 CSRF 공격을 막기 위해 만든 토큰을 담은 숨은 입력 폼이다. 13강에서 공부한 내용이다. `{!!  !!}`은 '<', '>' 같은 특수문자의 '&lt;', '&gt;' Escaping 되는 것을 막기 위한 블레이드의 Interpolation 문법이다.
<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [16강 - 사용자 인증 기본기](16-authentication.md)
- [18강 - 모델간 관계 맺기](18-eloquent-relationships.md)
<!--@end-->

