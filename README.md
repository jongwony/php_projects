Publ
===

Blogging Tool based on Festiv!

## 사용하는 프로그램

- bower
- composer
- npm

## Installation

개발자 전용이라 일단 조금 복잡합니다. Git으로 일단 가지고 옵니다.

```bash
$ git clone git@github.com:Festiv/Publ.git ./Publ
```

컴포저로 설치를 합니다.

```bash
$ cd Publ # Git으로 Clone한 경로
$ composer install --prefer-source # 개발자 버전이라  --prefer-source를 사용합니다.
```

설정파일을 조정해줍니다.

```bash
$ cp config.sample.php config.php
```

데이터베이스를 업그레이드 해주어야 합니다. 만약 여기서 에러가 발생하면 데이터베이스 연결이 잘못되었다는 이야기입니다. 위에
`config.php`파일을 잘 조절해줍니다.

```bash
$ ./vendor/bin/phpmig migrate
```

Frontend 빌드를 위한 스크립트도 설치해줍시다.

```bash
$ bower install
$ npm install
$ gulp build
```

내장 PHP서버로 테스트 서버를 띄워봅시다

```
$ php -S 0.0.0.0:8001 -t public
```

브라우저에 `http://localhost:8001`를 입력했을 때 Hello Publ이 나오면 모든 설치가 끝난겁니다.
`http://localhost:8001/admin`에 들어가서 회원가입을 하고, 데이터베이스에서 `grant`항목을 '0'으로 수정해야 관리자에
접근이 가능해집니다. (이 부분은 나중에 커맨드 라인으로 제공할 예정.)

