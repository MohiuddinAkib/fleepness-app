@servers([
    'staging' => [getenv('ENVOY_STAGING_SERVER') ?: 'deploy@example.com'],
    'production' => [getenv('ENVOY_PRODUCTION_SERVER') ?: 'deploy@example.com'],
])

@setup
    $appDir = getenv('ENVOY_APP_DIR') ?: '/var/www/backend_v2';
    $branch = getenv('ENVOY_BRANCH') ?: 'staging';
    $composerBinary = getenv('ENVOY_COMPOSER_BINARY') ?: 'composer';
    $phpBinary = getenv('ENVOY_PHP_BINARY') ?: 'php';
    $npmBinary = getenv('ENVOY_NPM_BINARY') ?: 'npm';
    $artisan = "{$phpBinary} artisan";
    $composerFlags = '--no-interaction --prefer-dist --optimize-autoloader --no-dev';
@endsetup

@story('deploy', ['on' => 'staging'])
    verify_app_directory
    application_down
    update_code
    install_php_dependencies
    install_node_dependencies
    build_assets
    run_migrations
    cache_application
    restart_background_services
    application_up
@endstory

@story('deploy-production', ['on' => 'production'])
    verify_app_directory
    application_down
    update_code
    install_php_dependencies
    install_node_dependencies
    build_assets
    run_migrations
    cache_application
    restart_background_services
    application_up
@endstory

@task('verify_app_directory')
    [ -d {{ $appDir }} ]
@endtask

@task('application_down')
    cd {{ $appDir }}
    {{ $artisan }} down --retry=60 || true
@endtask

@task('update_code')
    cd {{ $appDir }}
    git fetch origin {{ $branch }}
    git checkout {{ $branch }}
    git pull --ff-only origin {{ $branch }}
@endtask

@task('install_php_dependencies')
    cd {{ $appDir }}
    {{ $composerBinary }} install {{ $composerFlags }}
@endtask

@task('install_node_dependencies')
    cd {{ $appDir }}
    if [ -f package-lock.json ]; then
        {{ $npmBinary }} ci --no-audit --no-fund
    else
        {{ $npmBinary }} install --no-audit --no-fund
    fi
@endtask

@task('build_assets')
    cd {{ $appDir }}
    {{ $npmBinary }} run build
@endtask

@task('run_migrations')
    cd {{ $appDir }}
    {{ $artisan }} migrate --force
@endtask

@task('cache_application')
    cd {{ $appDir }}
    {{ $artisan }} optimize:clear
    {{ $artisan }} config:cache
    {{ $artisan }} event:cache
    {{ $artisan }} view:cache
@endtask

@task('restart_background_services')
    cd {{ $appDir }}
    {{ $artisan }} queue:restart
    {{ $artisan }} octane:reload || true
    {{ $artisan }} reverb:restart || true
@endtask

@task('application_up')
    cd {{ $appDir }}
    {{ $artisan }} up || true
@endtask
