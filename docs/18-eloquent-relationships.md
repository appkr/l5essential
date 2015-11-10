# 18강 - 모델간 관계 맺기

쿼리빌더 없이 여러 개의 테이블에서 Join Query하는 것은 정말 번거로운 일이다. 엘로퀀트 ORM을 이용해서 모델간에 관계를 연결하고, 손쉽게 관계된 모델의 속성값들에 접근해 보자.

## 테이블 연결

이전 강좌를 통해 만든 users와 posts 테이블 간의 관계를 생각해 보자. User는 여러 개의 Post를 만들 수 있다. 하나의 Post는 한명의 User에 속한다. 즉, one to many 관계가 형성된다. 테이블을 수정하기 위해 새로운 마이그레이션을 작성하자.

```bash
$ php artisan make:migration add_user_id_to_posts_table
```

```php
class AddUserIdToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function(Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('posts', function(Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign'); 
            $table->dropColumn('user_id');
        });
    }
}
```

`up()` 메소드를 살펴보자. posts 테이블에 user_id 필드를 생성한다. user_id가 음수가 될 수 없으므로 `unsigned()`를 체인했다. 테이블을 생성할 때 외래 키 연결을 하지 않았으므로, 마이그레이션에서 `foreign()` 메소드를 이용하여 `posts_user_id_foreign` 란 이름을 가진 관계를 생성하였다. `references()`, `on()`, `onUpdate()`, `onDelete()` 메소드 들을 사용한 것을 주의 깊게 살펴 보자.

`down()` 메소드는 `dropForeign()`을 `dropColumn()` 보다 먼저 호출했다. 이유는 외래키 연결이 있는 관계에서 컬럼을 삭제할 수 없기 때문이다.

마이그레이션하자.

```bash
$ php artisan migrate
```

## 모델간 관계를 연결하자.

User has many Post. Post belongs to a User. 이 관계를 기억하고, User 모델을 열어 보자.

```php
class User extends Model implements ...
{
    public function posts() {
        return $this->hasMany('App\Post');
    }
}
```

`$this (== App\User)`는 여러 개의 `App\Post`를 가질 수 있다라는 식으로 읽으면 되겠다. `posts()`라는 복수 형태로 쓴 것을 유의하자. 실제로 `posts()` 메소드의 최종 결과값이 Collection이기 때문이다. 가령, 메소드 이름을 전혀 생뚱한 `abc()`로 해도 되지만, 사람이 읽어 이해할 수 있는 이름으로 짓는게 좋은 습관이다. 팅커링해 보자.

```bash
$ php artisan tinker
>>> App\User::find(1)->posts()->get();
=> Illuminate\Database\Eloquent\Collection {#686
     all: [],
   }
```

[] 이 반환되었다. id 1번을 갖는 User 가 생성한 Post가 없기 때문이다. 만들어 보자.

**`팁`** 앞 강의에서 Post 모델에 수정했던 `public $timestamps = false;` 코드는 삭제하자.

```bash
$ php artisan tinker
>>> App\User::find(1)->posts()->create([
... 'title' => 'My First Article',
... 'body' => 'My post body...'
... ]);
=> App\Post {#697
     title: "My First Article",
     body: "My post body...",
     user_id: 1,
     updated_at: "2015-11-10 12:33:35",
     created_at: "2015-11-10 12:33:35",
     id: 1,
   }
```

`find()`로 가져온 User 인스턴스에 `posts()->create()` 메소드를 체인한 것을 주의 깊게 보자. 생성된 Post 모델에 user_id가 자동으로 1이 입력된 것을 확인하자. 좀 더 팅커링해 보자.

```bash
$ php artisan tinker
>>> $posts = App\User::find(1)->posts()->get(); # == App\User::find(1)->posts
=> Illuminate\Database\Eloquent\Collection {#687
     all: [
       App\Post {#674
         id: 1,
         user_id: 1,
         title: "My First Article",
         body: "My post body...",
         created_at: "2015-11-10 12:33:35",
         updated_at: "2015-11-10 12:33:35",
       },
     ],
   }
>>> $posts[0]->title;
=> "My First Article"
>>> $post = App\Post::find(1);
=> App\Post {#689
     id: 1,
     user_id: 1,
     title: "My First Article",
     body: "My post body...",
     created_at: "2015-11-10 12:33:35",
     updated_at: "2015-11-10 12:33:35",
   }
>>> $post->user()->get();
# BadMethodCallException with message 'Call to undefined method Illuminate\Database\Query\Builder::user()'
```

`App\User::find(1)->posts` 형태로도 1번 id User의 Post Collection을 가져올 수 있다는 것을 기억하자. 실전에서는 `Auth::user()->posts` 와 같이 현재 로그인한 사용자의 모든 Post 를 가져오는 식으로 많이 사용하게 될 것이다. 
 
## 반대 방향 관계 (Reverse Relationship)

$user->posts 가 가능하다면, $post->user 도 가능해야 한다. 그런데 위 팅커링에서는 `BadMethodCallException`이 발생했다. 이유는 User 모델에 Post와 관계를 설정해 주었지만, Post 모델에는 User와의 관계가 설정되어 있지 않기 때문이다. Post 모델을 열자.

```php
class Post extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }
}
```

`$this (== App\Post)`는 `App\User`에 속한다 라고 읽어 보자. 이제 역방향 관계 설정이 잘 되었는 지 확인해 보자.

```bash
$ php artisan tinker
>>> App\Post::find(1)->user()->first();
=> App\User {#691
     id: 1,
     name: "John Doe",
     email: "john@example.com",
     created_at: "2015-11-10 09:20:09",
     updated_at: "2015-11-10 09:36:52",
   }
```

**`참고`** 위 예에서 posts 테이블에 컬럼을 `user_id`로 하지 않았다면 어떻게 해야 하나? 이때는 관계를 설정할 때, `return $this->hasMany('App\Post', 'custom_field_name');`, `return $this->belongsTo('App\User', 'custom_foreign_key');` 처럼, 엘로퀀트에게 컬럼 이름을 알려 주어야 한다.

---

- [17강 - 라라벨에 내장된 사용자 인증](https://github.com/appkr/l5essential/blob/master/docs/17-authentication-201.md)
- [19강 - 데이터 심기](https://github.com/appkr/l5essential/blob/master/docs/19-seeder.md)
