<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wepa\Core\Models\Backend\Menu;


return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('core_backend_menu', function(Blueprint $table) {
			$table->id();
			$table->string('route')->nullable();
			$table->string('active')->nullable();
			$table->string('can')->nullable();
			$table->string('icon')->nullable();
			$table->bigInteger('parent_id')->nullable();
		});
		
		Schema::create('core_backend_menu_translations', function(Blueprint $table) {
			$table->id();
			$table->foreignId('menu_id');
			$table->string('locale')->index();
			$table->string('label');
			
			$table->unique(['locale', 'menu_id']);
			$table->foreign('menu_id')->references('id')->on('core_backend_menu')->onDelete('cascade');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('core_backend_menu');
		Schema::dropIfExists('core_backend_menu_translations');
	}
};
