<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$public = __DIR__ . "/public";

// Try to serve static file from public/
if ($uri !== "/" && $uri !== "") {
    $file = $public . $uri;
    if (is_file($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimes = [
            "css" => "text/css; charset=utf-8",
            "js" => "application/javascript; charset=utf-8",
            "png" => "image/png",
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "gif" => "image/gif",
            "svg" => "image/svg+xml",
            "ico" => "image/x-icon",
            "woff" => "font/woff",
            "woff2" => "font/woff2",
            "json" => "application/json; charset=utf-8",
            "webp" => "image/webp",
        ];
        if (isset($mimes[$ext])) {
            header("Content-Type: " . $mimes[$ext]);
            header("Cache-Control: public, max-age=31536000, immutable");
            header("X-Content-Type-Options: nosniff");
            readfile($file);
            exit;
        }
    }
}

// Route through Laravel
require $public . "/index.php";
