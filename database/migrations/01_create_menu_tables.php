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
        Schema::create('core_menu', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->string('package');
            $table->string('app');
            $table->string('route')->nullable();
            $table->string('active')->nullable();
            $table->string('can')->nullable();
            $table->string('icon')->nullable();
            $table->bigInteger('position');
        });

        Schema::create('core_menu_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id');
            $table->string('locale')->index();
            $table->string('label');

            $table->unique(['locale', 'menu_id']);
            $table->foreign('menu_id')
                ->references('id')
                ->on('core_menu')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_menu');
        Schema::dropIfExists('core_menu_translations');
    }
};
