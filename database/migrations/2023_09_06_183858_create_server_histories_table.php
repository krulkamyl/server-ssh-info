<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('server_histories', function (Blueprint $table) {
            $table->id();
            $table->string('hostname')->index();
            $table->integer('RAM_usage')->nullable()->default(null);
            $table->integer('RAM_max')->nullable()->default(null);
            $table->float('CPU_usage', 5)->nullable()->default(null);
            $table->string('load_avg')->nullable()->default(null);
            $table->string('disk_capacity')->nullable()->default(null);
            $table->string('services_check')->index()->nullable()->default(null);
            $table->string('connections_check')->index()->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_histories');
    }
};
