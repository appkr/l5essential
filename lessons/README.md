# 라라벨 5 입문 강좌

## Github

이 강좌의 코드는 아래 저장소에서 확인할 수 있다.

[https://github.com/appkr/l5essential](https://github.com/appkr/l5essential)

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

- 좌측 메뉴 참조

### **[중급코스]** 실전 프로젝트

아래 프로젝트들을 기획하고 있다.

#### 1. Markdown Viewer

기본기 강좌에 포함된 마크다운 문서를 HTML 뷰에서 나이스하게 보여주는 기능을 구현해 본다. 이를 통해 Filesystem, Custom Helper, Cache, Elixir 등을 살펴볼 예정이다.

- 좌측 메뉴 참조

#### 2. Forum

StackOverflow 처럼 댓글이 가능한 간단한 포럼을 구현해 본다. 이를 통해 HTTP Request &amp; Response 에 대한 이해를 높인다. 뿐만 아니라, CRUD, Event, File/Image Upload, 인증과 권한부여 등에 대해 배워볼 예정이다.

- 좌측 메뉴 참조

#### 3. RESTful API

Forum 에서 생성된 게시글/댓글을 JSON API 로 외부에 노출하여, 외부 앱들이 Forum 서비스와 상호 작용할 수 있도록 만들어 본다. 실험을 위해 프론트엔드 프레임웍을 이용 간단한 모바일 앱을 만들어 볼 예정이다. 

- 좌측 메뉴 참조

#### 번외. 기타 잡다한 것들

- 좌측 메뉴 참조

## 이 강좌를 보는 방법

각 강좌의 [소스코드](https://github.com/appkr/l5essential) 는 tag로 저장되어 있다. 먼저 이 프로젝트를 클론하고, 원하는 강좌로 체크아웃하자. **이미 만들어진 소스코드를 눈으로 읽는 것 보다, 한 단락 한 문장 따라하면서 실제 실습해 볼 것을 권장한다.** 따라해 보실 분은 아래 코맨드를 수행하지 마시길...

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
