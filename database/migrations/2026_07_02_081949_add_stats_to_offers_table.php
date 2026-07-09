<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedInteger('views')->default(0)->after('order');
            $table->unsignedInteger('redemptions')->default(0)->after('views');
            $table->unsignedInteger('saves')->default(0)->after('redemptions');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['views', 'redemptions', 'saves']);
        });
    }
};