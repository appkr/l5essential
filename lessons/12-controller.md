# 12강 - 컨트롤러

MVC에서 V(뷰)와 M(모델)을 살펴보았다. 이제 마지막 콤포넌트인 C(컨트롤러)를 살펴볼 차례이다.

## Revisit Route

이제까지 모든 HTTP 요청에 대한 처리를 app/Http/routes.php의 Route Closure에서 처리 했다. Router의 역할은 HTTP 요청을 적절한 처리 로직으로 연결시켜주는 것, 즉 컨트롤러에 연결시켜주는 것이다. app/Http/routes.php에 컨트롤러를 이용한 Route를 만들어 보자.

```php
Route::get('/', 'IndexController@index');
```

HTTP GET / 요청이 들어오면, `IndexController`의 `index()` 메소드로 연결시키라는 뜻이다.

## Controller를 만들자.

서버를 부트업하고, '/' 경로로 접근해 보자. `ReflectionException - Class App\Http\Controllers\IndexController does not exist` 란 메시지가 출력되었을 것이다. IndexController가 없기 때문이다. artisan CLI를 이용해서 만들자.

```bash
$ php artisan make:controller IndexController
```

app/Http/Controllers/IndexController 가 생성된 것을 확인하자. `index()` 메소드를 만들자.

```php
class IndexController extends Controller
{
    public function index() {
        return view('index');
    }
}
```

서버를 부트업하고, / 경로로 접근하여, 정상적으로 뷰가 표시된 것을 확인하자. 4강에서 Route에 Closure에 넣은 내용과 `index()` 메소드의 내용이 동일하다는 것을 인지했을 것이다. Route에 모든 비즈니스 로직을 넣을 수 없을 뿐더러, 코드를 효율적으로 구조화시키기 위해서 컨트롤러를 사용하여 구조화한 것이라 생각하면 된다.
<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [11강 - DB 마이그레이션](11-migration.md)
- [13강 - RESTful 리소스 컨트롤러](13-restful-resource-controller.md)

<!--@end-->

