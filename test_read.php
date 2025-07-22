<?php
$path = __DIR__ . '/Modules/Core/routes/web.php';
if (file_exists($path)) {
    echo "File exists\n";
    echo "Contents:\n";
    echo file_get_contents($path);
} else {
    echo "File does NOT exist\n";
}