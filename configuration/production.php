<?php

use DataStorage\Basis\DataSource;

if (!defined('DBMS')) {
    define('DBMS', SQLITE);
}
if (!defined('DATA_PATH')) {
    define('DATA_PATH', CONFIGURATION_ROOT . 'database.sqlite');
}
$dataSource = new DataSource(SQLITE . DATA_PATH);

return $dataSource;
