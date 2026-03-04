<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ensure ticket_number exists (may have been added by previous failed migration)
        if (!Schema::hasColumn('tickets', 'ticket_number')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('ticket_number')->nullable()->after('id');
            });
        }

        // remove any erroneous empty/duplicate index before fixing
        try {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropUnique('tickets_ticket_number_unique');
            });
        } catch (\Exception $e) {
            // index may not exist, ignore
        }

        // populate missing ticket_numbers and re-generate simple sequential
        $countsByDate = [];
        $tickets = DB::table('tickets')->orderBy('created_at')->get();
        foreach ($tickets as $t) {
            if (empty($t->ticket_number)) {
                $date = Carbon::parse($t->created_at)->toDateString();
                if (!isset($countsByDate[$date])) {
                    $countsByDate[$date] = 0;
                }
                $countsByDate[$date]++;
                $seq = str_pad($countsByDate[$date], 2, '0', STR_PAD_LEFT);
                $num = 'T - ' . Carbon::parse($t->created_at)->format('dmY') . $seq;
                DB::table('tickets')->where('id', $t->id)->update(['ticket_number' => $num]);
            }
        }

        // now set column non-nullable and unique
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('ticket_number')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique('tickets_ticket_number_unique');
            $table->dropColumn('ticket_number');
        });
    }
};
