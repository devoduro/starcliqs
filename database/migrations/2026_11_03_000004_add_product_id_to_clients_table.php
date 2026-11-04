<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** A client can point to one of our products; its card links there when it has no website. */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('website_url')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
