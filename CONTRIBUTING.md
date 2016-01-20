# Contribution Guide

이 강좌에 여러가지 방법으로 기여할 수 있습니다. 

-   강좌 오류 수정
-   코드 오류 수정
-   ...

기여자 분들은기여의 형태에 따라 [Contributor List](https://github.com/appkr/l5essential/graphs/contributors) 에 올라 가거나, [기여자](https://github.com/appkr/l5essential#기여자), [도움주신 분들](https://github.com/appkr/l5essential#도움-주신-분들-sponsor) 에 등록되어 기여자로 인정해 드립니다.

## Pull Request

-   PSR-2 코딩 컨벤션을 지켜 주세요. (코드일 경우에만)
-   수정 내용을 설명해 주세요.
-   Fork 한후 Topic Branch 를 만들어서 PR 을 보내 주세요.
-   PR 전에 최신 코드인지를 확인해 주세요. (`$ git pull`)
-   단순 오류 수정이 아니라, 동작을 변경하는 코드라면 Test 를 포함해 주세요.

## Test
이 강좌의 코드는 Integration Test 를 포함하고 있습니다. PR 전에 테스트를 돌려 보세요.

```sh
$ phpunit
```

감사합니다.
   
   