---
extends: _layouts.master
section: content
current_index: 58
---

# Homestead 설치 (on Windows)

## 사전 요구 사항

[VirtualBox](https://www.virtualbox.org/wiki/Downloads) 와 [Vagrant](http://www.vagrantup.com/downloads.html) 설치가 필요하다. 인스톨러 화면에서 "Next" 만 계속 눌러서 쉽게 설치할 수 있다.
 
## Vagrant Box 설치

라라벨 커뮤니티에서 미리 준비해서 [Vagrant Box Registry](https://atlas.hashicorp.com/boxes/search) 에 배포해 놓은 `laravel/homestead` Vagrant Box (==Virtual Machine Image) 를 다운로드 하는 과정이다. 이 강좌를 쓰는 시점에 `laravel/homestead` Vagrant Box 의 최신 버전은 PHP7 이 기본 포함되어 있는 0.4.0 이다.

```bash
$ vagrant box add laravel/homestead

# 설치에 실패했다면, 기존 다운로드 찌거기를 지우고 다시 다운로드 받기 위해 --clean 옵션 스위치를 붙여야 한다.
# 기존에 설치한 laravel/homestead Box 을 덮어쓰고 강제로 다시 설치하려면 --force 옵션 스위치를 붙인다.
```

설치 과정에 아래 처럼 Virtual Machine Provider 를 선택하는 화면이 나온다. `2) vmware` 는 [Vagrant 용 Plugin](http://www.vagrantup.com/vmware) 을 별도로 사서 설치해야 한다는 점을 알고 있자. 이 강좌를 쓰는 현재 Vmware Vagrant Plugin 의 가격은 $79 이다. 무료로 쓸 수 있는 `1) virtualbox` 를 선택했다. OS 에 따라 수 GB 용량을 다운로드 받아야 하므로 꽤 오랜 시간이 걸린다.

```bash
This box can work with multiple providers! The providers that it
can work with are listed below. Please review the list and choose
the provider you will be working with.

1) virtualbox
2) vmware_desktop

Enter your choice: 1
```

## Homestead 프로젝트 설치

`git` 명령을 쓸 수 없는 경우, [https://github.com/laravel/homestead](https://github.com/laravel/homestead) 를 방문해서 zip 파일을 다운로드 한 후, 적절한 위치에 압축을 해제한다. 

```bash
# Git Bash
$ cd ~ && git clone https://github.com/laravel/homestead.git Homestead
```

## Homestead 설정

`laravel/homestead` Vagrant VM 을 올바르게 셋팅하기 위한 설정 파일은 '~/.homestead' 디렉토리에 위치해야 한다. 아래 명령으로 이 설정 파일을 초기화하자.

```bash
# Git Bash
$ cd ~/Homestead && bash init.sh

# Windows Command Prompt
\> cd %HOMEPATH%\Homestead
\> init
```

Homestead 설정을 우리 프로젝트에 맞게 수정하자. 코드 에디터 또는 텍스트 편집기로 %HOMEPATH%\.homestead\Homestead.yaml 파일을 연다.

```bash
# /c/Users/{username}/.homestead/Homestead.yaml
# Or %HOMEPATH%\.homestead\Homestead.yaml

ip: "192.168.10.10" # homestead VM 이 사용할 ip 주소
memory: 2048
cpus: 1
provider: virtualbox # Virtual Machine Provider

# SSH 로그인에 사용할 public key. 이 키 값은 homestead VM 의 
# /home/vagrant/.ssh/authorized_keys 에 자동으로 추가된다.
authorize: ~/.ssh/id_rsa.pub 

keys:
    - ~/.ssh/id_rsa # SSH 로그인에 사용할 private key

# 로컬과 VM 간에 공유할 폴더를 설정한다.
# 이 강좌용 라라벨은 ~/myProject 에 위치한다고 가정한다.
folders:
    - map: ~/myProject # 로컬 디렉토리 경로
      to: /home/vagrant/myProject # VM 의 디렉토리 경로

# 도메인 이름 (hostname) 과 VM 에 설치된 웹 서버의 Document Root 를 설정한다.
sites:
    - map: myproject.dev # 도메인 이름
      to: /home/vagrant/myProject/public # 웹 서버의 Document Root
    # 사이트를 추가하려면.. 아래 처럼 추가 사이트를 정의한 다음
    # VM 콘솔에서 $ vagrant provision 명령을 실행한다.
    - map: example.dev
      to: /home/vagrant/example/public
```

## Host 파일 설정

Homestead 설정에서 myproject.dev 란 도메인을 이용했다. 이런 도메인은 존재하지 않는다. 운영체제의 Host 파일을 수정할 것이다. 운영체제에 포함된 'hosts' 파일은 DNS 로 myproject.dev 에 대한 ip 주소 Resolution 요청이 나가기 전에 요청을 낚아 채서, 'hosts' 파일 안에서 찾는다. 사용자가 요청한 도메인에 해당하는 레코드가 있으면 지정된 ip 주소로 이동할 것이다.

텍스트 편집기나 코드 에디터로 `%WINDOR%\System32\drivers\etc\hosts` 파일을 열어 아래 레코드를 추가한다.

```bash
# %WINDOR%\System32\drivers\etc\hosts

192.168.10.10    myproject.dev
```

![](./images/02-install-homestead-windows-img-01.png)

## SSH Key 생성

Homestead 설정에서 `authorize: ~/.ssh/id_rsa.pub` 와 `~/.ssh/id_rsa` 를 설정했는데, 해당 파일은 존재하지 않는다. Git Bash 를 쓸 수 없다면, [PuTTYgen](http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html) 을 설치하고 private, public key pair 를 만들자.
 
```bash
# Git Bash

$ mkdir ~/.ssh
$ ssh-keygen -t rsa
# Enter file in which to save the key (/c/Users/suchc/.ssh/id_rsa): <Enter>
# Enter passphrase (empty for no passphrase): <Enter>
# Enter same passphrase again: <Enter>
```

## Homestead 실행

실행해 보자. 처음 실행할 때는 시간이 좀 걸리는데, 이유는 앞에서 Homestead.yaml 에 설정한, ip 주소, public key 복사, 공유 폴더 설정 등을 하기 때문이다. 처음 실행할 때는 방화벽 관련 보안 경고가 뜰 수 있는 데 "허용" 해 주자. 

```bash
# Git Bash
$ cd ~/Homestead && vagrant up

# homestead VM 을 중지시킬 때는 $ vagrant halt
# 완전히 끌 때는 $ vagrant suspend

# Windows Command Prompt
\> cd %HOMEPATH%\Homestead
\> vagrant up
```

VM 에 로그인하고 Homestead.yaml 설정이 잘 먹었나 확인해 보자. 코맨드 프롬프트에서는 SSH Client 가 없어서 안되는 작업이니, 반드시 Git Bash 를 이용해야 한다.

```bash
# Git Bash only

$ vagrant ssh
# Welcome to Ubuntu 14.04.3 LTS (GNU/Linux 3.19.0-25-generic x86_64)

vagrant@homestead:~$ ls ~/myProject
# app ... bootstrap ...

vagrant@homestead:~$ ifconfig | grep 192.168.10.10
# inet addr:192.168.10.10  Bcast:192.168.10.255  Mask:255.255.255.0

vagrant@homestead:~$ cat ~/.ssh/authorized_keys
# ssh-rsa AAAAB3NzaC1...TpJ5HH suchc@homepc
```

**`참고`** ssh 를 이용해 직접 접속하려면 `$ ssh vagrant@myproject.dev -D 2222`. 

## 데이터베이스 접속

Host `127.0.0.1`, Port `33060`, Username `homestead`, Password `secret` 로 접속한다. PostgresSQL 의 경우 Port `54320` 으로 접속한다. 필자는 [SQLyog](https://code.google.com/p/sqlyog/wiki/Downloads) 클라이언트를 이용하였다.

![](./images/02-install-homestead-windows-img-02.png)

## 웹 서버 접속

Homestead 에는 Nginx 가 기본으로 탑재되어 있고, Homestead.yaml 의 sites 섹션에서 설정한대로 이미 서비스가 돌고 있는 상태이다.

브라우저에서 'http://myproject.dev' 로 접속해 보자. 테스트용으로 쓸 수 있는 self-signed 인증서가 설치되어 있기 때문에 'https:://myproject.dev' 도 사용할 수 있다.

![](./images/02-install-homestead-windows-img-03.png)

<!--@start-->
---

- [목록으로 돌아가기](../readme.md)
<!--@end-->
