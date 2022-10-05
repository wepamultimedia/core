<?php

namespace Wepa\Core\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Wepa\Core\Database\seeders\AdminUserSedder;
use Wepa\Core\Database\seeders\CoreSeeder;
use Wepa\Core\Database\seeders\RoleSeeder;


class SeederTest extends TestCase
{
	use RefreshDatabase;
	/**
     * Comprobamos si el usuario admin@admin.com fue creado
     *
     * @return void
     */
    public function test_super_admin_user(): void
    {
		$this->seed(CoreSeeder::class);
		
	    $this->assertDatabaseHas('users', [
		    'email' => 'admin@admin.com',
	    ]);
	
	    $this->assertDatabaseHas('model_has_roles', [
		    'role_id' => 5,
		    'model_type' => 'App\Models\User',
		    'model_id' => 1
	    ]);
    }
	
	/**
	 * @return void
	 */
	public function test_roles()
	{
		$this->seed(CoreSeeder::class);
		
		$this->assertDatabaseHas('roles', [
			'name' => 'admin',
		]);
		
		$this->assertDatabaseHas('roles', [
			'name' => 'super.admin',
		]);
	}
}
