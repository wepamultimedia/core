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
        Schema::table('core_seo', function (Blueprint $table) {
            $table->string('change_freq')->default('never');
            $table->string('priority')->default('0.7');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_seo', function (Blueprint $table) {
            $table->dropColumn('change_freq');
            $table->dropColumn('priority');
        });
    }
};
