# 19강 - 데이터 심기
 
앞에서 마이그레이션으로 테이블을 만들어 보았다. 만들어진 테이블에 테스트 데이터 또는 기본 데이터를 심는 과정을 씨딩(Seeding)이라 한다. 라라벨 5부터 Seeding을 효율적으로 하기 위한 Factory 기능이 제공된다. Factory는 데이터베이스 Seeding 뿐만 아니라, 테스트 클래스에서 필요한 데이터를 Stubbing하는데도 유용하게 사용할 수 있다.

## Model Factory

database/factories/ModelFactory.php 를 살펴보자. `App\User` 모델에 대한 Factory는 기본 제공되는 것을 알 수 있다. `App\Post` 모델에 대한 Factory를 정의해 보자.

```php
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title'   => $faker->sentence,
        'body'    => $faker->paragraph,
        'user_id' => App\User::all()->random()->id
    ];
});
```

`$faker`는 다양한 형태의 Dummy 데이터를 생성해 주는 클래스이다. 'user_id'를 생성할 때, `App\User::all()->random()->id`를 쓴 것을 주목해 보자. 아무리 테스트를 위한 데이터 심기라 하지만 실제 존재하는 User를 Post와 연결해야 하기 때문이다.

**`참고`** php 5.5 이상에서는 `'App\Post'` 대신 `App\Post::class`와 같은 문법으로 사용할 수 있다. 문자열이 아니기 때문에 phpStorm 같은 IDE에서 해당 클래스로 이동할 때 편리하다(<kbd>Cmd</kbd> + Click).
 
팅커링 해 보자.

```bash
$ php artisan tinker
>>> factory('App\User')->make();
=> App\User {#707
     name: "Louvenia McDermott Sr.",
     email: "filiberto.moore@gmail.com",
   }
>>> factory('App\User', 2)->make(); # Instance 2개 생성
=> Illuminate\Database\Eloquent\Collection {#700
     all: [
       App\User {#703
         name: "Jany Ullrich",
         email: "skunde@friesen.org",
       },
       App\User {#712
         name: "Ms. Shanelle Heller III",
         email: "walker84@lebsack.com",
       },
     ],
   }
```

**`참고`** `factory()` Helper 에서 호출한 `make()` 메소드는 메모리에 모델 인스턴스만 생성하는 반면, 곧 보게 될 `create()` 메소드는 모델 인스턴스를 생성하고 DB에 저장하는 일까지 한다. 

## Seeder 클래스

tinker해 보았던 것을 이제 Seeder 클래스로 만들자.

```bash
$ php artisan make:seed UsersTableSeeder
$ php artisan make:seed PostsTableSeeder
```

`factory()` Helper Function을 이용해서 Seeder 클래스를 채우자. `truncate()` 메소드는 모델과 연결된 테이블 데이터를 깨끗이 지워주는 역할을 해 준다.

```php
// database/seeds/UsersTableSeeder
class UsersTableSeeder extends Seeder 
{
    public function run() 
    {
        App\User::truncate();
        factory('App\User', 10)->create();
    }
}

// database/seeds/PostsTableSeeder
class PostsTableSeeder extends Seeder
{
    public function run()
    {
        App\Post::truncate();
        factory('App\Post', 20)->create();
    }
}
```

Seeder 클래스가 완성되었으면 마스터 Seeder 클래스인, database/seeds/DatabaseSeeder.php 에 등록하자. `Model::ungard()`는 모든 모델에 대해 MassAssignment를 허용한다는 의미이다.

```php
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(UsersTableSeeder::class);
        $this->command->info('users table seeded');
        
        $this->call(PostsTableSeeder::class);
        $this->command->info('users table seeded');

        Model::reguard();
    }
}

```

## Seeding 하자.

이제 모든 준비가 완료되었으니, Seeding을 하자.

```bash
$ php artisan db:seed
```

## QueryException

```bash
[Illuminate\Database\QueryException]
  SQLSTATE[42000]: Syntax error or access violation: 1701 Cannot truncate a table referenced in a foreign key constraint ...
```

왜 발생했을까? `truncate()` 메소드 호출에서 `posts.user_id` 와 `users.id` 간의 Foreign Key 제약 때문에 테이블의 레코드 삭제가 불가해서 발생한 것이다. database/seeds/DatabaseSeeder.php에 아래 2 라인을 추가하고 Seeding을 다시 실행해 보자.

```php
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ...
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
```

이제 될 것이다.

```bash
$ php artisan db:seed
```

Seeding이 잘 되었는 지, DB 테이블을 확인해 보자.
<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [18강 - 모델간 관계 맺기](18-eloquent-relationships.md)
- [20강 - Eager 로딩](20-eager-loading.md)

<!--@end-->