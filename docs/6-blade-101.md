# 6강 - 블레이드 101

블레이드는 라라벨의 템플릿 엔진이다. 뷰 안에 포함된 블레이드 문법들은 블레이드 엔진에 의해 php 코드로 컴파일 된다.

## {{ }} - String interpolation

php echo 코맨드와 같은 역할을 해 준다.

```html
<!-- <?= $greeting; ?> -->
{{ $greeting }}
```

## {{-- --}} - Comment

<!-- --> 와 같이 HTML 주석으로 컴파일 된다. 그럼데 브라우저에서 소스보기로 보면 엄연히 다르다.

```html
{{-- count(range(1, 10)) --}} <!-- count() 자체가 실행안됨. 즉, 아무것도 출력되지 않음 -->
\<\!-- {{  count(range(1, 10)) }} --\> <!-- 주석 안에 10이 표시됨 -->
```

## @foreach

resources/views/index.blade.php 에서 `@foreach` 블레이드 문법을 사용해 보자.

```html
<ul>
  @foreach($items as $item)
    <li>{{ $item }}</li>
  @endforeach
</ul>
```

당연히 `$items` 변수를 뷰로 넘겨줘야 한다. 어디서? 5강에서 배운 내용이다. app/Http/routes.php 에서...

```php
Route::get('/', function () {
    $items = [
        'Apple',
        'Banana'
    ];

    return view('index', compact('items'));
});
```

**`참고`** `@for`도 사용할 수 있다.

## @if

resources/views/index.blade.php 에서 `@if` 블레이드 문법을 사용해 보자.

```html
@if($itemCount = count($items))
  <p>There are {{ $itemCount }} items !</p>
@else
  <p>There is no item !</p>
@endif
```

**`참고`** `@elseif` 당연히 된다. `@unless (== if(!))`도 사용할 수 있다.

## @forelse

resources/views/index.blade.php 에서 `@forelse` 블레이드 문법을 사용해 보자. `@forelse`는 `@if`와 `@foreach`의 결합이다. 뷰로 넘어온 변수에 값이 있고 ArrayAccess를 할 수 있으면 , `@forelse`를 타고 그렇지 않으면 `@empty`를 탄다. 자주 이용하게 되니 잘 기억해 두자.
 
```
@forelse($items as $item)
  <p>The item is {{ $item }}</p>
@empty
  <p>There is no item !</p>
@endforelse
```

`@forelse` 위에서 `$items = [];`로 변수를 오버라이드하고 다시 실험해 보자.

---

- [5강 - 뷰에 데이터 바인딩하기](https://github.com/appkr/l5essential/blob/master/docs/5-pass-data-to-view.md)
- [7강 - 블레이드 201](https://github.com/appkr/l5essential/blob/master/docs/7-blade-201.md)