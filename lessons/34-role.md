# 실전 프로젝트 2 - Forum

## 34강 - 사용자 역할

역할 기능을 이용해 RBAC (== Role Based Access Control)을 구현할 것이다. 앞에서 배운 'auth' 미들웨어가 할 수 있는 것은 특정 엔드포인트에 들어가려면 로그인(== Authentication, 인증)이 필요하다 정도라면, 접근 제어 또는 권한 부여(== Authorization 또는 Access Control)는 특정 엔드포인트는 admin 역할을 가진 사용자만 들어갈 수 있도록 할 수 있다. 우리 프로젝트를 예로 들면, 포럼 글은 로그인한 사용자 누구나 볼 수 있지만, 수정이나 삭제는 admin 또는 소유자만 할 수 있도록 하는 것이 권한 부여의 기능이다. 

### 패키지 설치

사용자에게 역할을 부여하기 위해 bican/roles 패키지를 이용할 것이다. 라라벨에도 [Authorization](http://laravel.com/docs/authorization) 기능을 지원하고 있으나, 필자에겐 사용법이 너무 어려웠다.
 
```bash
$ composer require "bican/roles: 2.1.*"
```

```php
// config/app.php

return [
    'providers' => [
        // Other service providers ...
        Bican\Roles\RolesServiceProvider::class,
    ],
];
```

```bash
$ php artisan vendor:publish --provider="Bican\Roles\RolesServiceProvider" --tag=config
$ php artisan vendor:publish --provider="Bican\Roles\RolesServiceProvider" --tag=migrations
$ php artisan migrate
```

User 모델을 수정하자. 라라벨 네이티브 Authorization을 사용하지 않을 것이므로 `AuthorizableContract` 와 `Authorizable` trait를 삭제하자.

```php
use ...
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract,
                                    /*AuthorizableContract,*/
                                    CanResetPasswordContract,
                                    HasRoleAndPermissionContract
{
    use Authenticatable;
    use CanResetPassword;
    use HasRoleAndPermission;
    ...
}
```

Route 별로 역할에 따른 권한부여를 쉽게 하기 위해서, bican/roles 패키지에 내장되어 배포되는 미들웨어를 app/Http/Kerlen.php 에 등록해 놓자. 나중에 Route에서 `'middleware' => 'role:admin'` 또는 컨트롤러에서 `$this->middleware('role:admin')` 처럼 사용할 수 있다.
 
```php
class Kernel extends HttpKernel
{
    ...
    protected $routeMiddleware = [
        // Other middleware registrations ...
        'role' => \Bican\Roles\Middleware\VerifyRole::class,
    ];
}
```

### 역할을 만들자.

관리자모드에서 사용자와 역할을 관리하는 UI를 만드는 것이 좋다. 여기서는 artisan CLI의 tinker 코맨드를 이용한다.

```bash
$ php artisan tinker
>>> $admin = Bican\Roles\Models\Role::create([
... 'name' => 'Admin',
... 'slug' => 'admin'
... ]);
=> Bican\Roles\Models\Role {#718
     name: "Admin",
     slug: "admin",
     updated_at: "2015-11-18 06:10:59",
     created_at: "2015-11-18 06:10:59",
     id: 1,
   }
>>> $member = Bican\Roles\Models\Role::create([
... 'name' => 'Member',
... 'slug' => 'member'
... ]);
=> Bican\Roles\Models\Role {#730
     name: "Member",
     slug: "member",
     updated_at: "2015-11-18 06:11:57",
     created_at: "2015-11-18 06:11:57",
     id: 2,
   }
```

User 13번에 admin 역할을 부여하고, 나머지는 member 역할을 부여할 것이다. 

```bash
$ php artisan tinker
>>> $users = App\User::where('id', '!=', 13)->get();
>>> $users->map(function($user) {
... $user->roles()->sync([2]);
... });
>>> $user = App\User::find(13);
>>> $user->roles()->sync([1]);
```

User 와 Role 은 Many to Many 관계를 갖는다. 1명의 User가 여러 개의 Role을 가질 수 있고, admin Role을 가진 User는 여러 명이 있을 수 있다. Forum, Tag, Comment 모델을 다룰 때 뒤에서 다시 살펴볼 것이다. 

**`참고`** `Bican\Roles\Models\Role` 모델과 `App\User` 모델에 추가한 trait를 잘 따라가보면, 우리가 흔히 보았던 `return $this->belongsToMany('App\User')` 와 같은 관계 설정을 확인할 수 있다.

`map()` 메소드는 `array_map()`과 같은 동작을 한다. Many To Many 관계는 role_user 란 피봇테이블을 이용하여 관계를 기록하는데, `$user->roles()->attach(int|array $id)` 로 관계를 기록하고, `$user->roles()->detach(int|array $id)`로 관계를 끊을 수 있다. `$user->roles()->sync(array $ids)` 메소드는 인자로 넘겨 받은 $ids 목록과 피봇테이블에 써진 관계를 비교해, 인자에 없고 테이블에 있으면 테이블에서 해당 $id들 삭제하고, 인자에 있고 테이블에 없으면 테이블에 해당 $id들을 추가해 준다.
  
### 테스트

지금 당장 쓰지 않으니, 모든 기능이 동작하는 지 확인만하고 넘어가도록 하자.

```bash
$ php artisan tinker
>>> App\User::find(12)->is('admin'); 
=> false
>>> App\User::find(13)->is('admin');
=> true
>>> App\User::find(13)->is('admin|member', true); 
# == is(['admin', 'member'], true); or isAll(['admin', 'member']);
=> false
```
<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [33강 - 소셜 로그인](33-social-login.md)
- [35강 - 다국어 지원](35-locale.md)
<!--@end-->

