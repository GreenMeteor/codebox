<?php

$requirements = [];

// Check if PHP version is at least 8.1
if (PHP_VERSION_ID < 80100) {
    $requirements[] = 'Please upgrade to PHP Version 8.1 or later!';
}

return $requirements;