#--------------------------------------------------------------------------
# List of tasks, that you can run...
# e.g. envoy release
#--------------------------------------------------------------------------
#
# hello             Check ssh connection
# release           Publish new release
# provision         Server provision
# init              Initialize Laravel environment
#

@servers(['aws' => 'ec2-52-192-13-191.ap-northeast-1.compute.amazonaws.com'])

@setup
  $projectName = 'l5essential';
  $userName = 'vagrant';
  $homePath = '/home/' . $userName;
  $codePath = $homePath . '/code';
  $basePath = $codePath . '/' . $projectName;
  $repoUrl = 'git@github.com:appkr/l5essential.git';
  $domainName = 'l5essential.appkr.kr';
@endsetup

@task('hello_envoy', ['on' => 'aws'])
  echo "Hello Envoy!";
@endtask

@task('release', ['on' => 'aws', 'confirm' => true])
  # pull code from the repository.
  cd {{ $basePath }} && git pull;
@endtask

@task('provision', ['on' => 'aws', 'confirm' => true])
  # curl https://raw.githubusercontent.com/appkr/l5essential/master/provision.sh -O {{ $homePath }}/provision.sh
  # curl https://raw.githubusercontent.com/appkr/l5essential/master/serve.sh -O {{ $homePath }}/serve.sh

  # Install and set required server packages... e.g. php, mysql, ...
  {{ $homePath }}/provision.sh {{ $userName }};

  # Configure nginx for domain endpoint and server path.
  {{ $homePath }}/serve.sh {{ $domainName }} {{$basePath}}/public;
@endtask

@task('init', ['on' => 'aws', 'confirm' => true])
  # Make a directory at server, which will house the codes.
  if [ ! -d {{ $codePath }} ]; then
    mkdir {{ $codePath}};
  fi;

  # Clone the source from the git repository.
  cd {{ $codePath }} && git clone -b master {{ $repoUrl }} {{ $projectName }};

  # Run composer.
  cd {{ $basePath }} && composer install;

  # Make application key.
  php {{ $basePath }}/artisan key:generate;

  # Make 'attachments' direcotry, whil will hold file uploads from users.
  if [ ! -d {{ $basePath }}/public/attachments ]; then
    mkdir {{ $basePath }}/public/attachments;
  fi;

  # Set directory permission.
  chmod -R 777 {{ $basePath }}/storage {{ $basePath }}/bootstrap/cache {{ $basePath }}/public/attachments;

  # Run migration.
  php {{ $basePath }}/artisan migrate;

  # Run seeding.
  php {{ $basePath }}/artisan db:seed;
@endtask