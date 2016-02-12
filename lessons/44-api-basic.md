# 실전 프로젝트 3 - RESTful API

**"실전 프로젝트 2 - Forum"** 에서 생성된 게시글/댓글을 JSON API 로 외부에 노출하여, 다양한 앱에서 "포럼" 을 이용할 수 있도록 서비스를 확장해 보자.

## 44강 - API 기본기 및 기획

RESTful API 의 이론에 대해 이해하는 시간을 가져보자.

### RESTful API

#### 먼저 REST 가 무엇인지 알아보자.

- **RE**presentational **S**tate **T**ransfer. 대응되는 한국말 번역이 없어, 대부분 "레스트" 라 그냥 읽는다.
- HTTP 의 특성을 잘 살려서 사용하는 방법에 대해, 그 창시자들이 제안한 **"이종(異種, heterogeneous) 시스템간의 네트워크 통신 구조"**다. 엄격하게 지켜야 하는 스펙은 아니지만, 남들, 특히 이름만 대면 아는 웹 거물들은 모두 쓰므로 꼭 써야 한다.
- RESTful, "레스트" 스러운 HTTP 사용법은 [13강 - RESTful 리소스 컨트롤러](13-restful-resource-controller) 를 필두로 앞선 실전 프로젝트에서도 계속 사용했었다. 이 강좌를 잘 따라오신 분이라면 알게 모르게 쓰고 있었고 이미 알고 있는 개념이다.
- REST 는 **1) Command** *(==Method. HEAD/GET, POST, PUT, ...)*, **2) Things** *(==Resource. articles, comments, ...)*, **3) Response** *(==Message. 200, 422 등의 HTTP 상태 코드와 text/html, application/json 등의 메시지 본문)*, 총 세 가지 큰 덩어리로 구성된다. 

#### (HTTP) API 가 무엇인지 알아보자.

- API 는 시스템간의 커뮤니케이션에 사용된다. 가령, 우리가 만든 앱/서비스는 Laravel 에서 제공하는 API (e.g. `Route::resource()`) 를 이용해서 상호동작한다.
- 이종(異種) 시스템, 가령 Ruby 로 작성된 라이브러리(==API)를 PHP 에서 쓰려면, 양쪽 언어를 다 아는 번역사, 즉 Wrapping 이 필요하다. 사람 세상이랑 참 비슷하다.
- HTTP API 는 서로 다른 시스템간에도 커뮤니케이션을 할 수 있게 한다. 가령, iOS, Android, PC 등 다양한 플랫폼에서 다양한 언어로 구현된 클라이언트가 우리 API 와 데이터를 주고 받을 수 있다. 

#### 종합해 보면. 

영어가 공용어인것 처럼, HTTP 가 다양한 시스템에서 워낙 많이 쓰이기 때문에 거의 공용어 처럼 통한다라고 보면 된다. 모두 종합해 보면, **"서로 다른 시스템간에 네트워크를 경유해서 데이터를 교환할 때 HTTP API 라는 것을 이용하는데, 아무렇게나 짜는 게 아니라, 기계 뿐 아니라 사람이 이해하기 쉽도록, 모두가 사용하고 권장하는 형태인 REST 원칙을 따르도록 짠 API"** 가 **"RESTful API"** 인 것이다.

### RESTful API 베스트 프랙티스

아래는 [10 Best Practices for Better RESTful API](http://blog.mwaysolutions.com/2014/06/05/10-best-practices-for-better-restful-api/) 및 필자의 [RESTful API 제대로 개발하기](http://www.slideshare.net/ssuser7887b3/restful-api) 를 종합해서 정리한 내용이다. 실전 프로젝트를 진행하면서 하나씩 다시 보겠지만, 여기서 한번 정리하고 필요할 때 마다 돌아와서 잘 지키고 있는지 점검하기 위한 목적으로 나열해 본다.

1.  Resource 는 명사를 쓴다. 

    [13강 - RESTful 리소스 컨트롤러](13-restful-resource-controller) 에서 배운 내용을 다시 한번 리마인드 하자. 대신 테이블 형태를 약간 바꾸었다. 여기서 Resource 란 클라이언트 사이드에서 보이는 요소인 URI Endpoint 를 의미한다. 물론 URI 뒤에는 모델이 있는데 클라이언트에게 보이진 않는다.
    
    Type|Resource|GET(Read)|POST(Store)|PUT(Update)|DELETE(Destroy)
    ---|---|---|---|---|---
    콜렉션|/articles|Article 목록|새 Article 만들기|`405 MethodNotAllowed`|`405 MethodNotAllowed`
    인스턴스|/articles/{id}|id 를 가진 Article 상세 보기|`405 MethodNotAllowed`|id 를 가진 Article 수정|id 를 가진 Article 삭제
    
    **`참고`** API 에서는 HTML 뷰를 응답하는 경우가 없으므로, 'GET /articles/create', 'GET /articles/{id}/edit', 2 개의 Endpoint 는 필요 없다.
    
    **`ANTI-PATTERN`** Rosource 이름 (==Endpoint) 에 동사를 쓰지 않는 것이 좋다.
    
    ```
    GET /getAllArticles
    GET /getArticles?id={id}  
    POST /createArticles
    POST /updateArticle?id={id}
    POST /deleteArticles?id={id}
    ```
    
2.  적절한 HTTP 동사 (==Method) 를 사용한다.

    Resource 의 상태를 변경할 때는 `POST`, `PUT`, `DELETE` 메소드를 사용한다.
     
    **`ANTI-PATTERN`** 잘못된 메소드 사용이 불러올 재앙.
     
    > My favorite WTF story is using a GET verb to delete resources. Which was interesting when Google crawled the API. <small>by Jamie Hannaford</small><br>
    > 리소스 삭제를 위한 API Endpoint 를 DELETE 대신 GET 메소드로 정의했다. 구글 검색엔진 크롤러가 방문할 때 마다, 서비스는 안드로메다로 간다.
     
3. Resource 이름은 복수를 사용하고, 일관된 대소문자 규칙을 적용할 것을 권장한다.
 
    1 번의 예에서 article 보다는 articles 가 더 낫다. Resource 이름 뿐 아니라, 필드명에서도 Snake case (e.g. snake_case), Camel case (e.g. camelCase), Dash case (e.g. dash-case) 를 일관되게 사용하자. 
    
    ```
    /article 보다는 /articles
    /comment 보다는 /comments
    ```
    
    **`ANTI-PATTERN`** 대소문자 혼용 사례.
    
    ```javascript
    // GET /push_messages
    {
      "total": 1540,
      "perPage": 10,
      "current-page": 1,
      "data": ["..."]
    }
    ```

4.  Resource 간 관계를 노출해야 할 경우에는 Resource 를 중첩 (==Nesting) 한다.
    
    ```
    GET /tags/{id}/articles
    ```

5.  복잡한 것들은 모두 물음표 (?) 뒤에 둔다.
      
    -   필터
        
        ```
        GET /articles?filter=notsolved
        ```
        
    -   정렬
    
        ```
        GET /articles?sort=view_count&direction=asc
        ```
        
    -   페이징
    
        ```
        GET /articles?page=2
        ```
        
    -   필드 선택
    
        모바일에서 트래픽은 곧 사용자의 돈이다. 클라이언트가 꼭 필요한 필드만 요청할 수 있는 방법을 제공하는 것은 좋은 API 디자인이다.
    
        ```
        GET /articles?fields=id,title
        ```
                
6.  Content/Language Negotiation

    응답을 받고자 하는 데이터 형태 (==Content-Type) 와 언어는 HTTP 헤더를 이용해서 주고 받는다.
    
    ```
    // Request
    GET /articles<br/>
    Accept: application/json
    Accept-Language: ko-KR
    ```
    
    ```
    // Response
    HTTP/1.1 200 OK
    Content-Type: application/json
    ```
    
    **`참고`** Resource 이름 뒤에 확장자를 붙여 Content Negotiation 을 하는 프레임웍도 있다 (e.g. /articles.json, /articles.xml). Anti-pattern 이라고는 할 수 없지만, 이 경우 API Endpoint 자체가 프레임웍에 의존성을 가지게 된다. 가령 /articles.json 을 그대로 두고, 백엔드 프레임웍을 다른 것으로 변경하고자 한다면, 확장자에 따른 Content Negotiation 로직을 별도로 구현해 주어야 한다. 
    
7.  하위 또는 관련 리소스를 쉽게 찾을 수 있는 링크를 제공한다. (==HATEOAS)
    
    HTML 의 경우 메뉴나 링크로 다른 페이지로 이동할 수 있는 방법을 제공하고 있다. 반면에, API 는 달랑 데이터만 제공하기 때문에, API 를 사용하는 사람이나 기계가 다음에 무엇을 해야 하고, 어디로 가야할 지 전혀 알 수가 없다. 이를 해결하기 위한 방안으로 제시된 것이 HATEOAS (Hypermedia as the Engine of Application State) 이다.
    
    ```javascript
    // GET /articles
    {
      data: [
        {
          id: 1,
          title: "...",
          links: [
            rel: "self",
            href: "http://api.example.com/v1/articles"
          ]
          author: {
            id: 5,
            name: "...",
            links: [
              rel: "self",
              href: "http://api.example.com/v1/users/5"
            ]
          }
        },
        {"..."}
      ]
    }
    ```

8.  API 버전
    
    HTML 페이지의 경우에는 코드 배포만 하면, 사용자는 언제든지 최신 코드를 이용하게 된다. 반면, API 의 경우에는 클라이언트와 서버가 완전히 분리 (==Decoupling) 된다. 즉, 서버 사이드에서 API 를 변경하더라도, 클라이언트는 여전히 **정상적인 동작을 기대하면서** 변경 전 API 를 이용할 수 있다는 의미이다. 점진적 마이그레이션을 위해서 API 버저닝은 꼭 필요하고, 처음에 '/articles' 로 Endpoint 를 만든후, 변경되면 '/v2/articles' 로 가지 말고, 반드시 처음 부터 '/v1/articles' 로 만들 것을 권장한다.
       
    ```
    GET http://api.example.com/v1/articles
    GET http://example.com/api/v1/articles
    ```
   
9.  적절한 HTTP 응답 코드를 사용하자.
    
    꽤 큰 회사/서비스 임에도 불구하고 에러일 경우에도 200 응답 코드를 사용하는 경우를 많이 봤다. HTTP 응답 코드는 괜히 있는 것이 아니며, 200 만 쓰는 것은 REST 원칙에도 어긋난다. 아래는 HTTP API 에서 주로 사용하는 응답 코드이다.
    
    ```
    200 - Ok                                // 성공
    201 - Created                           // 새로운 리소스 생성 요청에 대한 응답                     
    204 - No Content                        // 리소스 삭제 요청 성공 등에 사용
    304 - Not Modified                      // 클라이언트에 캐시된 리소스 대비 서버 리소스의 변경이 없을 때 
    400 - Bad Request                       // 클라이언트 쪽에서 뭔가 잘못했을 때
    401 - Unauthorized                      // 인증 필요 (실제로는 Unauthenticated 의 의미)
    403 - Forbidden                         // 권한 부족 (실제로는 Unauthorized 의 의미)
    404 - Not Found                         // 요청한 리소스가 없을 때
    405 - Method Not Allowed                // 서버에 없는 Endpoint 일 때
    406 - Not Acceptable                    // Accept* 헤더 또는 본문의 내용이 수용할 수 없을 경우 
    409 - Conflict                          // 기존 리소스와 충돌
    410 - Gone                              // 404 가 아니라, 리소스가 삭제되어 응답을 줄 수 없을 경우
    422 - Unprocessable Entity,             // 유효성 검사 오류 등에 사용
    429 - Too Many Requests,                // Rate Limit 에 걸렸을 경우
    500 - Internal Server Error             // 서버 쪽 오류
    503 - Service Unavailable               // 점검 등으로 서버가 일시적으로 응답할 수 없는 경우
    ```
    
    클라이언트 측 개발자가 잘 이해할 수 있는 에러 내용도 같이 담아 주는 것이 좋다.
    
    ```javascript
    {
      "errors": {
        "code": 422,
        "message": [
          "title": "The title filed is required" 
        ]
        "dict": http://api.example.com/v1/docs#errors422
      }
    }
    ```

10. HTTP 메소드 오버라이드
    
    [13강 - RESTful 리소스 컨트롤러](13-restful-resource-controller) 에서 이미 설명한 바 있는 내용이다. 모던 브라우저 또는 네트워크 프록시들이 GET, POST 메소드만 이해하기 때문에 PUT, DELETE 메소드를 사용할 때는 `_method=put` 과 같이 사용해야 한다고.. 라라벨은 `X-HTTP-Method-Override` HTTP 헤더를 이용한 메소드 오버라이드도 지원한다.
    
    ```
    POST /articles
    ---payload---
    _method=PUT&title=...&content=...
    ```
    
    or
    
    ```
    POST /articles
    X-HTTP-Method-Override=PUT
    ---payload---
    title=...&content=...
    ```

### 어떤 기능을 가질 것인가?
    
- 앞서 베스트 프랙티스라고 나열한 기능은 모두 구현해 보자.
- 기존과 다른 것은 응답의 형태 뿐이다. 앞 강에서 구현한 컨트롤러를 최대한 활용하자.
- 뷰 뒤에 숨은 데이터가 아니다. 여기서는 데이터가 서비스이다. 데이터 Transform/Serialization 을 지원한다.
- JWT (==JSON Web Token) 을 이용한 사용자 인증을 지원한다.
- Rate Limit 를 구현한다.
- 보안 목적으로 Auto-increment ID 를 숨길 것이다.
- 브라우저 클라이언트를 위해 CORS (Cross Origin Resource Sharing) 기능을 지원한다.

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
- [45강 - 기본 구조 잡기](45-api-big-picture.md)

<!--@end-->