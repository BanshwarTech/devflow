<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
if (Illuminate\Support\Facades\Schema::hasTable('comments')) {
    Illuminate\Support\Facades\Schema::drop('comments');
    echo "Dropped comments table\n";
} else {
    echo "Comments table does not exist\n";
}
