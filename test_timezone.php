<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now test
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Checking User Timezones:\n";
echo "========================\n\n";

$users = User::select('id', 'email', 'timezone')->limit(5)->get();

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Timezone: {$user->timezone}\n";
    echo "---\n";
}

echo "\n\nDatabase Check:\n";
echo "========================\n";
$result = DB::select("SELECT id, email, timezone FROM users LIMIT 5");
foreach ($result as $row) {
    echo "ID: {$row->id} | Email: {$row->email} | TZ: {$row->timezone}\n";
}
