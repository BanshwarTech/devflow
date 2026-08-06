<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$tables = ['activities', 'notifications', 'attachments', 'comments'];
foreach ($tables as $table) {
    if (Illuminate\Support\Facades\Schema::hasTable($table)) {
        Illuminate\Support\Facades\Schema::drop($table);
        echo "Dropped $table table\n";
    } else {
        echo "$table table does not exist\n";
    }
}
