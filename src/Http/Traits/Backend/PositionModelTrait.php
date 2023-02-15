<?php

namespace Wepa\Core\Http\Traits\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait PositionModelTrait
{
    public function currentPosition(array $where = null): mixed
    {
        $currentPosition = $this->when($where, function ($query, $where) {
            $query->where($where);
        })->max('position');

        if ($currentPosition) {
            return $currentPosition;
        }

        return 0;
    }

    public static function nextPosition(array $condition = null): mixed
    {
        if ($nextPosition = self::when($condition, function ($query, $condition) {
            $query->where($condition);
        })
            ->max('position')) {
            return $nextPosition + 1;
        }

        return 1;
    }

    public function placeInFirstPosition(Model $model,
                                         array $condition = []): void
    {
        if ($model->position) {
            $this->updatePosition($model, 1, $condition);
        }
    }

    /**
     * @param  Model  $model
     */
    public function updatePosition(int $newPosition,
                                   array $condition = []): void
    {
        if ($newPosition > 0 and $newPosition <= self::lastPosition()) {
            $oldPosition = $this->position;
            if ($oldPosition != $newPosition) {
                if (count($condition)) {
                    $condition = $this->arrayToStringSqlCondition($condition);

                    DB::table($this->table)
                        ->update(['position' => DB::raw("CASE WHEN (position = $oldPosition and $condition) THEN $newPosition WHEN (position > $oldPosition and position <= $newPosition and $condition) THEN position - 1 WHEN (position < $oldPosition and position >= $newPosition and $condition) THEN position + 1 ELSE position END")]);
                } else {
                    DB::table($this->table)
                        ->update(['position' => DB::raw("CASE WHEN (position = $oldPosition) THEN $newPosition WHEN (position > $oldPosition and position <= $newPosition) THEN position - 1 WHEN (position < $oldPosition and position >= $newPosition) THEN position + 1 ELSE position END")]);
                }
            }
        }
    }

    public static function lastPosition(array $condition = []): int
    {
        $nextPosition = self::when($condition, function ($query, $condition) {
            $query->where($condition);
        })->max('position');

        if ($nextPosition) {
            return $nextPosition;
        }

        return 1;
    }

    /**
     * @return array|string|string[]|null
     */
    public function arrayToStringSqlCondition(array $condition): array|string|null
    {
        $conditionString = '';

        foreach ($condition as $key => $value) {
            if ($value === 'NULL' or ! $value) {
                $conditionString .= "$key IS NULL,";
            } else {
                $conditionString .= "$key=$value,";
            }
        }

        return preg_replace('/,$/', '', $conditionString);
    }

    /**
     * @param  Model  $model
     */
    public function placeInLastPosition(array $condition = []): void
    {
        $this->updatePosition(self::lastPosition($condition),
            $condition);
    }
}
