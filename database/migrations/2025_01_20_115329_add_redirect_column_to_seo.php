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
        Schema::table('core_seo', function (Blueprint $table) {
            $table->enum('redirect', [301, 302])->default(301)->after('alias');
        });

        Schema::table('core_seo_translations', function (Blueprint $table) {
            $table->string('slug_redirect')->nullable()->after('slug_suffix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_seo', function (Blueprint $table) {
            $table->dropColumn('redirect');
        });

        Schema::table('core_seo_translations', function (Blueprint $table) {
            $table->dropColumn('slug_redirect');
        });
    }
};
