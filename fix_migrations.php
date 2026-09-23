<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

$files = collect(File::files(database_path('migrations')))
    ->map(fn($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))
    ->sort()
    ->values();

$done = DB::table('migrations')->pluck('migration')->toArray();
$batch = (int) DB::table('migrations')->max('batch') + 1;

// Phrases that indicate "this already exists in the DB, just mark it done"
$skipPhrases = [
    'already exists',        // table already exists
    'Duplicate column name', // column already exists
    'Duplicate key name',    // index already exists
    'errno: 121',            // duplicate foreign key constraint name
    'Duplicate foreign key', // some MySQL versions phrase it this way
];

foreach ($files as $migration) {
    if (in_array($migration, $done)) {
        continue;
    }

    $exitCode = Artisan::call('migrate', [
        '--path' => 'database/migrations/' . $migration . '.php',
        '--force' => true,
    ]);

    $output = Artisan::output();

    $isSkippable = false;
    foreach ($skipPhrases as $phrase) {
        if (stripos($output, $phrase) !== false) {
            $isSkippable = true;
            break;
        }
    }

    if ($isSkippable) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch,
        ]);
        echo "SKIPPED (already existed, marked done): $migration\n";
    } elseif ($exitCode === 0) {
        echo "MIGRATED: $migration\n";
    } else {
        echo "FAILED (needs manual check): $migration\n";
        echo $output . "\n";
        echo "---- Stopping here. Fix this migration, then re-run the script to continue with the rest. ----\n";
        break;
    }
}

echo "\nDone.\n";