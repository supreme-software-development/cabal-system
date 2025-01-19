<?php
return [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_user' => getenv('DB_USER') ?: 'sa',
    'db_pass' => getenv('DB_PASS') ?: 'PASS',
    'db_name' => getenv('DB_NAME') ?: 'ACCOUNT'
]; 