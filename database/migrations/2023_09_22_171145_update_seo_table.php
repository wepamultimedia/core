<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_seo_translations', function (Blueprint $table) {
            $table->dropUnique('core_seo_translations_slug_unique');

            $table->unique(['locale', 'slug_prefix', 'slug']);
            $table->string('slug_prefix')->index()->after('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_seo_translations', function (Blueprint $table) {
            $table->dropUnique('core_seo_translations_locale_slug_prefix_slug_unique');
            $table->dropIndex('core_seo_translations_slug_prefix_index');
            $table->dropColumn('slug_prefix');
        });
    }
};
