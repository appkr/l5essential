#--------------------------------------------------------------------------
# List of tasks, that you can run...
# e.g. envoy run hello
#--------------------------------------------------------------------------
#
# hello     Check ssh connection
# release   Publish new release
# list      Show list of releases
# checkout  Checkout to the given release (must provide --release=/path/to/release)
# prune     Purge old releases (must provide --keep=n, where n is a number)
#


@servers(['web' => 'aws-seoul-deploy'])


@setup
  $path = [
    'base' => '/home/deployer/www',
    'docroot' => '/home/deployer/www/l5.appkr.kr',
    'shared' => '/home/deployer/www/shared',
    'release' => '/home/deployer/www/releases',
  ];

  $required_dirs = [
    $path['base'],
    $path['shared'],
    $path['release'],
  ];

  $shared_item = [
    '/home/deployer/www/shared/.env' => '.env',
    '/home/deployer/www/shared/storage' => 'storage',
    '/home/deployer/www/shared/cache' => 'bootstrap/cache',
    '/home/deployer/www/shared/attachments' => 'public/attachments',
  ];

  $distribution = [
    'name' => 'release_' . date('YmdHis'),
  ];

  $git = [
    'repo' => 'git@github.com:appkr/l5essential.git',
  ];
@endsetup


@task('hello', ['on' => ['web']])
  HOSTNAME=$(hostname);
  echo "Hello Envoy! Responding from $HOSTNAME";
@endtask


@task('release', ['on' => ['web']])
  {{--Create directories if not exists--}}
  @foreach ($required_dirs as $dir)
    if [ ! -d {{ $dir }} ]; then
      mkdir {{ $dir }};
      chgrp -h -R www-data {{ $dir }};
    fi;
  @endforeach

  {{--Download book keeping officer--}}
  if [ ! -f {{ $path['base'] }}/officer.php ]; then
    wget https://raw.githubusercontent.com/appkr/envoy/master/scripts/officer.php -O {{ $path['base'] }}/officer.php;
  fi;

  {{--Fetch code from git--}}
  cd {{ $path['release'] }};
  git clone -b master {{ $git['repo'] }} {{ $distribution['name'] }};

  {{--Symlink shared directory to current release.--}}
  {{--e.g. storage, .env, user uploaded file storage, ...--}}
  cd {{ $path['release'] }}/{{ $distribution['name'] }};
  @foreach($shared_item as $global => $local)
    [ -f {{ $local }} ] && rm {{ $local }};
    [ -d {{ $local }} ] && rm -rf {{ $local }};
    ln -nfs {{ $global }} {{ $local }};
    chgrp -h -R www-data {{ $local }};
  @endforeach

  {{--Run composer install--}}
  composer install --prefer-dist --no-scripts;
  php artisan clear-compiled;
  php artisan optimize;
  php artisan cache:clear;
  php artisan my:update-lesson;

  {{--Symlink current release to service directory.--}}
  ln -nfs {{ $path['release'] }}/{{ $distribution['name'] }} {{ $path['docroot'] }};
  chgrp -h -R www-data {{ $path['docroot'] }};

  {{--Set permission and change owner. Do one final more for safety.--}}
  chgrp -h -R www-data {{ $path['release'] }}/{{ $distribution['name'] }};

  {{--Book keeping--}}
  php {{ $path['base'] }}/officer.php deploy {{ $path['release'] }}/{{ $distribution['name'] }};

  {{--Restart web server.--}}
  sudo service nginx restart;
  sudo service php5-fpm restart;
@endtask


@task('prune', ['on' => 'web'])
  if [ ! -f {{ $path['base'] }}/officer.php ]; then
    echo '"officer.php" script not found.';
    echo '\$ envoy run hire_officer';
    exit 1;
  fi;

  @if (isset($keep) and $keep > 0)
    php {{ $path['base'] }}/officer.php prune {{ $keep }};
  @else
    echo 'Must provide --keep=n, where n is a number.';
  @endif
@endtask


@task('hire_officer', ['on' => 'web'])
  {{--Download "officer.php" to the server--}}
  wget https://raw.githubusercontent.com/appkr/envoy/master/scripts/officer.php -O {{ $path['base'] }}/officer.php;
  echo '"officer.php" is ready!';
@endtask


@task('list', ['on' => 'web'])
  {{--Show the list of release--}}
  if [ ! -f {{ $path['base'] }}/officer.php ]; then
    echo '"officer.php" script not found.';
    echo '\$ envoy run hire_officer';
    exit 1;
  fi;

  php {{ $path['base'] }}/officer.php list;
@endtask


@task('checkout', ['on' => 'web'])
  {{--Checkout to the given release path--}}
  if [ ! -f {{ $path['base'] }}/officer.php ]; then
    echo '"officer.php" script not found.';
    echo '\$ envoy run hire_officer';
    exit 1;
  fi;

  @if (isset($release))
    cd {{ $release }};

    {{--Symlink shared directory to the given release.--}}
    @foreach($shared_item as $global => $local)
      [ -f {{ $local }} ] && rm {{ $local }};
      [ -d {{ $local }} ] && rm -rf {{ $local }};
      ln -nfs {{ $global }} {{ $local }};
      chgrp -h -R www-data {{ $local }};
    @endforeach

    {{--Symlink the given release to service directory.--}}
    ln -nfs {{ $release }} {{ $path['docroot'] }};
    chgrp -h -R www-data {{ $path['docroot'] }};

    {{--Book keeping--}}
    php {{ $path['base'] }}/officer.php checkout {{ $release }};

    {{--Restart web server.--}}
    sudo service nginx restart;
    sudo service php5-fpm restart;
  @else
    echo 'Must provide --release=/full/path/to/release.';
  @endif
@endtask