# 5강 - 뷰에 데이터 바인딩하기

## 블레이드 템플릿 맛보기

resources/views/index.blade.php 를 만들고 아래와 같이 데이터를 바인딩 시켜 보자. 여기서 `{{ }}`은 라라벨의 템플릿 엔진인 블레이드에서 사용하는 String Interpolation 문법이다. 즉, 뷰 안에서 `<?= ?>`과 같은 역할을 해 주는 것이다.

```html
<!-- <?= $greeting; ?> <?= $name ? $name : ''; ?> Welcome Back~-->
<p>
    {{ $greeting }} {{ $name or '' }}. Welcome Back~
</p>
```

**`참고`** HTML 스트링등 특수문자가 포함된 데이터를 뷰에 바인딩 시킬 때는 {{ }}대신 {!! !!}을 사용한다. `{{ $name or '' }}`을 php 문법으로 컴파일 하면, 대략 `echo $name ? $name : '';`와 같다.

## with() 메쏘드로 뷰에 데이터 바인딩하는 방법

```php
Route::get('/', function () {
    $greeting = 'Hello';
    
    return view('index')->with('greeting', $greeting);
});
```

## with() 메쏘드로 한 개 이상의 데이터를 넘기는 방법

```php
Route::get('/', function () {
    return view('index')->with([
        'greeting' => 'Good morning ^^/',
        'name'     => 'Appkr'
    ]);
});
```

## view() 의 2번째 인자로 데이터를 넘기는 방법

```php
Route::get('/', function () {
    return view('index', [
        'greeting' => 'Ola~',
        'name'     => 'Laravelians'
    ]);
});
```

**`참고`** 실전에서는 `compact(mixed $varname)]` php 내장 함수와 조합하여, `$greeting='World'; return view('index', compact('greeting'));`와 같은 식으로 많이 이용한다.

## view 인스턴스의 Property를 이용하는 방법

```php
Route::get('/', function () {
    $view = view('index');
    $view->greeting = "Hey~ What's up";
    $view->name = 'everyone';
    
    return $view;
});
```

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [4강 - Routing 기본기](04-routing-basics.md)
- [6강 - 블레이드 101](06-blade-101.md)
<!--@end-->

