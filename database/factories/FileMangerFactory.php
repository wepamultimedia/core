<?php

namespace Wepa\Core\Database\factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Wepa\Core\Models\File;


class FileMangerFactory extends Factory
{
	protected $model = File::class;
	
	public function definition()
	{
		return [
			'name'        => $this->faker->name,
			'url'         => $url = $this->faker->imageUrl(),
			'file'        => basename($url),
			'alt_name'    => $this->faker->title,
			'description' => $this->faker->sentence,
			'parent_id'   => null,
			'type_id'     => 14,
			'created_at'  => Carbon::now(),
			'updated_at'  => Carbon::now(),
		];
	}
}
