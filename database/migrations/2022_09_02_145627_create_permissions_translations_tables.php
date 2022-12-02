<?php


use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wepa\Core\Models\Permission;
use Wepa\Core\Models\Role;


return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'roles_translations', function(Blueprint $table) {
			$table->id();
			$table->foreignId('role_id');
			$table->string('locale')->index();
			$table->string('description');

			$table->unique(['locale', 'role_id']);
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
		});

		Schema::create('permissions_translations', function(Blueprint $table) {
			$table->id();
			$table->foreignId('permission_id');
			$table->string('locale')->index();
			$table->string('description');

			$table->unique(['locale', 'permission_id']);
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$tableNames = config('permission.table_names');
		Schema::table($tableNames['roles'], function(Blueprint $table) {
			$table->dropColumn('description');
		});
		Schema::table($tableNames['permissions'], function(Blueprint $table) {
			$table->dropColumn('description');
		});
	}
};
