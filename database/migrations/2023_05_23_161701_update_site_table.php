<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Wepa\Core\Models\Site;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('core_seo')
            ->where('alias', 'home')
            ->update(['model_type' => Site::class, 'model_id' => 1]);

        Schema::table('core_site', function (Blueprint $table) {
            $table->dropForeign('core_site_seo_id_foreign');
            $table->dropColumn('seo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_site', function (Blueprint $table) {
            $table->foreignId('seo_id')->nullable();
        });
    }
};
