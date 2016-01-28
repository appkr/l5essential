# Contribution Guide

이 강좌에 여러가지 방법으로 기여할 수 있습니다. 

-   강좌 오류 수정
-   코드 오류 수정
-   ...

기여자 분들은 기여의 형태에 따라 [Contributors / Sponsors](https://github.com/appkr/l5essential#contributors--sponsors) 또는 [Contributor List](https://github.com/appkr/l5essential/graphs/contributors) 에 등록됩니다. 이 강좌는 독자 및 기여자 여러분들과 같이 만들어 가는 것입니다. 여러 분들의 기여 활동은 이 강좌를 접하는 많은 분들에게 도움이 될 것입니다. 

## Pull Request

-   코드는 [PSR-2 코딩 컨벤션](http://www.php-fig.org/psr/psr-2/) 을 지켜 주세요.
-   수정 내용을 설명해 주세요.
-   Fork 한후 Topic Branch 를 만들어서 PR 을 보내 주세요.
-   PR 전에 최신 코드인 지를 확인해 주세요. (`$ git pull && git merge master`)
-   단순 오류 수정이 아니라, 동작을 변경하는 코드라면 Test 를 포함해 주세요.

## Test

이 강좌의 코드는 Integration Test 를 포함하고 있습니다. 코드를 수정했다면 PR 전에 테스트를 실행해 주세요. PR 을 하시면 Travis CI 가 테스트를 한번 더 수행합니다.

```sh
$ phpunit
```

감사합니다.

appkr.
   
   
