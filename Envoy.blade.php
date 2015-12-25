#--------------------------------------------------------------------------
# List of tasks, that you can run...
# e.g. envoy run hello
#--------------------------------------------------------------------------
#
# hello             Check ssh connection
# deploy            Publish new release
# rollback          TODO - Rollback to previous release
# provision         Prepare a server
# migration         Run database migration
#

# @include not work! So resorted back to traditional way~.
<?php include(__DIR__ . '/./envoy.config.php'); ?>


@servers(['aws-demo' => 'l5.appkr.kr', 'barebone' => 'barebone.vm'])


@task('hello', ['on' => ['aws-demo', 'barebone']])
  HOSTNAME=$(hostname);
  echo "Hello Envoy! Responding from $HOSTNAME";
@endtask


@macro('deploy', ['on' => 'barebone', 'confirm' => true])
  prepare_path
  fetch_repo
  update_symlinks
  update_permissions
  run_composer
  prune_release
@endmacro


@task('prepare_path', ['on' => 'barebone'])
  paths=("{{ config('path.web') }}" "{{ config('path.shared') }}" "{{ config('path.shared') }}/attachments" "{{ config('path.shared') }}/backup" "{{ config('path.shared') }}/logs" "{{ config('path.release') }}");

  for path in "${paths[@]}"; do
    if [ ! -d $path ]; then
      mkdir $path;
      sudo chown -R {{ config('server.user') }}:www-data $path;
    fi;
  done;
@endtask


@task('fetch_repo', ['on' => 'barebone'])
  cd {{ config('path.release') }};
  git clone -b master {{ config('git.repo') }} {{ config('release.name') }};
@endtask


@task('update_permissions', ['on' => 'barebone'])
  cd {{ config('path.release') }};
  chgrp -R www-data {{ config('release.name') }};
  # chmod -R ug+rwx {{ config('release.name') }};

  cd {{ config('path.release') }}/{{ config('release.name') }};
  chmod -R 777 storage bootstrap/cache public/attachments;
@endtask


@task('update_symlinks', ['on' => 'barebone'])
  # Symlink global .env to current release.

  cd {{ config('path.release') }}/{{ config('release.name') }};
  ln -nfs {{ config('path.shared') }}/.env .env;
  chgrp -h www-data .env;

  # Symlink global laravel.logs to current release.

  rm -r {{ config('path.release') }}/{{ config('release.name') }}/storage/logs;
  cd {{ config('path.release') }}/{{ config('release.name') }}/storage;
  ln -nfs {{ config('path.shared') }}/logs logs;
  chgrp -h www-data logs;

  # Symlink global backup dir to current release.

  rm -r {{ config('path.release') }}/{{ config('release.name') }}/storage/backup;
  cd {{ config('path.release') }}/{{ config('release.name') }}/storage;
  ln -nfs {{ config('path.shared') }}/backup backup;
  chgrp -h www-data backup;

  # Symlink global attachments path to current release.

  rm -r {{ config('path.release') }}/{{ config('release.name') }}/public/attachments;
  cd {{ config('path.release') }}/{{ config('release.name') }}/public;
  ln -nfs {{ config('path.shared') }}/attachments attachments;
  chgrp -h www-data attachments;

  # Symlink current release to service directory.

  ln -nfs {{ config('path.release') }}/{{ config('release.name') }} {{ config('path.base') }};
  chgrp -h www-data {{ config('path.base') }};
@endtask


@task('run_composer', ['on' => 'barebone'])
  cd {{ config('path.release') }}/{{ config('release.name') }};
  composer install --prefer-dist --no-scripts;

  php artisan clear-compiled --env=production;
  php artisan optimize --env=production;
  php artisan cache:clear --env=production;
  # php artisan my:update-lessons;

  service nginx restart;
  service php5-fpm restart;
@endtask


@task('prune_release', ['on' => 'barebone'])
  php {{ config('path.base') }}/artisan my:prune-release {{ config('path.release') }} --keep={{ config('release.keep') }};
@endtask


@task('provision', ['on' => 'barebone', 'confirm' => true])
  #--------------------------------------------------------------------------
  # Having problem? Look up a explanation in provision.sh and serve.sh
  #--------------------------------------------------------------------------

  echo 'Downloading required scripts.';

  curl https://raw.githubusercontent.com/appkr/l5essential/master/provision.sh -O {{ config('path.home') }}/provision.sh;
  curl https://raw.githubusercontent.com/appkr/l5essential/master/serve.sh -O {{ config('path.home') }}/serve.sh;

  echo 'Preparing server (e.g. php, mysql, nginx, ...). This will take long...';

  {{ config('path.home') }}/provision.sh {{ config('server.user') }};

  echo 'Configuring web server.';

  {{ config('path.home') }}/serve.sh {{ config('server.domain') }} {{ config('path.base') }}/public;

  echo 'Registering crontab entry.';
  cat << EOT | sudo tee -a /var/spool/cron/{{ config('server.user') }}
* * * * * php {{ config('path.base') }}/artisan schedule:run 1>> /dev/null 2>&1
EOT;

  echo 'Installing github key.';
  block="{
  "github-oauth": {
    "github.com": "{{ config('git.token') }}"
  }
}
";
  echo "$block" > "{{ config('path.home') }}/.composer/auth.json";

  echo 'Done.';
  echo '';
  echo 'NOTICE:';
  echo 'To deploy a code from the github,';
  echo 'install your ssh key on {{ config('server.host') }} server.';
  echo 'Then, run $ envoy run deploy on your local machine.';
@endtask


@task('migration', ['on' => 'barebone', 'confirm' => true])
  #--------------------------------------------------------------------------
  # Before run this command, confirm the database is ready.
  #--------------------------------------------------------------------------

  cd {{ config('path.base') }};
  composer dumpautoload;
  php artisan migrate --seed --force;
@endtask
