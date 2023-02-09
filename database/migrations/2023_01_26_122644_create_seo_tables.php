<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('core_seo', function(Blueprint $table) {
			$table->id();
			$table->string('package')->index()->nullable();
			$table->string('alias')->index()->nullable();
			$table->string('controller')->nullable();
			$table->string('action')->nullable();
			$table->string('request_params')->nullable();
			$table->string('robots')->index()->nullable();
			$table->string('route_params')->nullable();
			$table->boolean('canonical')->default(false);
			$table->string('page_type')->default('website');
			$table->string('article_type')->nullable();
			$table->string('image')->nullable();
			$table->string('facebook_image')->nullable();
			$table->string('twitter_image')->nullable();
		});
		Schema::create('core_seo_translations', function(Blueprint $table) {
			$table->id();
			$table->foreignId('seo_id');
			$table->string('locale')->index();
			$table->unique(['locale', 'seo_id']);
			
			$table->string('keyword')->unique()->nullable();
			$table->string('title');
			$table->string('slug')->unique()->index()->nullable();
			$table->string('description')->nullable();
			$table->string('image_title')->nullable();
			$table->string('image_alt')->nullable();
			$table->string('facebook_title')->nullable();
			$table->string('facebook_description')->nullable();
			$table->string('facebook_image_title')->nullable();
			$table->string('facebook_image_alt')->nullable();
			$table->string('twitter_title')->nullable();
			$table->string('twitter_description')->nullable();
			$table->string('twitter_image_title')->nullable();
			$table->string('twitter_image_alt')->nullable();
			
			$table->foreign('seo_id')
				->references('id')
				->on('core_seo')
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
		Schema::dropIfExists('core_seo');
		Schema::dropIfExists('core_seo_translations');
	}
};
