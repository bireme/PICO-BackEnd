<?php return array (
  'adminconfig' => 
  array (
    'MASTER_ADMIN_DATA' => 'PICOMaster@gmail.com:PICOMaster',
    'ADMIN_DATA' => 'PICOX@gmail.com:PICOX,PICOTWO@gmail.com:PICOTWO',
  ),
  'analytics' => 
  array (
    'view_id' => NULL,
    'service_account_credentials_json' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\app/analytics/service-account-credentials.json',
    'cache_lifetime_in_minutes' => 1440,
    'cache' => 
    array (
      'store' => 'file',
    ),
  ),
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'debug',
    'debug' => true,
    'url' => 'http://localhost',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:b2VmZTTBguW+iqvDBT08Itv+6QAHwmfmKrzEqxXUya0=',
    'cipher' => 'AES-256-CBC',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'GrahamCampbell\\Throttle\\ThrottleServiceProvider',
      23 => 'hedronium\\SpacelessBlade\\SpacelessBladeProvider',
      24 => 'PICOExplorer\\Providers\\AdvancedLoggerProvider',
      25 => 'PICOExplorer\\Providers\\AuthServiceProvider',
      26 => 'PICOExplorer\\Providers\\EventServiceProvider',
      27 => 'PICOExplorer\\Providers\\RouteServiceProvider',
      28 => 'Rap2hpoutre\\LaravelLogViewer\\LaravelLogViewerServiceProvider',
      29 => 'PragmaRX\\Health\\ServiceProvider',
      30 => 'Ixudra\\Curl\\CurlServiceProvider',
      31 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\AuthHandlerServiceProvider',
      32 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\ResultsNumberServiceProvider',
      33 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\ResultsNumberIntegrationServiceProvider',
      34 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\ResultsNumberIntegrationLooperServiceProvider',
      35 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\DeCSLooperServiceProvider',
      36 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\DeCSIntegrationLooperServiceProvider',
      37 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\DeCSServiceProvider',
      38 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\DeCSIntegrationServiceProvider',
      39 => 'PICOExplorer\\Providers\\AdvancedServiceProviders\\QueryBuildServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Throttle' => 'GrahamCampbell\\Throttle\\Facades\\Throttle',
      'Curl' => 'Ixudra\\Curl\\Facades\\Curl',
      'AdvancedLoggerFacade' => 'PICOExplorer\\Facades\\AdvancedLoggerFacade',
      'WarningsHandlerFacade' => 'PICOExplorer\\Facades\\WarningsHandlerFacade',
      'ExceptionLoggerFacade' => 'PICOExplorer\\Facades\\ExceptionLoggerFacade',
      'SpecialValidatorFacade' => 'PICOExplorer\\Facades\\SpecialValidatorFacade',
      'AuthHandlerFacade' => 'PICOExplorer\\Facades\\AuthHandlerFacade',
      'UltraLoggerFacade' => 'PICOExplorer\\Facades\\UltraLoggerFacade',
      'ServicePerformanceSV' => 'PICOExplorer\\Services\\AdvancedLogger\\Services\\ServicePerformance',
      'ResultsNumberFacade' => 'PICOExplorer\\Facades\\ResultsNumberFacade',
      'ResultsNumberIntegrationFacade' => 'PICOExplorer\\Facades\\ResultsNumberIntegrationFacade',
      'ResultsNumberIntegrationLooperFacade' => 'PICOExplorer\\Facades\\ResultsNumberIntegrationLooperFacade',
      'DeCSIntegrationFacade' => 'PICOExplorer\\Facades\\DeCSIntegrationFacade',
      'DeCSIntegrationLooperFacade' => 'PICOExplorer\\Facades\\DeCSIntegrationLooperFacade',
      'DeCSFacade' => 'PICOExplorer\\Facades\\DeCSFacade',
      'DeCSLooperFacade' => 'PICOExplorer\\Facades\\DeCSLooperFacade',
      'QueryBuildFacade' => 'PICOExplorer\\Facades\\QueryBuildFacade',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
        'hash' => false,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'PICOExplorer\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'null',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'encrypted' => true,
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => NULL,
        'secret' => NULL,
        'region' => 'us-east-1',
        'table' => 'cache',
      ),
    ),
    'prefix' => 'laravel_cache',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => false,
        'port' => '3306',
        'database' => 'users',
        'username' => 'laravel-PICO',
        'password' => 'PICO180819',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'options' => 
      array (
        'cluster' => 'predis',
        'prefix' => 'laravel_database_',
      ),
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => 6379,
        'database' => 0,
      ),
      'cache' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => 6379,
        'database' => 1,
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
        'url' => NULL,
      ),
    ),
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 1024,
      'threads' => 2,
      'time' => 2,
    ),
  ),
  'health' => 
  array (
    'title' => 'Laravel Health Check Panel',
    'resources' => 
    array (
      'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\config\\health/resources',
      'enabled' => 
      array (
        0 => 'AppKey',
        1 => 'Cache',
        2 => 'ConfigurationCached',
        3 => 'Database',
        4 => 'DebugMode',
        5 => 'DirectoryPermissions',
        6 => 'DiskSpace',
        7 => 'EnvExists',
        8 => 'Filesystem',
        9 => 'Framework',
        10 => 'Https',
        11 => 'LaravelServices',
        12 => 'Latency',
        13 => 'LocalStorage',
        14 => 'Mail',
        15 => 'MigrationsUpToDate',
        16 => 'MySql',
        17 => 'MySqlConnectable',
        18 => 'NginxServer',
        19 => 'Php',
        20 => 'Queue',
        21 => 'QueueWorkers',
        22 => 'RebootRequired',
        23 => 'Redis',
        24 => 'RedisConnectable',
        25 => 'RedisServer',
        26 => 'RoutesCached',
        27 => 'SecurityChecker',
        28 => 'ServerLoad',
        29 => 'ServerUptime',
        30 => 'Supervisor',
      ),
    ),
    'sort_by' => 'slug',
    'cache' => 
    array (
      'key' => 'health-resources',
      'minutes' => false,
    ),
    'database' => 
    array (
      'enabled' => false,
      'graphs' => 
      array (
        'enabled' => true,
        'height' => 90,
      ),
      'max_records' => 30,
      'model' => 'PragmaRX\\Health\\Data\\Models\\HealthCheck',
    ),
    'services' => 
    array (
      'ping' => 
      array (
        'bin' => '/sbin/ping',
      ),
      'composer' => 
      array (
        'bin' => 'composer',
      ),
    ),
    'assets' => 
    array (
      'css' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\vendor/pragmarx/health/src/resources/dist/css/app.css',
      'js' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\vendor/pragmarx/health/src/resources/dist/js/app.js',
    ),
    'cache_files_base_path' => 'app/pragmarx/health',
    'notifications' => 
    array (
      'enabled' => false,
      'notify_on' => 
      array (
        'panel' => false,
        'check' => true,
        'string' => true,
        'resource' => false,
      ),
      'action-title' => 'View App Health',
      'action_message' => 'The \'%s\' service is in trouble and needs attention%s',
      'from' => 
      array (
        'name' => 'Laravel Health Checker',
        'address' => 'healthchecker@mydomain.com',
        'icon_emoji' => ':anger:',
      ),
      'scheduler' => 
      array (
        'enabled' => true,
        'frequency' => 'everyMinute',
      ),
      'users' => 
      array (
        'model' => 'App\\User',
        'emails' => 
        array (
          0 => 'admin@mydomain.com',
        ),
      ),
      'channels' => 
      array (
        0 => 'mail',
        1 => 'slack',
      ),
      'notifier' => 'PragmaRX\\Health\\Notifications',
    ),
    'alert' => 
    array (
      'success' => 
      array (
        'type' => 'success',
        'message' => 'Everything is fine with this resource',
      ),
      'error' => 
      array (
        'type' => 'error',
        'message' => 'We are having trouble with this resource',
      ),
    ),
    'style' => 
    array (
      'columnSize' => 2,
      'button_lines' => 'multi',
      'multiplier' => 0.4,
      'opacity' => 
      array (
        'healthy' => '0.4',
        'failing' => '1',
      ),
    ),
    'views' => 
    array (
      'panel' => 'pragmarx/health::default.panel',
      'empty-panel' => 'pragmarx/health::default.empty-panel',
      'partials' => 
      array (
        'well' => 'pragmarx/health::default.partials.well',
      ),
    ),
    'string' => 
    array (
      'glue' => '-',
      'ok' => 'OK',
      'fail' => 'FAIL',
    ),
    'routes' => 
    array (
      'prefix' => '/admin/health',
      'namespace' => 'PragmaRX\\Health\\Http\\Controllers\\Health',
      'notification' => 'pragmarx.health.panel',
      'middleware' => 
      array (
        0 => 'throttle:60,1',
        1 => 'IsAdmin',
      ),
      'list' => 
      array (
        0 => 
        array (
          'uri' => '/admin/health/panel',
          'name' => 'pragmarx.health.panel',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@panel',
          'middleware' => 
          array (
          ),
        ),
        1 => 
        array (
          'uri' => '/admin/health/check',
          'name' => 'pragmarx.health.check',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@check',
          'middleware' => 
          array (
          ),
        ),
        2 => 
        array (
          'uri' => '/admin/health/string',
          'name' => 'pragmarx.health.string',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@string',
          'middleware' => 
          array (
          ),
        ),
        3 => 
        array (
          'uri' => '/admin/health/resources',
          'name' => 'pragmarx.health.resources.all',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@allResources',
          'middleware' => 
          array (
          ),
        ),
        4 => 
        array (
          'uri' => '/admin/health/resources/{slug}',
          'name' => 'pragmarx.health.resources.get',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@getResource',
          'middleware' => 
          array (
          ),
        ),
        5 => 
        array (
          'uri' => '/admin/health/assets/css/app.css',
          'name' => 'pragmarx.health.assets.css',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@assetAppCss',
          'middleware' => 
          array (
          ),
        ),
        6 => 
        array (
          'uri' => '/admin/health/assets/js/app.js',
          'name' => 'pragmarx.health.assets.js',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@assetAppJs',
          'middleware' => 
          array (
          ),
        ),
        7 => 
        array (
          'uri' => '/admin/health/config',
          'name' => 'pragmarx.health.config',
          'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@config',
          'middleware' => 
          array (
          ),
        ),
      ),
    ),
    'urls' => 
    array (
      'panel' => '/health/panel',
    ),
    'config' => 
    array (
      'title' => 'Laravel Health Check Panel',
      'resources' => 
      array (
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\config\\health/resources',
        'enabled' => 
        array (
          0 => 'AppKey',
          1 => 'Cache',
          2 => 'ConfigurationCached',
          3 => 'Database',
          4 => 'DebugMode',
          5 => 'DirectoryPermissions',
          6 => 'DiskSpace',
          7 => 'EnvExists',
          8 => 'Filesystem',
          9 => 'Framework',
          10 => 'Https',
          11 => 'LaravelServices',
          12 => 'Latency',
          13 => 'LocalStorage',
          14 => 'Mail',
          15 => 'MigrationsUpToDate',
          16 => 'MySql',
          17 => 'MySqlConnectable',
          18 => 'NginxServer',
          19 => 'Php',
          20 => 'Queue',
          21 => 'QueueWorkers',
          22 => 'RebootRequired',
          23 => 'Redis',
          24 => 'RedisConnectable',
          25 => 'RedisServer',
          26 => 'RoutesCached',
          27 => 'SecurityChecker',
          28 => 'ServerLoad',
          29 => 'ServerUptime',
          30 => 'Supervisor',
        ),
      ),
      'sort_by' => 'slug',
      'cache' => 
      array (
        'key' => 'health-resources',
        'minutes' => false,
      ),
      'database' => 
      array (
        'enabled' => false,
        'graphs' => 
        array (
          'enabled' => true,
          'height' => 90,
        ),
        'max_records' => 30,
        'model' => 'PragmaRX\\Health\\Data\\Models\\HealthCheck',
      ),
      'services' => 
      array (
        'ping' => 
        array (
          'bin' => '/sbin/ping',
        ),
        'composer' => 
        array (
          'bin' => 'composer',
        ),
      ),
      'assets' => 
      array (
        'css' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\vendor/pragmarx/health/src/resources/dist/css/app.css',
        'js' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\vendor/pragmarx/health/src/resources/dist/js/app.js',
      ),
      'cache_files_base_path' => 'app/pragmarx/health',
      'notifications' => 
      array (
        'enabled' => false,
        'notify_on' => 
        array (
          'panel' => false,
          'check' => true,
          'string' => true,
          'resource' => false,
        ),
        'action-title' => 'View App Health',
        'action_message' => 'The \'%s\' service is in trouble and needs attention%s',
        'from' => 
        array (
          'name' => 'Laravel Health Checker',
          'address' => 'healthchecker@mydomain.com',
          'icon_emoji' => ':anger:',
        ),
        'scheduler' => 
        array (
          'enabled' => true,
          'frequency' => 'everyMinute',
        ),
        'users' => 
        array (
          'model' => 'App\\User',
          'emails' => 
          array (
            0 => 'admin@mydomain.com',
          ),
        ),
        'channels' => 
        array (
          0 => 'mail',
          1 => 'slack',
        ),
        'notifier' => 'PragmaRX\\Health\\Notifications',
      ),
      'alert' => 
      array (
        'success' => 
        array (
          'type' => 'success',
          'message' => 'Everything is fine with this resource',
        ),
        'error' => 
        array (
          'type' => 'error',
          'message' => 'We are having trouble with this resource',
        ),
      ),
      'style' => 
      array (
        'columnSize' => 2,
        'button_lines' => 'multi',
        'multiplier' => 0.4,
        'opacity' => 
        array (
          'healthy' => '0.4',
          'failing' => '1',
        ),
      ),
      'views' => 
      array (
        'panel' => 'pragmarx/health::default.panel',
        'empty-panel' => 'pragmarx/health::default.empty-panel',
        'partials' => 
        array (
          'well' => 'pragmarx/health::default.partials.well',
        ),
      ),
      'string' => 
      array (
        'glue' => '-',
        'ok' => 'OK',
        'fail' => 'FAIL',
      ),
      'routes' => 
      array (
        'prefix' => '/admin/health',
        'namespace' => 'PragmaRX\\Health\\Http\\Controllers\\Health',
        'notification' => 'pragmarx.health.panel',
        'middleware' => 
        array (
          0 => 'throttle:60,1',
          1 => 'IsAdmin',
        ),
        'list' => 
        array (
          0 => 
          array (
            'uri' => '/admin/health/panel',
            'name' => 'pragmarx.health.panel',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@panel',
            'middleware' => 
            array (
            ),
          ),
          1 => 
          array (
            'uri' => '/admin/health/check',
            'name' => 'pragmarx.health.check',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@check',
            'middleware' => 
            array (
            ),
          ),
          2 => 
          array (
            'uri' => '/admin/health/string',
            'name' => 'pragmarx.health.string',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@string',
            'middleware' => 
            array (
            ),
          ),
          3 => 
          array (
            'uri' => '/admin/health/resources',
            'name' => 'pragmarx.health.resources.all',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@allResources',
            'middleware' => 
            array (
            ),
          ),
          4 => 
          array (
            'uri' => '/admin/health/resources/{slug}',
            'name' => 'pragmarx.health.resources.get',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@getResource',
            'middleware' => 
            array (
            ),
          ),
          5 => 
          array (
            'uri' => '/admin/health/assets/css/app.css',
            'name' => 'pragmarx.health.assets.css',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@assetAppCss',
            'middleware' => 
            array (
            ),
          ),
          6 => 
          array (
            'uri' => '/admin/health/assets/js/app.js',
            'name' => 'pragmarx.health.assets.js',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@assetAppJs',
            'middleware' => 
            array (
            ),
          ),
          7 => 
          array (
            'uri' => '/admin/health/config',
            'name' => 'pragmarx.health.config',
            'action' => 'PragmaRX\\Health\\Http\\Controllers\\Health@config',
            'middleware' => 
            array (
            ),
          ),
        ),
      ),
      'urls' => 
      array (
        'panel' => '/health/panel',
      ),
    ),
    'dist_path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\vendor\\pragmarx\\health\\src/resources/dist',
  ),
  'languages' => 
  array (
    'en' => 'English',
    'pt' => 'Portugaise',
    'es' => 'Español',
    'fr' => 'Français',
  ),
  'logging' => 
  array (
    'default' => 'InternalErrors',
    'channels' => 
    array (
      'InternalErrors' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/InternalErrors/InternalErrors.log',
        'level' => 'debug',
        'days' => 60,
        'ignore_exceptions' => false,
      ),
      'Emergency' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/Emergency/Emergency.log',
        'level' => 'debug',
        'days' => 1204,
      ),
      'Performance' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/Performance/Performance.log',
        'level' => 'info',
        'days' => 1204,
      ),
      'DTO' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/DTO/DTO.log',
        'level' => 'info',
        'days' => 1204,
      ),
      'AppDebug' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/AppDebug/AppDebug.log',
        'level' => 'debug',
        'days' => 60,
      ),
      'ClientDebug' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/ClientDebug/ClientDebug.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'AppInfo' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/AppInfo/AppInfo.log',
        'level' => 'debug',
        'days' => 7,
      ),
      'Connections-In' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/Connections-In/Connections-In.log',
        'level' => 'debug',
        'days' => 1204,
      ),
      'Connections-Out' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/Connections-Out/Connections-Out.log',
        'level' => 'debug',
        'days' => 1204,
      ),
      'DeCS' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/DeCS/DeCS.log',
        'level' => 'debug',
        'days' => 1204,
      ),
      'DeCSImporter' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/DeCSImporter/DeCSImporter.log',
        'level' => 'debug',
        'days' => 1204,
      ),
      'Console' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\logs/Console/Console.log',
        'level' => 'debug',
        'days' => 1204,
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.mailgun.org',
    'port' => 587,
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Example',
    ),
    'encryption' => 'tls',
    'username' => NULL,
    'password' => NULL,
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\resources\\views/vendor/mail',
      ),
    ),
    'log_channel' => NULL,
  ),
  'picoexplorerconfig' => 
  array (
    'ThrottleConfig' => 
    array (
      'PICO' => 
      array (
        'maxAttempts' => 1,
        'decayMinutes' => 1,
      ),
      'switchLang' => 
      array (
        'maxAttempts' => 1,
        'decayMinutes' => 1,
      ),
      'savePreviousInfo' => 
      array (
        'maxAttempts' => 1,
        'decayMinutes' => 1,
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => NULL,
        'secret' => NULL,
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'PICOExplorer\\User',
      'key' => NULL,
      'secret' => NULL,
      'webhook' => 
      array (
        'secret' => NULL,
        'tolerance' => 300,
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => true,
    'files' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
    'same_site' => NULL,
  ),
  'throttle' => 
  array (
    'driver' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\resources\\views',
    ),
    'compiled' => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd\\storage\\framework\\views',
  ),
  'debug-server' => 
  array (
    'host' => 'tcp://127.0.0.1:9912',
  ),
  'trustedproxy' => 
  array (
    'proxies' => NULL,
    'headers' => 30,
  ),
  'activitylog' => 
  array (
    'enabled' => true,
    'delete_records_older_than_days' => 365,
    'default_log_name' => 'default',
    'default_auth_driver' => NULL,
    'subject_returns_soft_deleted_models' => false,
    'activity_model' => 'Spatie\\Activitylog\\Models\\Activity',
    'table_name' => 'activity_log',
    'database_connection' => 'mysql',
  ),
  'ide-helper' => 
  array (
    'filename' => '_ide_helper',
    'format' => 'php',
    'meta_filename' => '.phpstorm.meta.php',
    'include_fluent' => false,
    'write_model_magic_where' => true,
    'write_eloquent_model_mixins' => false,
    'include_helpers' => false,
    'helper_files' => 
    array (
      0 => 'C:\\xampp\\htdocs\\home\\apps\\bvsalud.org\\pesquisa\\htdocs\\pico\\PICO-BackEnd/vendor/laravel/framework/src/Illuminate/Support/helpers.php',
    ),
    'model_locations' => 
    array (
      0 => 'app',
    ),
    'extra' => 
    array (
      'Eloquent' => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Builder',
        1 => 'Illuminate\\Database\\Query\\Builder',
      ),
      'Session' => 
      array (
        0 => 'Illuminate\\Session\\Store',
      ),
    ),
    'magic' => 
    array (
      'Log' => 
      array (
        'debug' => 'Monolog\\Logger::addDebug',
        'info' => 'Monolog\\Logger::addInfo',
        'notice' => 'Monolog\\Logger::addNotice',
        'warning' => 'Monolog\\Logger::addWarning',
        'error' => 'Monolog\\Logger::addError',
        'critical' => 'Monolog\\Logger::addCritical',
        'alert' => 'Monolog\\Logger::addAlert',
        'emergency' => 'Monolog\\Logger::addEmergency',
      ),
    ),
    'interfaces' => 
    array (
    ),
    'custom_db_types' => 
    array (
    ),
    'model_camel_case_properties' => false,
    'type_overrides' => 
    array (
      'integer' => 'int',
      'boolean' => 'bool',
    ),
    'include_class_docblocks' => false,
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
