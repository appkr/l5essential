# 1강 - 처음 만나는 라라벨

라라벨은 php 언어로 짜여진 MVC 아키텍처를 지원하는 웹 프레임웍이다. 루비 언어에 레일즈, 파이썬 언어에 장고와 대칭되는 존재라고 보면 된다. [SitePoint의 2015년 설문조사](http://www.sitepoint.com/best-php-framework-2015-sitepoint-survey-results/)에 따르면, 라라벨은 **(국외에서)** 현재 가장 인기 있는 php 프레임웍으로 알려져 있다. 국산 CMS 중 사용자/사이트 수 측면에서 1위 CMS인 [XE](https://www.xpressengine.com/) 에서도 차기 버전인 XE3 는 라라벨로 전환한다고 발표한 바 있다. 
 
## 인기의 비결은?

- 단순하고(== 쉽고) 우아한 문법
- 복잡한 것들은 프레임웍 안에서 처리
- 강력한 확장 기능들
- PSR(Php Standards Recommendations) 적용
- 모던 개발 방법론 적용
- RAD RAD RAD (Rapid Application Development)

**`참고`** 라라벨은 다른 php 프레임웍 대비 "무겁다", "느리다"고 알려져 있다. 라라벨의 철학은 "개발 생산성"이라는 것을 기억하자. 우리가 사는 2015년은, 개발자 인건비에 비하면 큰 메모리에 SSD로 서버 성능을 높이는 비용은 너무나도 싸다. 가령, DigitalOcean의 경우 최고 사양인 8GB메모리, 4Core, 80GB HDD의 IaaS 사용 비용이 월 $80이다. 사용자가 수 억에 달해 스케일아웃으로도 한계에 달해 정말 성능 최적화를 해야할 시점이 된다면 정말 행복한 고민일 것이다. 그때는 훌륭한 개발자들을 뽑아서 하면 된다. 페이스북을 보라~ HHVM 이란 php 엔진도 지들이 직접 만들어 쓰지 않는가? 웬만한 규모에서는 시스템엔지니어 고용 대신 AWS 쓰고, 성능을 최적화하는 마이크로 레벨의 개발보다는 개발 시간을 단축할 수 있는 도구를 사용하는 것이 훨씬 더 현명한 선택일 것이다. 

## 내장 기능 (Free)

이 강좌를 통해 소개되는 기본적인 기능외에도 라라벨 5 에는 많은 기능이 포함되어 있다.

- 웹 서비스를 위해 필요한 Cache, Queue, Mail, ...
- [Service Container](http://laravel.com/docs/5.1/container)를 이용한 의존성 자동 주입
- [Cron 자동화](http://laravel.com/docs/5.1/scheduling)
- [Elixir](http://laravel.com/docs/5.1/elixir)를 이용한 CSS/Sass/Less, JS/Coffee 등 Frontend 워크플로우 자동화
- ... 

## 확장 기능 (Free)

- [Homestead](https://github.com/laravel/homestead) 로 개발 환경을 표준화할 수 있다.
- [Socialite](https://github.com/laravel/socialite)로 소셜 인증을 쉽게 할 수 있다. [Socialite Provider의 목록](http://socialiteproviders.github.io/)을 확인해 보자.
- [Cashier](https://github.com/laravel/cashier)로 결제 기능을 쉽게 달 수 있다.
- [Envoy](https://github.com/laravel/envoy) 로 SSH 원격 작업을 자동화할 수 있다.
- [Laravel Collective](http://laravelcollective.com/) 라라벨 구버전에 있었으나 빠진 기능들의 모음이다.

## 마이크로 프레임웍 - 루멘 (Free)

라라벨을 쓰는 것이 너무 오버라고 생각되는, 아주 간단한 서비스를 개발하려 할 땐,

- [Lumen](http://lumen.laravel.com/)

## 확장 서비스 ($)

- [Forge](https://forge.laravel.com/)를 이용하여 서버 프로비저닝/서버 관리/코드 배포 등을 자동화할 수 있다. ($10/월, 서버 댓수 제한 없음)
- [Envoyer](https://envoyer.io/)를 이용하여 무중단 코드 배포를 할 수 있다. ($10/월, 10 프로젝트)

## 커뮤니티

- [라라벨 뉴스](https://laravel-news.com/) - 프레임웍 코어 멤버 중의 한명인 Eric Barnes 가 운영하는 뉴스 블로그. 라라벨 개발자라면, 또는 되려면 뉴스레터에 꼭 가입하라.
- [라라 캐스트](https://laracasts.com/) - 역시 코어 멤버 중의 한명인 Jeffrey Way가 운영하는 동영상 강의 서비스. 매주 2~3개의 강의가 올라오며, 기존에 작성된 거의 400편에 가까운 동영상 강의를 볼 수 있다 ($10/월). 라라벨 사용자가 몰려 있는 서비스로서, 포럼도 같이 운영 중이며 포럼 활동도 아주 활발하다.
- [LARAVEL.IO](http://laravel.io/forum) - 라라 캐스트 전에 가장 활발한 활동을 하던 포럼이다.
- [Codecourse](https://www.youtube.com/user/phpacademy) - phpacademy 란 채널이 최근이 이름이 바뀌었다. 무료 동영상 강의를 제공하고 있다.
- 그 외에도 구글링 해보면 라라벨 관련 *영어* 자료는 넘쳐 난다. 

## 한국어 리소스

- [한국어 매뉴얼 (번역 by XE팀)](http://xpressengine.github.io/laravel-korean-docs/)
- [라라벨 영상 강좌 (by XE팀)](https://www.xpressengine.com/learn/23061328)
- [한국어 책 (by 정광섭님)](https://www.lesstif.com/pages/viewpage.action?pageId=28606603)

---

이제 라라벨 5 로 여행을 떠나 보자.

- [목록으로 돌아가기](../readme.md)
- [2강 - 라라벨 5 설치하기](02-hello-laravel.md)