<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wepa\Core\Models\Seo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('core_seo', function (Blueprint $table) {
            $table->string('redirect')->nullable()->change(); // Hacer la columna nullable
        });

        \Wepa\Core\Models\Seo::all()->each(function (Seo $seo) {
            $seo->update(['redirect' => null]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_seo', function (Blueprint $table) {
            $table->string('redirect')->nullable(false)->change(); // Revertir el cambio
        });
    }
};
