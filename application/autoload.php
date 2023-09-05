<?php

spl_autoload_register(function ($className) {
    $class_dirs = [
        '/application/core/',
        '/application/controllers/',
        '/application/models/',
    ];

    foreach ($class_dirs as $dir) {
        $file = WORK_DIR . $dir . $className . '.class.php';

        if (file_exists($file)) {
            include_once $file;
        }
    }
});
