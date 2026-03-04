<?php
require __DIR__ . '/../vendor/autoload.php';

// bootstrap the framework so Eloquent and config are available
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Ticket;

$count = Ticket::count();
echo "Count: $count\n";
foreach (Ticket::take(10)->get() as $t) {
    printf("%s | %s | %s | user_id:%s | assigned_to:%s\n", $t->ticket_number, $t->title, $t->status, $t->user_id, $t->assigned_to);
}
