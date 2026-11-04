<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Public-site page views. No raw IP addresses are kept: `visitor_id` is a hash that changes every
     * day (enough to count unique visitors), and `ip_masked` drops the last part of the address.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('path', 255);
            $table->string('route_name', 100)->nullable();
            $table->char('visitor_id', 64);
            $table->string('ip_masked', 45)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('browser', 40)->nullable();
            $table->string('platform', 40)->nullable();
            $table->string('referrer_host', 191)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->unsignedSmallInteger('status')->default(200);
            $table->timestamp('created_at')->useCurrent();

            $table->index('created_at');
            $table->index(['created_at', 'visitor_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
