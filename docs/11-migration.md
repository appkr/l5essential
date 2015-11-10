# 11강 - DB 마이그레이션

마이그레이션은 데이터베이스를 위한 버전 컨트롤이라 생각하면 된다. 처음 테이블을 생성하고, 가령 이후에 새로운 필드를 추가한다든지, 필드의 이름을 바꾼다든지 등의 이력을 모두 마이그레이션 코드로 남겨 두고, 테이블을 생성했다가 롤백하는 등 자유롭게 이용할 수 있다.

마이그레이션 코드 작성은 정말 지루한 일이다. 토이(toy) 프로젝트를 하는데 마이그레이션을 굳이 작성할 필요는 없다. 하지만, 대형 서비스라면 테이블 스키마를 변경해야 할 수도 있는 새로운 요구사항이 생길 수 있다. 이때 기초공사를 잘못해 두었다면, 개발자에게 엄청난 위기 상황이 닥칠 수도 있는데, 마이그레이션이 위기에서 개발자를 구해주는 데 도움을 줄 것이다. 정말로~ 그리고, 팀으로 여러 명이 테이블 스키마를 변경해 가면서 개발할 때는, `mysqldump` 해서 주고 받는 수고를 피하기 위해 꼭 필요하다.

라라벨에서는 데이터베이스 스키마를 코드로 생성하기 위한 `Blueprint` 클래스를 제공하고 있다. 

## Migration을 만들자.

먼저 기존에 만든 posts, authors 테이블들을 삭제하자. (가급적 GUI 툴을 사용하자)

```bash
$ mysql -u homestead -p
mysql> SET FOREIGN_KEY_CHECKS = 0;
mysql> DROP TABLE posts;
mysql> DROP TABLE authors;
mysql> SET FOREIGN_KEY_CHECKS = 1;
```

artisan CLI를 이용해 마이그레이션을 만들자.

```bash
$ php artisan make:migration create_posts_table
$ php artisan make:migration create_authors_table
```

database/migrations 디렉토리에 timestamp_create_xxx_table 이란 2개의 마이그레이션이 생성된 것을 확인하자. database/migrations/timestamp_create_posts_table을 열어보면 `up()`, `down()` 2개의 메소드가 생성된 것을 확인할 수 있다. `up()` 은 마이그레이션을 실행할 때 동작하는 메소드이고 (`$php artisan migrate`), `down()`은 직전 마이그레이션을 롤백 하기 위한 메소드이다 (`$ php artisan migrate:rollback`).
 
```php
// CreateAuthorsTable 도 작성하자.

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function($table) {
            $table->increments('id'); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('title', 100); // title VARCHAR(100)
            $table->text('body'); // body TEXT
            $table->timestamps(); // created_at TIMESTAMP, updated_at TIMESTAMP
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts'); // DROP TABLE posts
    }
}
```

`up()` 에서는 `Schema` Facade의 `create()` 메소드를 이용하는데, 첫번째 인자는 테이블 이름, 두번째 인자는 콜백이다. 콜백은 `$table`이란 Blueprint 인스턴스를 주입하며, `string()`, `text()`, `integer()`, `timestamp()` 등 데이터베이스의 데이터 타입에 해당하는 다양한 메소드를 제공한다.

마이그레이션을 실행해 보자.

```bash
$ php artisan migrate
# Migration table created successfully.
# Migrated: 2014_10_12_000000_create_users_table
# Migrated: 2014_10_12_100000_create_password_resets_table
# Migrated: 2015_11_10_080603_create_posts_table
# Migrated: 2015_11_10_080609_create_authors_table
```

테이블이 정상적으로 생성되었는 지 확인하자. (GUI 툴에서 확인하자.)
 
```bash
$ mysql -u homestead -p
mysql> use myProject;
mysql> describe posts;
mysql> describe authors;
```
 
**`참고`** 마이그레이션을 처음 실행하면, migrations, users, password_resets 등과 같이 라라벨이 기본 내장된 마이그레이션도 같이 실행되어 해당 테이블들이 같이 생성된다.
 
## 롤백해 보자.
 
```bash
$ php artisan migrate:rollback

# 롤백되는 것이 잘 확인되었으면 다음 실습을 위해 다시 마이그레이션하자.
$ php artisan migrate
```

## 필드를 추가

authors 테이블에 name 필드를 추가하는 것을 깜빡했다고 가정하자. 물론, 전체 롤백을 하고, 최초 테이블 생성 마이그레이션에 name 필드를 추가한 뒤 마이그레이션을 다시 실행할 수 도 있다. 그런데, 만약 테이블에 데이터가 있다면... 난감해 진다. 필드를 추가하는 마이그레이션을 작성해 보자.

```bash
$ php artisan make:migration add_name_to_authors_table
```

```php
class AddNameToAuthorsTable extends Migration
{
    public function up()
    {
        Schema::table('authors', function(Blueprint $table) {
            $table->string('name')->after('email')->nullable(); // nullable()은 NULL 을 허용한다는 얘기
        });
    }

    public function down()
    {
        Schema::table('authors', function(Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}
```

테이블을 새로 생성할 때 쓰던 `create()`가 아니라, 이미 만들어진 테이블에 스키마를 변경하는 것이라 `table()` 메소드를 쓴 것에 주목하자. `after()`는 mySql에서만 쓸 수 있는 메소드로 인자로 넘겨 받은 필드 다음에 새로운 필드를 추가해 준다.

마이그레이션을 실행하고, 필드가 추가되었는 지 확인해 보자.

```bash
$ php artisan migrate
```

**`팁`** 이번 마이그레이션에서는 Closure Function 인자에 Blueprint 라고 TypeHint를 썼다. TypeHint를 쓰면 코드 에디터에서 `->` 로 코드 힌트를 볼 수 있어서 편리하다.

## Reset & Refresh

`migrate:rollback` 이 직전 마이그레이션만 롤백하는 반면 `migrate:reset` 는 모든 마이그레이션을 롤백하고 데이터베이스를 초기화 시킨다. `migrate:refresh` 는 리셋을 실행해서 데이터베이스를 청소한 후, 마이그레이션을 처음부터 다시 실행하는 코맨드이다.

---

- [10강 - 엘로퀀트 ORM](https://github.com/appkr/l5essential/blob/master/docs/10-eloquent.md)
- [12강 - 컨트롤러](https://github.com/appkr/l5essential/blob/master/docs/12-controller.md)