<?php

try {
    (require('production.php'))->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
