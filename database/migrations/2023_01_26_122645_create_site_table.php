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
		Schema::create('core_site', function(Blueprint $table) {
			$table->id();
			$table->foreignId('seo_id');
			$table->boolean('maintenance')->default(false);
			$table->string('company')->nullable();
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->string('mobile')->nullable();
			$table->string('address')->nullable();
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
			$table->string('facebook_url')->nullable();
			$table->string('twitter_url')->nullable();
			$table->string('youtube_url')->nullable();
			$table->string('skype_url')->nullable();
			$table->string('linkedin_url')->nullable();
			$table->string('instagram_url')->nullable();
			$table->string('vimeo_url')->nullable();
			$table->string('twitch_url')->nullable();
			$table->string('icon')->nullable();
			$table->string('logo')->nullable();
			$table->string('logo_invert')->nullable();
			$table->timestamps();
			
			$table->foreign('seo_id')
				->references('id')
				->on('core_seo');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('core_site');
	}
};
