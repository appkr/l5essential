@extends('layouts.master')

@section('style')
  @parent
  <style>
    .navbar.transparent.navbar-inverse .navbar-inner {
      background: rgba(0,0,0,0.4);
    }
  </style>
@stop

@section('content')
  <section id="laracroft">
    <div class="container-fluid">
      <div class="row">
        <h1>
          <strong>라라벨</strong>
        </h1>
        <h2>
          PHP 월드에서 가장 <span class="selection">섹시한 웹 프레임웍</span>
        </h2>
        <p class="lead">
          웹 장인 <sup>Web Artisan</sup> 이 만든 웹 장인을 위한 프레임웍
        </p>
        <a href="{{ route('lessons.show') }}" class="btn btn-primary">
          강좌 바로가기
        </a>
        <a href="{{ route('articles.index') }}" class="btn btn-default">
          포럼 바로가기
        </a>
      </div>
    </div>

    <div class="credit">
      Image from <a href="http://wall.alphacoders.com/big.php?i=459033">Alpha Coders</a>
    </div>
  </section>

  <section class="container" id="story">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">
          왜 섹시하다고 하는가? 왜 라라크로프트 인가?
        </h1>

        <p>
          테일러 <sup><a href="http://taylorotwell.com/" target="_blank">Taylor Otwell - The Creator of Laravel</a></sup> 는 나니아연대기의 배경이되는 프라벨 <sup><a
              href="https://en.wikipedia.org/wiki/Cair_Paravel" target="_blank">Pravel</a></sup> 이란 가상의 왕국 이름에서 라라벨이란 이름을 따왔다고 얘기하지만... 누가 봐도 그건 마케팅 스토리다. 라라벨 작명에 대한 비하인드 스토리는 제이슨 <sup>Jason Lewis - A Core Member</sup> 의
          <a href="http://jasonlewis.me/article/laravel-the-story-behind-the-name" target="_blank">블로그 포스트</a>에 공개되어 있다.
        </p>

        <blockquote>
          <p>
            ... when during a Skiing holiday with Lara Croft, an unfortunate tumble led to me becoming Enveloped within her ample bosom. I had become <span style="text-decoration: underline;">Laraveloped</span> ...
          </p>
          <footer>Dayle Lees</footer>
        </blockquote>

        <p>
          라라벨 4 프로젝트 이름으로 사용했었고, 지금은 라라벨 코어 콤포넌트들의 네임스페이스로도 사용하고 있는 <code>Illumination</code> 도 툼레이더 <sup>Tomb Raider</sup> 에서 등장하는 비밀결사 조직인 Illuminati 에서 따온 것으로 추정된다. 여러가지 정황상 라라벨의 라라는 툼레이더의 주인공인 라라크로프트 <sup>Lara Croft</sup> 일 가능성이 높다. 역시 동서고금을 막론하고, 남자들이 모이면 <span style="text-decoration: underline;">기-승-전-여자</span> 인가 보다 ^^/. 어쨌든...
        </p>

        <p>
          라라벨은 여성 이름으로도 사용되기도 하는데, 이 이름을 가진 이들의 공통 특징은 침착함, 우아함, 준비됨의 성격을 가진다고 알려져 있다. 라라벨에 입문하고, 프로젝트를 진행해 보면, 쓰면 쓸수록 참 매력적인 프레임웍인 것을 몸소 체험하게 될 것이다.
        </p>
      </div>
    </div>
  </section>

  <hr/>

  <section class="container" id="feature">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">
          라라벨을 배워야 하는 이유? 그것도 오늘 당장?
        </h1>
        <p>
          라라벨은 표현력이 풍부한 API 와 우아한 문법, 무한한 확장성을 제공한다. 그 이유는 PSR <sup>PHP Standard Recommendations</sup>, 컴포저 <sup><a
              href="https://getcomposer.org/" target="_blank"></a>Composer</sup> 에 의한  의존성 관리 등 PHP 생태계에서 제공하는 개발 표준을 준수하기 때문이다.
        </p>

        <p class="snippets">
          <pre><code class="hljs language-bash"># PHP 에서 Image 조작을 위해서 아래 컴포넌트를 composer 로 설치한다.
$ composer require "intervention/image:2.3.*"</code></pre>
        </p>

        <p class="snippets">
          <pre><code class="hljs language-php">// 웹 서버 Document Root 디렉토리 밖에 있는 이미지를 요청하기 위한 Route 정의이다.
// app/Http/routes.php
Route::get('lessons/{file}', 'LessonsController@image');</code></pre>
        </p>

        <p class="snippets">
          <pre><code class="hljs language-php">// 좀 전에 Composer 로 설치한 intervention/image 컴포넌트를 이용해서 image/png 응답을 한다.
// app/Http/Controllers/LessonsController.php
<\?php

namespace App\Http\Controllers;

class LessonsController extends Controller {
    public function image($file)
    {
        $image = \Image::make('images/' . $file);
        return response($image->encode('png'), 200, ['Content-Type'  => 'image/png']);
    }
}</code></pre>
        </p>

        <p style="margin-top: 2rem;">
          또, IoC 컨테이너 <sup><a
              href="https://en.wikipedia.org/wiki/Inversion_of_control" target="_blank">Inversion of Control</a></sup>, ORM <sup><a
              href="https://en.wikipedia.org/wiki/Object-relational_mapping" target="_blank">Object Relational Mapping</a></sup> 등 객체지향 프로그래밍의 베스트 프랙티스들을 실천한다. <em class="help-block">용어들에 절대 겁먹을 필요없다. 몰라도 이 강좌를 따라가는데 아무런 지장이 없고, 강좌를 진행하면서 저절로 익히게 될 것이다.</em>
        </p>

        <p>
          라라벨은 프론트엔드 프레임웍 <sup><code>jQuery</code>, <code>VueJs</code>, <code>ReactJs</code>, <code>...</code></sup> 의 선택에는 관여하지 않지만, 클라이언트 사이드를 위한 기능들도 포함하고 있는 풀 스택 프레임웍이다. MVC <sup>Model View Controller</sup> 웹 프레임웍이 가진 기본 기능이라 할 수 있는 자체 템플릿 문법을 이용한 서버사이드 뷰 렌더링을 제공할 뿐 아니라, 프론트엔드 빌드 자동화를 위한 엘릭서 <sup><code>Elixir</code> - a Gulp Task Runner</sup> 기능도 제공한다. 엘릭서에는 <code>Babel</code>, <code>BrowserSync</code>, <code>Cache Bursting</code>, <code>...</code> 거의 모든 최신 빌드 레시피가 기본 포함되어 있다. 그 뿐인가? 팀 협업 및 개발 환경의 단일화를 위한 홈스테드 <sup><code>Homestead</code> - Ubuntu VM powered by Vagrant</sup>, 원격 서버로의 코드 배포 등을 자동화할 수 있는 엔보이 <sup><code>Envoy</code> - SSH Task Runner</sup> 까지 제공되니, 그 세심한 배려에 감동하지 않을 수 없다.
        </p>

        <blockquote>
          <p>
            Laravel continues its path toward <span style="text-decoration: underline;">world conquest</span> with Lumen, a new (and well-done) PHP micro framework
          </p>
          <footer>
            <a href="https://twitter.com/codeguy/status/587979729430519808">
              Josh Lockhart, April 2015
            </a>
          </footer>
        </blockquote>

        <p>
          <a href="http://www.yes24.com/24/goods/22380599" target="_blank">Modern PHP</a>, <a href="http://modernpug.github.io/php-the-right-way/" target="_blank">PHP The Right Way</a> 를 쓰면서 PHP 사용자 커뮤니티에서 위대한 리더로 자리 잡고 있는 조시 <sup>Josh Lockhart - Modern PHP Evangelist</sup> 도, 라라벨의 간결하고 우아함에 감탄하며, 자신이 개발하고 유지하던 <a href="http://www.slimframework.com/" target="_blank">Slim Framework</a> 에서 잠시 손을 놓았다가 최근에 3.0 업데이트를 내 놓기도 했다. 가장 <a href="http://eev.ee/blog/2012/04/09/php-a-fractal-of-bad-design/" target="_blank">일관성이 없는 언어라는 비난을 받던 PHP</a> 가, 조시와 같은 선구자 및 라라벨의 등장으로 인해 그 위상이 굉장히 많이 높아졌다고 필자는 평가한다.
        </p>

        <p>
          PHP 개발자라면 지금 당장 라라벨 우주선에 올라타야 한다. 조시의 말대로, 해외에서는 라라벨과 루멘 <sup><a href="http://lumen.laravel.com/">Lumen - A Micro Framework based on Laravel</a></sup> 으로 대동단결하는 추세다, 마치 <code>Java/Spring</code>, <code>Ruby on Rails</code>, <code>python/Django</code>, 각 언어별로 하나의 프레임웍이 사용자들의 사랑을 받는 것 처럼. 과거의 절차 지향 방식의 PHP 개발방법론은 대형 프로젝트에 사용이 어렵다. 물론, 토이 프로젝트에 프레임웍을 사용할 필요는 없다. 하지만, <span style="text-decoration: underline;">개발자 본인 스스로와 PHP 개발자 그룹 전체의 몸값을 높이기 위해서</span>, 꼭 라라벨이 아니더라도 <code>CodeIgniter</code>, <code>CakePHP</code>, <code>Slim</code> 과 같은 PHP 프레임웍을 공부할 것을 권장한다.
        </p>

        <p>
          이유야 어떻든 PHP 를 배우려는 분이라면, 라라벨을 선택할 것을 강력히 추천한다. 필자도 <code>Ruby On Rails</code> 를 쓰다가 2013년 경에 라라벨 3 버전을 때 넘어 왔다. 각 프레임웍들이 가지고 있는 기능들의 많고 적음 및 언어 고유의 특성에 의한 약간의 차이가 있지만, 각 기능들의 사용법은 크게 다르지 않다고 생각한다. 하나의 프레임웍을 자유자재로 쓸 수 있다면, <span style="text-decoration: underline;">다른 언어/프레임웍으로 전향할 때 학습속도는 말도 못하게 빨라진다</span>. 필자도 그랬으니까...
        </p>
      </div>
    </div>
  </section>

  <hr/>

  <section class="container" id="courses">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">
          코스 소개
        </h1>
        <p>
          입문 코스는 완성되었고 내용이 더 변경될 것이 없을 것 같다. 중급 및 실전 코스는 강좌를 계속 작성하고 있다. 중급 이상 강좌를 쓰는 중에 느낀 점은, 라라벨 고유의 기능보다는 프로그래밍 일반적인 것들이 많다는 것인데, 문맥상 설명에 필요하지 않는 부분들은 과감히 생략되었으니 독자들의 양해를 부탁드린다.
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <h3 class="text-center">입문 코스</h3>

        <img src="images/icon_beginner_course.svg" alt="">

        <p>
          입문자가 꼭 알아야 할 내용만을 추렸다. 이 정도만 익혀도 실전에 투입할 수 있다고 생각한다.
        </p>

        <p>
          라라벨 설치 및 Hello World 출력, 환경 변수 설정, 라우팅, 블레이드 템플릿 문법, 데이터베이스 연결, 테이블 마이그레이션, 데이터 씨딩, 데이터베이스 쿼리, 엘로퀀트 <sup><code>Eloquent</code> - 라라벨의 ORM</sup>, 모델과 컨트롤러, 사용자 인증, 메일보내기, 이벤트 트리거 및 처리, 사용자 입력갑 유효성 검사, 컴포저 사용법 등 을 배운다.
        </p>

        <p class="text-center">
          <a class="btn btn-default" href="{{ route('lessons.show', '01-welcome.md') }}" role="button">
            바로가기 &raquo;
          </a>
        </p>
      </div>

      <div class="col-md-4">
        <h3 class="text-center">중급 코스</h3>

        <img src="images/icon_intermediate_course.svg" alt="">

        <p>
          중급 코스와 실전 프로젝트는 별도로 분리되어 있지는 않지만, 실전 프로젝트를 진행하는 과정에서 입문 코스에서 배우지 못한 라라벨의 다양한 기능을 익히게 될 것이다.
        </p>

        <p>
          실전 프로젝트를 진행하는 과정에서 컴포저를 이용한 콤포넌트 설치와 사용법을 다시 한번 살펴보고, 엘로퀀트가 제공하는 다양한 관계 클래스의 사용법, 파일 시스템, 언어 현지화, 고급 이벤트 핸들링, 콘솔 코맨드 등등 의 내용을 배울 것이다.
        </p>

        <p class="text-center">
          <a class="btn btn-default" href="{{ route('lessons.show', '26-document-model.md') }}" role="button">
            바로가기 &raquo;
          </a>
        </p>
      </div>

      <div class="col-md-4">
        <h3 class="text-center">실전 코스</h3>

        <img src="images/icon_realworld_course.svg" alt="">

        <p>
          실전 프로젝트를 통해서, 서비스를 기획하고, 구현 방안 및 구조에 대한 디자인 의사결정을 하는 과정을 같이 공부해 보고 싶었다.
        </p>

        <p>
          이 강좌를 웹 페이지에서 볼 수 있도록 하는 Markdown Viewer 를 먼저 만들어 볼 것이다. 커뮤니티에서 주로 사용하는 댓글이 가능한 포럼을 만들어 볼 것이며, 이 포럼을 다른 디바이스에서도 사용할 수 있도록 RESTful API 서비스로 포장하는 과정을 진행해 볼 것이다.
        </p>

        <p class="text-center">
          <a class="btn btn-default" href="{{ route('lessons.show', '26-document-model.md') }}" role="button">
            바로가기 &raquo;
          </a>
        </p>
      </div>
    </div>
  </section>

  {{--<hr/>

  <section class="container" id="mailing-list">
    <div class="row">
      <div class="col-sm-6">
        <h3>새로운 소식을 메일로 알려 드립니다.</h3>
        <p>새로운 강좌 등록, 라라벨 관련한 소식 등 유용한 정보를 메일로 받아 보세요.</p>
      </div>
      <div class="col-sm-6">
        <form action="{{ route('mail-list.subscribe') }}" method="POST" class="form-horizontal">
          {!! csrf_field() !!}
          <div class="form-group col-sm-8 {{ $errors->has('email') ? 'has-error' : '' }}">
            <input type="email" class="form-control" id="email" placeholder="signup@example.com">
          </div>
          <div class="form-group col-sm-4">
            <button type="submit" class="btn btn-primary btn-block">가입하기</button>
          </div>
        </form>
      </div>
    </div>
  </section>--}}
@stop