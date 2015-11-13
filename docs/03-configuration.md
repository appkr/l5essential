# 3강 - 글로벌 설정 살펴보기

## Code Editor 와 DB Client

- [phpStorm](https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Early+Access+Program)(1달 Free)을 권장한다. 
- [Sequel Pro](http://www.sequelpro.com/download)(Free)를 권장한다. 

설치하자.

## .env

.env에 써진 값들을 config/\*\*.php 에서 `env(string $key)`로 읽을 수 있다. 왜 config/\*\*.php, 가령 database.php 에 직접 하드코드로 쓰지 않을까? 이유는...
- local, staging, production 등 어플리케이션 실행 환경에 따라 설정 값이 바뀌어야 할 때 유연하게 대응하기 위해서다.
- 패스워드 등 민감한 정보를 버전 컨트롤에서 제외하기 위해서다. (.gitignore 파일을 확인해 보자.)

```
APP_ENV=local           # 실행환경
APP_DEBUG=true          # 디버그 스위치
APP_KEY=                # 32bit Application Key
```

.env 파일이 없다면, 생성하자.

```bash
$ cp .env.example .env
```

## Application Key

.env에 설정된 `APP_KEY` 값은 라라벨 프레임웍 전반에 걸쳐 Cipher 알고리즘에서 Seed 값으로 사용된다. 설정되어 있지 않다면 꼭 설정하자.

```bash
$ php artisan key:generate
```

## DB 에 연결하자.

먼저 프로젝트에 사용할 데이터베이스를 설정하자. 라라벨에서 .env 파일 수정만으로 DB 설정이 가능하다.

```
DB_HOST=localhost
DB_DATABASE=myProject
DB_USERNAME=homestead
DB_PASSWORD=secret
```

실제 DB 접속은 8강 부터 할 것이다. config 디렉토리 아래에 있는 다른 파일들도 살펴 보자.

**`참고`** Homestead 에 설치된 mySQL에 접속하려면, port를 33060으로 설정해야 한다.

---

- [2강 - 라라벨 5 설치하기](02-hello-laravel.md)
- [4강 - Routing 기본기](04-routing-basics.md)