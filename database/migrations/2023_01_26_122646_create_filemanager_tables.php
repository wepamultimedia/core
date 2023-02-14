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
        Schema::create('core_files_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('extension');
            $table->string('icon')->nullable();
            $table->string('mime')->nullable();
        });
        Schema::create('core_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('type_id')->nullable();
            $table->string('name')->index();
            $table->string('alt_name')->nullable();
            $table->string('description')->nullable();
            $table->string('file')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('core_files_types')
                ->onDelete('cascade');
        });
        Schema::create('core_model_has_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id');
            $table->integer('model_class')->index();
            $table->string('route');
            $table->string('model_id');

            $table->foreign('file_id')
                ->references('id')
                ->on('core_files')
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
        Schema::drop('core_files');
    }
};
