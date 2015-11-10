# 입문자를 위한 라라벨 5! 따라하면서 배워보자!

## 목적

국내 라라벨 사용자가 늘어 나길 바라는 마음에서, 라라벨을 처음 접하는 분들도 이해하기 쉬운 코스를 만들고 싶었다.
 
## 목표

- 8 시간 정도에 라라벨의 기본 기능을 모두 마스터하는 것을 목표로 한다.
- 이 코스에 대한 완벽한 이해 + [공식 매뉴얼](http://laravel.com/docs) + [API 문서](http://laravel.com/api) => 실전 투입

## 무엇을 다루나?

라라벨을 처음 접하는 분들이 꼭 알아야 하는 내용만 추렸다(고 생각한다). 

이 저장소 및 코스가 목표하는 수준을 고려했을 때, 꼭 다루었으면 하는 내용인데 빠졌다, 불필요할 것 같은데 있다.., 이 코스와 관련한 질문, 이런 것들이 있으면 [새로운 이슈](https://github.com/appkr/l5essential/issues)로 등록해 주시거나 PR을 날려 주시기 바란다. 그 외 라라벨 관련 질문은 [Laravel Korea](https://www.laravel.co.kr/) 게시판에 남겨 주시면 필자를 포함한 커뮤니티 멤버들이 성심껏 답변해 드릴 것이다.

- [1강 - 처음 만나는 라라벨](https://github.com/appkr/l5essential/blob/master/docs/1-welcome.md)
- [2강 - 라라벨 5 설치하기](https://github.com/appkr/l5essential/blob/master/docs/2-hello-laravel.md)
- [3강 - 글로벌 설정 살펴보기](https://github.com/appkr/l5essential/blob/master/docs/3-configuration.md)
- [4강 - Routing 기본기](https://github.com/appkr/l5essential/blob/master/docs/4-routing-basics.md)
- [5강 - 뷰에 데이터 바인딩하기](https://github.com/appkr/l5essential/blob/master/docs/5-pass-data-to-view.md)
- [6강 - 블레이드 101](https://github.com/appkr/l5essential/blob/master/docs/6-blade-101.md)
- [7강 - 블레이드 201](https://github.com/appkr/l5essential/blob/master/docs/7-blade-201.md)
- [8강 - 날 쿼리 :(](https://github.com/appkr/l5essential/blob/master/docs/8-raw-queries.md)
- [9강 - 쿼리 빌더](https://github.com/appkr/l5essential/blob/master/docs/9-query-builder.md)
- [10강 - 엘로퀀트 ORM](https://github.com/appkr/l5essential/blob/master/docs/10-eloquent.md)
- [11강 - DB 마이그레이션](https://github.com/appkr/l5essential/blob/master/docs/11-migration.md)
- [12강 - 컨트롤러](https://github.com/appkr/l5essential/blob/master/docs/12-controller.md)
- [13강 - RESTful 리소스 컨트롤러](https://github.com/appkr/l5essential/blob/master/docs/13-restful-resource-controller.md)
- [14강 - 이름 있는 Route](https://github.com/appkr/l5essential/blob/master/docs/14-named-routes.md)
- [15강 - 중첩된 리소스](https://github.com/appkr/l5essential/blob/master/docs/15-nested-resources.md)
- [16강 - 사용자 인증 기본기](https://github.com/appkr/l5essential/blob/master/docs/16-authentication.md)
- [17강 - 라라벨에 내장된 사용자 인증](https://github.com/appkr/l5essential/blob/master/docs/17-authentication-201.md)
- [18강 - 모델간 관계 맺기](https://github.com/appkr/l5essential/blob/master/docs/18-eloquent-relationships.md)
- [19강 - 데이터 심기](https://github.com/appkr/l5essential/blob/master/docs/19-seeder.md)
- [20강 - Eager 로딩](https://github.com/appkr/l5essential/blob/master/docs/20-eager-loading.md)
- [추가 - 페이징](https://github.com/appkr/l5essential/blob/master/docs/20-1-pagination.md)
- [21강 - 메일 보내기](https://github.com/appkr/l5essential/blob/master/docs/21-mail.md)
- [22강 - 이벤트](https://github.com/appkr/l5essential/blob/master/docs/22-events.md)
- [23강 - 입력 값 유효성 검사](https://github.com/appkr/l5essential/blob/master/docs/23-validation.md)
- [24강 - 예외 처리](https://github.com/appkr/l5essential/blob/master/docs/24-exception-handling.md)
- [25강 - 컴포저](https://github.com/appkr/l5essential/blob/master/docs/25-composer.md)

## 보는 방법

문서는 Markdown 문법으로 작성되어 있으므로, Github에서 보는 것이 좋다. 

각 강좌의 소스코드는 tag로 저장되어 있다. 가령 3강이면, lesson3을 보면 된다. 먼저 이 프로젝트를 클론하고, 원하는 강좌로 체크아웃하자. **이미 만들어진 소스코드를 눈으로 읽는 것 보다, 한 단락 한 문장 따라하면서 실제 실습해 볼 것을 권장한다.** 따라해 보실 분은 아래 코맨드를 수행하지 마시길...

```bash
$ git clone git@github.com:appkr/l5essential.git myProject
$ cd myProject
$ composer install # composer가 설치되어 있지 않다면 2강을 참조해서 설치하자.
$ git checkout 3-(tab & enter)
```

**`참고`** 학생들과 만나보면, 콘솔을 쓸 줄 모르는 분들이 많다. 문서에 나온 코드 블럭 중에서 `$` (윈도우즈의 경우 `\>`) 로 시작하는 명령들은 콘솔에서 실행하라는 의미이다. 가령, `$ ls -al` 이라 써 있으면, 콘솔에서 `ls -al (enter)` 를 하라는 의미이다. 코맨드 위에 `# ...` 은 주석이다.
