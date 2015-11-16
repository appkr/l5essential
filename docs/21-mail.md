# 21강 - 메일 보내기

## mailgun 서비스 가입하자

실습을 위해 라라벨에 기본 값으로 설정되어 있는 [Mailgun 서비스]에 가입하자. 월 1만통까지는 공짜라고 한다. 가입 후 반드시, 가입할 때 사용한 메일 계정으로 들어가서 활성화 링크를 눌러줘야 정상적으로 메일 발송이 가능하다. 

## 메일 설정

config/mail.php 를 열어 보내는 사람 정보를 채우자.

```php
'from' => ['address' => 'john@example.com', 'name' => 'John Doe'],
```

.env 를 열어 메일을 설정해 보자. MIAL_ 로 시작하는 나머지 설정은 모두 지우자.

```bash
MAIL_DRIVER=smtp
MAIL_USERNAME=your_mailgun_login_email
MAIL_PASSWORD=your_mailgun_login_password
```

## 메일을 보내 보자.

app/Http/routes.php 에 메일을 보내는 Route를 작성하자. 노파심에 다시 얘기하면, 학습을 위해 편의상 routes.php 에 작성하지만, 실전에서는 컨트롤러 (또는 서비스 로직)에 들어가야 하는 내용이다.

```php
Route::get('mail', function() {
    $to = 'YOUR@EMAIL.ADDRESS';
    $subject = 'Studying sending email in Laravel';
    $data = [
        'title' => 'Hi there',
        'body'  => 'This is the body of an email message',
        'user'  => App\User::find(1)
    ];

    return Mail::send('emails.welcome', $data, function($message) use($to, $subject) {
        $message->to($to)->subject($subject);
    });
});
```

`send()` 메소는 3개의 인자를 받는다. 첫번째는 사용할 뷰, 두번 째는 뷰에서 바인딩 시킬 데이터, 세번째는 콜백이다.

**`참고`** `send()` 대신 `queue()` 메소드를 사용하는 것이 편리하다. Queue 설정이 되어 있지 않으면, `send()`로 자동 폴백된다.

resources/views/emails/welcome.blade.php 를 만들자.

```html
<h1>{{ $title }} {{ $user->name }}</h1>
<hr/>
<p>{{ $body }}</p>
<hr/>
<footer>Mail from {{ config('app.url') }}</footer>
```

블레이드 문법을 통해서 `send()` 메소드를 통해 넘겨 받은 데이터들을 바인딩하는 것을 볼 수 있다. `config(string $key) (== Config::get(string $key))` 함수를 통해 config/**.php 에 위치한 설정 값을 읽을 수 있다.

브라우저를 열고 'mail' Route를 방문한 후, `$to`로 지정한 메일 계정으로 가서 이메일이 잘 왔나 확인해 보자.

![](./21-mail-img-01.png)
 
## 테스트 방법

메일이 잘 가는 지를 테스트하기 위해 매번 실제로 메일을 보내는 것은 여러가지로 좋지 않다. 라라벨에서는 메일 테스트를 위해 'log' 드라이버를 제공하며, 메일 발송 결과를 storage/logs/laravel.log 에서 확인할 수 있다. .env에서 수정할 수 있다.

```bash
MAIL_DRIVER=log
```

로그파일은 tail 명령을 이용하면 관찰하기 편리하다.

```bash
$ tail -f ./storage/logs/laravel.log
```

**`참고`** 실전에서는 `Mail::queue()` 메소드를 한번 Wrapping 한 도메인 레이어용 메일러를 별도로 만들고, 컨트롤러의 생성자에 의존성 주입을 해서 사용한다. 또는 컨트롤러에서 이런 데이터로 메일을 보내줘라고 이벤트를 생성하고, 이벤트를 구독하는 클래스에서 별도의 프로세스로 메일을 보내는 작업을 하게 된다.

---

- [목록으로 돌아가기](../readme.md)
- [추가 - 페이징](20-1-pagination.md)
- [22강 - 이벤트](22-events.md)
