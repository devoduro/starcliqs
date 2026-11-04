<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Details shown on the Academy programme cards. */
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('icon')->default('fa-code')->after('title');
            $table->string('level')->nullable()->after('icon');
            $table->string('schedule')->nullable()->after('duration');
            $table->date('registration_closes_on')->nullable()->after('starts_on');
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn(['icon', 'level', 'schedule', 'registration_closes_on']);
        });
    }
};
