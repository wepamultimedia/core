<?php

use Carbon\Carbon;
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
        Schema::table('core_seo', function (Blueprint $table) {
            $table->dateTime('last_mod')->nullable()->default(Carbon::now());
            $table->string('model_type')->nullable()->index();
            $table->foreignId('model_id')->nullable()->index();
        });

        Schema::table('core_seo_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('core_seo', ['last_mod', 'model_type', 'model_id']);
    }
};
