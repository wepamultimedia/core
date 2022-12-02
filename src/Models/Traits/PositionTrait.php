<?php

namespace Wepa\Core\Models\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


trait PositionTrait
{
	/**
	 * @param array|null $where
	 *
	 * @return mixed
	 */
	public function currentPosition(array $where = null): mixed
	{
		$currentPosition = $this->when($where, function($query, $where) {
			$query->where($where);
		})->max('position');
		
		if($currentPosition) {
			return $currentPosition;
		}
		
		return 0;
	}
	
	/**
	 * @param array|null $condition
	 *
	 * @return mixed
	 */
	public function nextPosition(array $condition = null): mixed
	{
		$nextPosition = $this->when($condition, function($query, $condition) {
			$query->where($condition);
		})->max('position');
		
		if($nextPosition) {
			return $nextPosition + 1;
		}
		
		return 1;
	}
	
	/**
	 * @param Model $model
	 * @param array $condition
	 *
	 * @return void
	 */
	public function placeInFirstPosition(Model $model,
	                                     array $condition = []): void
	{
		if($model->position) {
			$this->updatePosition($model, 1, $condition);
		}
	}
	
	/**
	 * @param Model $model
	 * @param int $newPosition
	 * @param array $condition
	 *
	 * @return void
	 */
	public function updatePosition(Model $model,
	                               int   $newPosition,
	                               array $condition = []): void
	{
		$oldPosition = $model->position;
		
		if($oldPosition != $newPosition) {
			if(count($condition)) {
				$condition = $this->arrayToStringSqlCondition($condition);
				
				DB::table($this->table)
					->update(['position' => DB::raw("CASE WHEN (position = $oldPosition and $condition) THEN $newPosition WHEN (position > $oldPosition and position <= $newPosition and $condition) THEN position - 1 WHEN (position < $oldPosition and position >= $newPosition and $condition) THEN position + 1 ELSE position END")]);
			} else {
				DB::table($this->table)
					->update(['position' => DB::raw("CASE WHEN (position = $oldPosition) THEN $newPosition WHEN (position > $oldPosition and position <= $newPosition) THEN position - 1 WHEN (position < $oldPosition and position >= $newPosition) THEN position + 1 ELSE position END")]);
			}
		}
	}
	
	/**
	 * @param array $condition
	 *
	 * @return array|string|string[]|null
	 */
	public function arrayToStringSqlCondition(array $condition): array|string|null
	{
		$conditionString = '';
		
		foreach($condition as $key => $value) {
			if($value === 'NULL' or !$value) {
				$conditionString .= "$key IS NULL,";
			} else {
				$conditionString .= "$key=$value,";
			}
		}
		
		return preg_replace('/,$/', '', $conditionString);
	}
	
	/**
	 * @param Model $model
	 * @param array $condition
	 *
	 * @return void
	 */
	public function placeInLastPosition(Model $model,
	                                    array $condition = []): void
	{
		$this->updatePosition($model,
			$this->lastPosition($condition),
			$condition);
	}
	
	/**
	 * @param array $condition
	 *
	 * @return int
	 */
	public function lastPosition(array $condition = []): int
	{
		$nextPosition = $this->when($condition, function($query, $condition) {
			$query->where($condition);
		})->max('position');
		
		if($nextPosition) {
			return $nextPosition;
		}
		
		return 1;
	}
}