# 8강 - 날 쿼리 :(

## 사용할 테이블을 만들자

[3강 - 글로벌 설정 살펴보기](03-configuration.md)에서 .env 파일에 설정한 내용으로 posts 테이블을 만들어 보자. 가능하면 GUI 툴을 사용하자 (Sequel Pro 권장)

```bash
# homestead 사용자를 생성하고, myProject DB 에 권한을 부여하는 등이 CLI 툴로는 굉장히 복잡하다.
# 아래 명령들은 이런 내용으로 만든다는 내용을 보여주기 위한 것이며 실제로는 GUI 툴로 할 것을 권장한다.

$ mysql -uroot
mysql > CREATE DATABASE myProject;
mysql > CREATE USER 'homestead@localhost' IDENTIFIED BY 'secret';
mysql > GRANT ALTER, CREATE, INSERT, SELECT, DELETE, REFERENCES, UPDATE, DROP, EXECUTE, LOCK TABLES, INDEX ON myProject.* TO 'homestead@localhost';
mysql > FLUSH PRIVILEGES;
mysql > exit (enter)

$ mysql -uhomestead -p
mysql > CREATE TABLE posts(
    -> id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -> title VARCHAR(255),
    -> body TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
mysql > INSERT INTO posts(title, body) VALUES('My Title', 'My Body');
mysql > exit (enter)
```

mySql에 root로 로그인하여 myProject DB를 만들고, homestead 사용자에 대해 myProject DB에 대한 접근 권한 부여를 반드시 해야 한다. 아래 그림은 Sequel Pro에서 권한 부여<kbd>Cmd</kbd> + <kbd>U</kbd>하는 과정이다.

![](./08-raw-queries-img-01.png)

## 라라벨을 이용해서 DB 쿼리를 해 보자. 

**`참고`** 실제로 이렇게 사용하는 경우는 거의 없으니 참고만 하자.

쿼리를 배우기 위해 라라벨에서 제공하는 REPL을 이용하자. tinker('어설프게 손보고 고치다' 라는 뜻)라고 불리는 artisan 코맨드인데, 라라벨의 모든 환경이 제공되기 때문에 여러가지 실험적인 시도들을 해보기 편리하다.

```bash
$ php artisan tinker
Psy Shell v0.5.2 (PHP 5.6.7 — cli) by Justin Hileman
>>> (cursor)
```

posts 테이블을 가져와 보자.

```bash
>>> DB::select('select * from posts');
=> [
     {#676
       +"id": 1,
       +"title": "My Title",
       +"body": "My Body",
     },
   ]
```

레코드를 더 추가하자. 라라벨은 PDO를 이용하기 때문에 ? 처럼 같이 데이터를 바인딩해 줘야 한다.

```bash
>>> DB::insert('insert into posts(title, body) values(?, ?)', ['Second Title', 'Second Body']);
=> true
```

Collection이 아니라 하나의 Instance만 얻으려면 어떻게 해야 할까?

```bash
>>> $post = DB::selectOne('select * from posts where id = ?', [1]);
=> {#689
     +"id": 1,
     +"title": "My Title",
     +"body": "My Body",
   }
>>> $post->title;
=> "My Title"
```

업데이트도 해 보자.

```bash
>>> DB::update('update posts set title="Modified Title" where id = ?', [2]);
=> 1
```

---

- [7강 - 블레이드 201](07-blade-201.md)
- [9강 - 쿼리 빌더](09-query-builder.md)

