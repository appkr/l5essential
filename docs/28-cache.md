# 실전 프로젝트 1 - Markdown Viewer 

## 28강 - Cache

우리가 사용한 마크다운 파일은 자주 변경되지 않는다. 즉, 매번 사용자가 요청할 때 마다 모델에서 요청한 파일을 읽어 들이고, ParsedownExtra 클래스 인스턴스를 만들어서 `text()` 메소드를 호출한다는 것은 서버의 CPU 및 메모리 자원뿐만아니라 사용자의 시간을 낭비하는 일이다. 이럴 때 필요한 것이 서버측 캐시이다. 캐시는 HTTP 요청에 대한 응답을 파일 또는 메모리에 저장해 두었다가 두번째 요청부터는 저장소에서 바로 꺼내어 주는 기능이다. 데이터베이스 드라이버와 마찬가지로 라라벨 캐시는 이 복잡한 기능을 단순화시켰다.

### 캐시 설정

.env 와 config/cache.php를 열어서 기본 캐시 드라이버 설정을 확인하자. `file` 드라이버를 사용하고 있고, 캐시 저장소는 storage/framework/cache 인 것을 알 수 있다. 우선 실습을 위해 `file` 드라이버를 그냥 사용하도록 하자.

```bash
CACHE_DRIVER=file
```

```php
'...' => '...',
'file' => [
    'driver' => 'file',
    'path'   => storage_path('framework/cache'),
],
```

### 컨트롤러에 캐시 기능 추가

`DocumentsController::show()` 메소드를 수정할 것이다. 캐시는 `key => value` 저장소라는 것을 기억하자.

```php
    public function show($file = '01-welcome.md')
    {
        $index = \Cache::remember('documents.index', 120, function () {
            return markdown($this->document->get());
        });

        $content = \Cache::remember("documents.{$file}", 120, function() use ($file) {
            return markdown($this->document->get($file));
        });

        return view('documents.index', compact('index', 'content'));
    }
```

`Cache` Facade 를 이용한다. 

`remember()` 메소드의 첫번 째 인자는 키 이름이다. 이 키 이름으로 데이터를 저장할 것이다. 두번째 인자는 캐시를 유지할 시간이다. 키 이름에 해당하는 값이 캐시 저장소에 없으면, 세번째 인자로 받은 콜백을 실행한다. 이 콜백에서 반환되는 값을 캐시 키 값으로 해서, 두번째 인자로 지정된 시간만큼 가지고 있는다. 지정된 시간 내에 들어온 요청에 대해서는 세번째 인자인 콜백을 실행하지 않고, 캐시 저장소에서 키 값에 대응되는 값(value)를 찾은 후 반환하는 식이다. 

이 예제에서는 키 이름을 `document.index` 와 `documents.문서이름`으로 지정했고, 첫 캐시가 적재된 이후 2시간 동안은 캐시에서 바로 응답하도록 했다. 가령, 8시 정각에 첫 요청이 들어와 새로운 캐시가 생성되었다면, 10시까지는 캐시에서 응답을 하다가, 10시 1초 이후에 요청이 들어오면 다시 콜백을 수행하고 캐시에 키와 값을 적재하게 된다. (무슨 얘긴지 이해 되었기를 바라며 여러번 설명했다.)

**`참고`** 앞 강의에서 계속 Closure라는 용어를 썼다. 이번 강의에서는 콜백이란 용어를 썼다. 같은 용어라고 생각하자.

**`참고`** "어떨 때는 클래스 앞에 `\` 를 쓰고 어떨 때는 안 쓰고 왜 그래요?" "이번에는 일부러 쓴 것이다." `\`는 루트 네임스페이스를 의미한다. 우리 컨트롤러는 `App\Http\Controllers` 네임스페이스 아래에 위치한다. 컴퓨터에서 절대경로와 상대 경로를 얘기할 때랑 마찬가지로, `Cache`라고 그냥 쓰면, `App\Http\Controllers\Cache`를 의미하게 되고(상대 경로), 그런 클래스는 존재하지 않는다. 해서 루트 (`\`) 네이스페이스에 존재하는 `Cache` 라고 명시적으로 쓴 것이다. 클래스를 시작하기 전에 `use` 키워드로 전체 경로를 표시해 주면 `\`를 생략할 수 있다. (이는 네임스페이스가 있는 모든 언어 마찬가지다. Java의 경우 `use` 대신 `import`를 사용한다. `import org.apache.http.client.HttpClient` 처럼.)
 
### 실험해 볼까?

서버를 띄우고, '/docs' Route를 처음으로 방문해 보자. 그리고, storage/framework/cache 아래에 캐시 파일이 생성된 것을 확인해 보자. 또, 'docs' Route를 다시 방문해 봐도, 우리 예제가 무겁지 않아서 별로 빨라진 것은 못 느낄 것이다. `Document::show()` 메소드에 `dd('reached')` 와 같은 디버그 코드를 넣고 다시 요청해 보자. 클라이언트 측의 요청이 캐시에 의해 바로 응답되고, `Document` 모델에 도달하지 않을 것을 알 수 있을 것이다. `docs/01-welcome.md` 를 수정하고 재요청해봐도 수정된 내용은 표시되지 않을 것이다, 2시간 동안은...
 
### 그럼, 2시간 내에 내용이 변경되면 어떻게 하지?

일단은 artisan CLI를 이용해 수동으로 지워 보자. 변경이 생기면 이벤트를 던져서 캐시를 초기화하는 것은 뒤에 다시 배운다.

```bash
$ php artisan cache:clear
```
 
 ### (OPTIONAL) Memcached
 
파일 캐시도 훌륭하다. 하지만 실전에서는 memcache나 redis와 같은 인 메모리(in-memory) 캐시를 주로 사용한다. 여기서는 memcached를 사용할 것이다.
  
.env 를 수정하자.

```bash
CACHE_DRIVER=memcached
```

먼저 이전에 사용했던 file 캐시를 삭제하고 memcached 를 설치하고 실행하자.

```bash
$ rm -rf storage/framework/cache/*
$ brew search memcache
$ brew install homebrew/php/php56-memcached # 자신의 php 버전에 맞는 모듈을 설치하자.

# 설치 종료 후 콘솔에 표시된 memcached 시작 코맨드를 잘 살펴보자.
# 필자의 장치에는 이미 설치되어 있어, 알고 있던 코맨드를 남긴다.
$ memcached -u memcached -d -m 30 -l 127.0.0.1 -p 11211
# 본 강좌랑 무관하므로 사용법은 구글링으로 찾아 보시길..
```

file 캐시에서 했던 방법과 동일한 방법으로 캐시 기능이 잘 동작하는 지 확인해 보자. 설정 값이 변경되었으므로 테스트 전에 `$ php artisan serve` 를 재기동하는 것을 잊지 말고.

**`참고`** 서버 측 뿐만 아니라, 클라이언트(브라우저) 측에서도 캐싱을 한다. 라라벨과 무관하므로 설명하지 않는다.

---

- [목록으로 돌아가기](../readme.md)
- [27강 - Document 컨트롤러](27-document-controller.md)
- [29강 - Elixir, 만병통치약?](28-elixir.md)