<?php

/**
 * This file is referenced in phpunit.xml, in the root of the project,
 * so all file references in here are also relative to the root of
 * the project.
 */

// Make sure to boot Laravel, so we can access Artisan.
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Then run the migrations once, to speed up individual tests.
echo "Running migrations before starting tests\n";
Illuminate\Support\Facades\Artisan::call('migrate');
