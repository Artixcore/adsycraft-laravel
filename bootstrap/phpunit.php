<?php

require dirname(__DIR__).'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Test database: SQLite in-memory locally, MySQL in CI
|--------------------------------------------------------------------------
| When CI=1 (e.g. GitHub Actions), DB_CONNECTION and DB_DATABASE are set
| by the workflow. Otherwise use sqlite :memory: for fast local tests.
*/
if (! getenv('CI')) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=:memory:');
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = ':memory:';
}
