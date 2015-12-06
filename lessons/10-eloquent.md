# 10강 - 엘로퀀트 ORM

엘로퀀트는 라라벨의 ORM (Object Relational Mapper, Active Record Pattern 의 구현체)이다. 데이터베이스는 테이블간 관계를 가지고 있다. 데이터베이스를 추상화한 모델 클래스 간에 관계를 맺어 주는 구현체를 일반적으로 ORM이라 칭한다. 뿐만 아니라 라라벨에서는 config/database.php에 의해 설정된 DB Driver와 ORM 사용으로 인해 데이터베이스와 어플리케이션 간에 디커플링 효과도 얻게 된다. 어플리케이션 코드 수정 한 줄 없이 mySQL을 SQLite로 바꿀 수 있다는 의미이다.
  
## 모델

라라벨이 MVC 프레임웍인데 우리가 이제까지 본 것은 V(뷰) 뿐이었다. 드디어 M에 해당하는 모델을 볼 차례이다. authors 라는 테이블을 생성하자. 생산성 측면에서 DB 작업은 GUI 툴을 사용하자 (Sequel Pro 권장)

```bash
$ mysql -uhomestead -p
mysql > CREATE TABLE authors(
    -> id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -> email VARCHAR(255) NOT NULL,
    -> password VARCHAR(60) NUT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
mysql > INSERT INTO authors(email, password) VALUES('john@example.com', 'password');
```

**`참고`** 이번까지는 수작업으로 테이블을 생성했다. 다음 번 부터는 migration을 통해 테이블을 생성하고, seed를 통해 데이터를 생성할 것이다.

모델을 생성하자. artisan CLI에서 제공하는 Generator 를 이용하자. 코맨드를 수행한 후 app/Post.php, app/Author.php 파일이 생성되었는 지 확인하자.

```
$ php artisan make:model Post
$ php artisan make:model Author
```

**`참고`** 테이블 이름을 users 와 같이 복수로, 모델 이름은 User 처럼 단수로 하는 것이 규칙이다. DB 테이블은 Collection을 담고 있고, 모델은 DB 테이블에 담긴 하나의 레코드를 클래스로 표현한 것이기 때문이다. 규칙을 지킬 수 없는 경우에는, 가령 모델명을 Author, 테이블을 users 라 했을 경우, 라라벨이 둘 간의 관계를 알 수 있도록 Author 모델에 `protected $table = 'users';` 코드를 추가해 주어야 한다.

## 처음 만나는 엘로퀀트 쿼리

전체 Collection을 가져오자. 이번엔 `DB` Facade를 이용하지 않고, `App\Author` 모델을 이용한 것을 눈여겨 보자.

```bash
$ php artisan tinker
>>> App\Author::get(); # == DB::table('authors')->get();
=> Illuminate\Database\Eloquent\Collection {#677
     all: [
       App\Author {#678
         id: 1,
         email: "john@example.com",
         password: "password",
       },
     ],
   }
```

**`참고`** 쿼리 빌더 에서 사용할 수 있는 대부분의 메소드는 Eloquent Model에서도 사용할 수 있다. 예를 들면, `App\Author::orderBy('id', 'desc')->limit(1)->lists('email');`와 같이. 사실, 이번 강좌에서 보는 것은 엘로퀀트를 상속한 모델을 이용해서 쿼리 빌더를 쓸 수 있다는 정도이다. 엘로퀀트의 전부가 아니란 얘기다. 엘로퀀트 ORM의 꽃은 모델간의 관계 맺기 이며, 18강에서 다루고 있다.

새로운 Instance를 생성하고 데이터베이스에 저장해 보자.

```bash
$ php artisan tinker
>>> $author = new App\Author;
>>> $author->email = 'foo@bar.com';
>>> $author->password = 'password';
>>> $author->save(); # 메모리에만 존재하던 인스턴스를 데이터베이스에 저장한다.
# Illuminate\Database\QueryException with message '... updated_at in ...'
```

## QueryException

`save()` 메소드 호출에서 예외가 발생했을 것이다. 엘로퀀트는 모든 모델이 `updated_at`과 `created_at` 필드가 있다고 가정하고, 새로운 Instance가 생성될 때 현재의 timestamp값을 입력하려한다. 그런데, 위 테이블들은 수작업으로 만든 테이블이라 앞서 말한 필드들이 존재하지 않는다. 방법은 필드를 추가하는 방법과, timestamp 입력을 모델에서 끄는 방법이 있는데, 실습을 위해 일단 끄자.

```php
// Post 모델도 적용해 주자.
class Author extends Model
{
    public $timestamps = false;
}
```

**`중요`** **_모델에 변경이 생기면 실행중이던 tinker 를 다시 실행해 주어야 한다._** tinker 가 로드될 시점에 라라벨 구동을 위한 모든 환경이 로드되므로, 이후 변경을 반영하기 위함이다. <kbd>ctrl</kbd> + <kbd>C</kbd> 또는 `>>> exit` 명령으로 종료할 수 있다. tinker를 재실행 한 후 <kbd>up</kbd> 화살표로 이전 코맨드 이력을 탐색할 수 있다.

```bash
>>> $author = new App\Author;
>>> $author->email = 'foo@bar.com';
>>> $author->password = 'password';
>>> $author->save();
=> true
```

## 다른 메소드를 이용한 모델 생성

이번에는 `save()` 대신 `create()` 메소드를 이용할 것이다.

```bash
$ php artisan tinker
>>> App\Author::create([
... 'email' => 'bar@baz.com',
... 'password' => bcrypt('password')
... ]);
# Illuminate\Database\Eloquent\MassAssignmentException with message 'email'
```

**`참고`** `bcrypt(string $value)` 은 암호화된 60byte스트링를 만들어 준다. Facade로 쓰면 `Hash::make(string $value)` 와 같다.

## MassAssignmentException

timestamps를 무력화 시킨 후에도, `create()` 메소드를 이용할 때는 에러가 발생했다. `create()` 메소드로 모델 인스턴스를 생성할 때는 해당 모델에 `$fillable` 속성을 지정해 주어야 한다. 폼을 통해 사용자가 넘긴 값을 그대로 DB에 넣을 경우를 대비해, 악의적인 필드가 입력되는 것을 방지하기 위한 조치이다. Post와 Author 모델을 열고 `$fillable` 속성을 지정하자.

```php
class Author extends Model
{
    protected $fillable = ['email', 'password'];
}
```

```php
class Post extends Model
{
    protected $fillable = ['title', 'body'];
}
```

tinker 를 재시작하고 <kbd>up</kbd> 키를 눌러, `create()` 메소드를 다시 실행해 보자.

```bash
$ php artisan tinker
>>> App\Author::create([
... 'email' => 'bar@baz.com',
... 'password' => bcrypt('password')
... ]);
=> App\Author {#680 # bcrypt() Helper에 의해 암호화된 60 byte 패스워드를 확인하자.
     email: "bar@baz.com",
     password: "$2y$10$tL/9voTNRtH7dfE9yULVaOybUWTcNkLRws9gTawcU85L3PEwRotUS",
     id: 3,
   }
```
<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [9강 - 쿼리 빌더](09-query-builder.md)
- [11강 - DB 마이그레이션](11-migration.md)
<!--@end-->