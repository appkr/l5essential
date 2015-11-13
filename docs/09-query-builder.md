# 9강 - 쿼리 빌더

SQL 문을 php 코드로 쓴 거라고 보면 된다. 지금은 그냥 SQL로 쓰면 될 것, 더 길고 복잡한 코드로 쓰지?라고 반문할 수 있지만.. 차차 그 편리성을 알게 되니 무작정 따라해 보자.

## 쿼리 빌더를 이용해 보자.

`DB` Facade와 `table()`, `get()` 메소드를 이용하여 Collection(레코드셋)을 가져 와 보자.

```bash
$ php artisan tinker
>>> DB::table('posts')->get(); # SELECT * FROM posts
=> [
     {#679
       +"id": 1,
       +"title": "My Title",
       +"body": "My Body",
     },
     {#680
       +"id": 2,
       +"title": "Modified Title",
       +"body": "Second Body",
     },
   ]
```

`first()`, `find()` 메소드로 Instance(레코드)를 가져오자.

```bash
>>> DB::table('posts')->find(2);
=> {#688
     +"id": 2,
     +"title": "Modified Title",
     +"body": "Second Body",
   }
>>> DB::table('posts')->first();
=> {#671
     +"id": 1,
     +"title": "My Title",
     +"body": "My Body",
   }
```

`where()` 예상대로 where 절을 사용하는 거다.

```bash
# 모두 동일한 쿼리이다. where()에서 = 연산자는 생략 가능하다.
>>> DB::table('posts')->where('id', '=', 1)->get(); 
>>> DB::table('posts')->where('id', 1)->get();
>>> DB::table('posts')->whereId(1)->get();
=> [
     {#692
       +"id": 1,
       +"title": "My Title",
       +"body": "My Body",
     },
   ]
```

**`참고`** `whereId()`는 Dynamic Method이다.
**`참고`** `where()`에 Closure를 쓸 수도 있다 `where(function($query) {$query->where('field', 'operator', 'value);})`

`select()`를 이용하여 필요한 필드만 가져오자.

```bash
>>> DB::table('posts')->select('title')->get();
=> [
     {#686
       +"title": "My Title",
     },
     {#678
       +"title": "Modified Title",
     },
   ]
```

자주 쓰는 메소드들이다. 공식 문서를 보면 알겠지만, `count()`, `distinct()`, `select(DB::raw('count(*) as cnt'))`, `join()`, `union()`, `whereNull()`, `having()`, `groupBy()`, ... 표현하지 못하는 SQL 문장은 없다고 보면 된다.
- `insert(array $value)`
- `update(array $values)`
- `delete(int $id)`
- `lists(string $column)`
- `orWhere(string $column, string $operator, mixed $value)`
- `limit(int $value)` // == `take(int $value)`
- `orderBy(string $column, string $direction)`
- `latest()` // == `orderBy('created_at', 'desc')`

---

- [8강 - 날 쿼리 :(](08-raw-queries.md)
- [10강 - 엘로퀀트 ORM](10-eloquent.md)

