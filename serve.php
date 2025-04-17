<?php
$port = getenv('PORT') ?: 8080;

echo "Serving on http://localhost:$port\n";

// Tangani Ctrl+C (hanya Linux/Mac)
if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGINT, function () {
        echo "\nServer stopped gracefully.\n";
        exit(0); // Keluar dengan status normal
    });
}

// Jalankan PHP built-in server
passthru("php -S localhost:$port -t .");

// Untuk mendeteksi sinyal (Linux/Mac)
if (function_exists('pcntl_signal_dispatch')) {
    pcntl_signal_dispatch();
}
