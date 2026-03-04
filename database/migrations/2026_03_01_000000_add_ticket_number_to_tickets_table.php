<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // only add column if it doesn't already exist (migration may have partially run)
        if (!Schema::hasColumn('tickets', 'ticket_number')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('ticket_number')->unique()->after('id');
            });
        }

        // generate ticket numbers for existing records if any (if ticket_number was just added or still empty)
        if (Schema::hasColumn('tickets', 'created_at')) {
            $tickets = \DB::table('tickets')->orderBy('created_at')->get();
            $countsByDate = [];
            foreach ($tickets as $t) {
                if (empty($t->ticket_number)) {
                    $date = \Carbon\Carbon::parse($t->created_at)->toDateString();
                    if (!isset($countsByDate[$date])) {
                        $countsByDate[$date] = 0;
                    }
                    $countsByDate[$date]++;
                    $seq = str_pad($countsByDate[$date], 2, '0', STR_PAD_LEFT);
                    $num = 'T - ' . Carbon\Carbon::parse($t->created_at)->format('dmY') . $seq;
                    \DB::table('tickets')->where('id', $t->id)->update(['ticket_number' => $num]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['ticket_number']);
            $table->dropColumn('ticket_number');
        });
    }
};
