# 라라벨 5 입문 강좌

[![Build Status](https://travis-ci.org/appkr/l5essential.svg)](https://travis-ci.org/appkr/l5essential)

## 데모 사이트

아래 주소를 방문하면 이 강좌에서 개발한 결과물을 볼 수 있다.

[http://l5.appkr.kr/](http://l5.appkr.kr/)

## 목적

국내 라라벨 사용자가 늘어나길 바라는 마음에서, 라라벨을 처음 접하는 분들도 이해하기 쉬운 코스를 만들고 싶었다.
 
## 목표

1.  8 시간 정도에 라라벨의 기본기를 모두 마스터하는 것을 목표로 한다. (1강 ~ 25강)
2.  제시된 실전 프로젝트를 통해 중급 이상의 라라벨 개발자로 성장하도록 한다. (26강 ~ 계속 연재 중)

## 무엇을 다루지 않나?

강좌를 진행하기 위해 사용하지만, 설명하지 않는 것들이다.

-   php 문법
-   웹 프로그래밍 일반론
-   객체 지향 프로그래밍(OOP) 일반론
-   프론트엔드 프로그래밍 일반론

## 무엇을 다루나?

### **[입문코스]** 라라벨 프레임웍 기본기 익히기

라라벨을 처음 접하는 분들이 꼭 알아야 하는 내용만 추렸다(고 생각한다). 

-   [1강 - 처음 만나는 라라벨](lessons/01-welcome.md)
-   [2강 - 라라벨 5 설치하기](lessons/02-hello-laravel.md)
-   [3강 - 글로벌 설정 살펴보기](lessons/03-configuration.md)
-   [4강 - Routing 기본기](lessons/04-routing-basics.md)
-   [5강 - 뷰에 데이터 바인딩하기](lessons/05-pass-data-to-view.md)
-   [6강 - 블레이드 101](lessons/06-blade-101.md)
-   [7강 - 블레이드 201](lessons/07-blade-201.md)
-   [8강 - 날 쿼리 :(](lessons/08-raw-queries.md)
-   [9강 - 쿼리 빌더](lessons/09-query-builder.md)
-   [10강 - 엘로퀀트 ORM](lessons/10-eloquent.md)
-   [11강 - DB 마이그레이션](lessons/11-migration.md)
-   [12강 - 컨트롤러](lessons/12-controller.md)
-   [13강 - RESTful 리소스 컨트롤러](lessons/13-restful-resource-controller.md)
-   [14강 - 이름 있는 Route](lessons/14-named-routes.md)
-   [15강 - 중첩된 리소스](lessons/15-nested-resources.md)
-   [16강 - 사용자 인증 기본기](lessons/16-authentication.md)
-   [17강 - 라라벨에 내장된 사용자 인증](lessons/17-authentication-201.md)
-   [18강 - 모델간 관계 맺기](lessons/18-eloquent-relationships.md)
-   [19강 - 데이터 심기](lessons/19-seeder.md)
-   [20강 - Eager 로딩](lessons/20-eager-loading.md)
-   [추가 - 페이징](lessons/20-1-pagination.md)
-   [21강 - 메일 보내기](lessons/21-mail.md)
-   [22강 - 이벤트](lessons/22-events.md)
-   [23강 - 입력 값 유효성 검사](lessons/23-validation.md)
-   [24강 - 예외 처리](lessons/24-exception-handling.md)
-   [25강 - 컴포저](lessons/25-composer.md)

### **[중급코스]** 실전 프로젝트

아래 프로젝트들을 기획하고 있다.

#### 1. Markdown Viewer

기본기 강좌에 포함된 마크다운 문서를 HTML 뷰에서 나이스하게 보여주는 기능을 구현해 본다. 이를 통해 Filesystem, Custom Helper, Cache, Elixir 등을 살펴볼 예정이다.

-   [26강 - Document 모델](lessons/26-document-model.md)
-   [27강 - Document 컨트롤러](lessons/27-document-controller.md)
-   [28강 - Cache](lessons/28-cache.md)
-   [29강 - Elixir, 만병통치약?](lessons/29-elixir.md)
-   [30강 - Debug & Final Touch](lessons/30-final-touch.md)

#### 2. Forum

StackOverflow 처럼 댓글이 가능한 간단한 포럼을 구현해 본다. 이를 통해 HTTP Request &amp; Response 에 대한 이해를 높인다. 뿐만 아니라, CRUD, Event, File/Image Upload, 인증과 권한부여 등에 대해 배워볼 예정이다.

-   [31강 - 포럼 요구사항 기획](lessons/31-forum-features.md)
-   [32강 - 사용자 로그인](lessons/32-login.md)
-   [33강 - 소셜 로그인](lessons/33-social-login.md)
-   [34강 - 사용자 역할](lessons/34-role.md)
-   [35강 - 다국어 지원](lessons/35-locale.md)
-   [36강 - 마이그레이션과 모델](lessons/36-models.md)
-   [37강 - Article 기능 구현](lessons/37-articles.md)
-   [38강 - Tag 기능 구현](lessons/38-tags.md)
-   [39강 - Attachment 기능 구현](lessons/39-attachments.md)
-   [32/33 보충 - 인증 리팩토링](lessons/32n33-auth-refactoring.md)
-   [40강 - Comment 기능 구현](lessons/40-comments.md)
-   [41강 - UI 개선](lessons/41-ui-makeup.md)
-   [42강 - 서버 사이드 개선](lessons/42-be-makeup.md)
-   [43강 - 변경 사항 알림](lessons/43-change-note.md)

#### 3. RESTful API

Forum 에서 생성된 게시글/댓글을 JSON API 로 외부에 노출하여, 외부 앱들이 Forum 서비스와 상호 작용할 수 있도록 만들어 본다. 실험을 위해 프론트엔드 프레임웍을 이용 간단한 모바일 앱을 만들어 볼 예정이다.
 
-   [44강 - API 기본기 및 기획](lessons/44-api-basic.md)
-   [45강 - 기본 구조 잡기](lessons/45-api-big-picture.md)
-   [46강 - JWT 를 이용한 인증](lessons/46-jwt.md)
-   [47강 - 중복 제거 리팩토링](lessons/47-dry-refactoring.md)
-   [48강 - all() is bad](lessons/48-all-is-bad.md)
-   [49강 - API Rate Limit](lessons/49-rate-limit.md)
-   [50강 - 리소스 id 난독화](lessons/50-id-obfuscation.md)

#### 번외. 기타 잡다한 것들

-   [Homestead 설치 (on Mac)](lessons/02-install-homestead-osx.md)
-   [Homestead 설치 (on Windows)](lessons/02-install-homestead-windows.md)
-   [코드 배포](lessons/999-code-release.md)

## 이 강좌를 보는 방법

강좌들은 Markdown 문법으로 작성되어 있으므로, Github에서 보는 것이 좋다. 

각 강좌의 소스코드는 tag로 저장되어 있다. 먼저 이 프로젝트를 클론하고, 원하는 강좌로 체크아웃하자. **이미 만들어진 소스코드를 눈으로 읽는 것 보다, 한 단락 한 문장 따라하면서 실제 실습해 볼 것을 권장한다.** 따라해 보실 분은 아래 코맨드를 수행하지 마시길...

```bash
$ git clone git@github.com:appkr/l5essential.git myProject
$ cd myProject
$ composer install # composer가 설치되어 있지 않다면 2강을 참조해서 설치하자.
$ git checkout 03(tab & enter)
```

**`참고`** 학생들과 만나보면, 콘솔을 쓸 줄 모르는 분들이 많다. 문서에 나온 코드 블럭 중에서 `$` (윈도우즈의 경우 `\>`) 로 시작하는 명령들은 콘솔에서 실행하라는 의미이다. 가령, `$ ls -al` 이라 써 있으면, 콘솔에서 `ls -al (enter)` 를 하라는 의미이다. `# ...` 은 주석이다.

## LICENSE

- 강좌에 사용된 코드는 [MIT](https://raw.githubusercontent.com/appkr/l5essential/master/LICENSE) 라이센스를 따른다.
- 강좌 자체는 [CC BY](https://creativecommons.org/licenses/by/2.0/kr/) 라이센스를 따른다.
