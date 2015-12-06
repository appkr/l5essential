#--------------------------------------------------------------------------
# List of tasks, that you can run...
# e.g. envoy run hello
#--------------------------------------------------------------------------
#
# hello             Check ssh connection
# release           Publish new release
# provision         Server provision
# init              Initialize Laravel environment
#

@servers(['aws-demo' => 'ec2-52-193-67-224.ap-northeast-1.compute.amazonaws.com', 'homestead' => 'homestead.vm'])

@setup
  $projectName = 'l5essential';
  $userName = 'vagrant';
  $homePath = '/home/' . $userName;
  $codePath = $homePath . '/code';
  $basePath = $codePath . '/' . $projectName;
  $repoUrl = 'git@github.com:appkr/l5essential.git';
  $domainName = 'l5.appkr.kr';
@endsetup

@task('hello', ['on' => 'homestead'])
  echo "Hello Envoy!";
@endtask

{{--@after
  @slack('https://hooks.slack.com/services/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', '#l5essential', 'Hello Envoy!')
@endafter--}}

@task('hello2', ['on' => 'aws-demo'])
  echo "Hello Envoy!";
@endtask

@task('release', ['on' => 'aws-demo', 'confirm' => true])
  # pull code from the repository.
  cd {{ $basePath }} && git pull;
  # composer install;
  # ln -nfs ../.env .env
  # rm -rf storage
  # ln -nfs ../storage storage
  # rm -rf bootstrap/cache
  # ln -nfs ../cache bootstrap/cache
  # rm -rf public/attachments
  # ln -nfs ../attachments public/attachments
@endtask

@task('provision', ['on' => 'aws-demo', 'confirm' => true])
  # curl https://raw.githubusercontent.com/appkr/l5essential/master/provision.sh -O {{ $homePath }}/provision.sh
  # curl https://raw.githubusercontent.com/appkr/l5essential/master/serve.sh -O {{ $homePath }}/serve.sh

  # Install and set required server packages... e.g. php, mysql, ...
  {{ $homePath }}/provision.sh {{ $userName }};

  # Configure nginx for domain endpoint and server path.
  {{ $homePath }}/serve.sh {{ $domainName }} {{$basePath}}/public;
@endtask

@task('init', ['on' => 'aws-demo', 'confirm' => true])
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