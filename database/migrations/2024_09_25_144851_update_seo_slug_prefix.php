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
        Schema::table('core_seo_translations', function (Blueprint $table) {
            $table->string('slug_suffix')->after('slug')->nullable();
            $table->json('slug_prefix')->after('slug')->nullable();
        });

        $slugs = \Wepa\Core\Models\SeoTranslation::all();
        foreach ($slugs as $slug) {
            $slug->slug_suffix = $slug->slug;
            $slug->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_seo_translations', function (Blueprint $table) {
           $table->dropColumn('slug_prefix');
           $table->dropColumn('slug_suffix');
        });
    }
};
