<?php
$port = 8006;
$docRoot = __DIR__ . '/public';

// Start server in background
$cmd = sprintf('start /B php -S 127.0.0.1:%d -t "%s" 2>NUL', $port, $docRoot);
pclose(popen($cmd, 'r'));
sleep(2);

$pages = [
    '/'                => 'home_test.html',
    '/admin'           => 'admin_test.html',
    '/login'           => 'login_test.html',
    '/register'        => 'register_test.html',
    '/shop'            => 'shop_test.html',
    '/cart'            => 'cart_test.html',
    '/checkout'        => 'checkout_test.html',
    '/about'           => 'about_test.html',
];

$tempDir = getenv('TEMP');
$results = [];

foreach ($pages as $path => $file) {
    $url = "http://127.0.0.1:{$port}{$path}";
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'header' => "Accept: text/html\r\n"]]);
    $start = microtime(true);
    $content = @file_get_contents($url, false, $ctx);
    $time = round((microtime(true) - $start) * 1000);

    if ($content === false) {
        $results[] = sprintf("%-30s %s (Error: %s)", $path, 'FAIL', error_get_last()['message'] ?? 'unknown');
    } else {
        file_put_contents("$tempDir/$file", $content);
        $size = strlen($content);
        $status = preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0] ?? '', $m) ? $m[1] : '200';
        $results[] = sprintf("%-30s %s - %d bytes - %dms", $path, $status, $size, $time);
    }
}

// Kill server
exec('taskkill /F /IM php.exe 2>NUL');

echo "=== TEST RESULTS ===\n";
echo "Server: http://127.0.0.1:{$port}\n";
echo str_repeat('-', 60) . "\n";
echo implode("\n", $results);
echo "\n" . str_repeat('-', 60) . "\n";
echo "Files saved to: {$tempDir}\\*_test.html\n";
