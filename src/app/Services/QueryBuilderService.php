<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QueryBuilderService
{
    const SORT_DIRECTION_MAP = [
        '+' => 'asc',
        '-' => 'desc'
    ];

    /**
     * Dynamically generate query builder from given parameters
     *
     * @param array $parameters
     * @param Builder $query
     * @return Builder
     */
    public function buildQueryFromParameters(array $parameters, Builder $query): Builder
    {
        if (isset($parameters['sort'])) {
            $query = $query->orderBy(substr($parameters['sort'], 1), self::SORT_DIRECTION_MAP[substr($parameters['sort'], 0, 1)] ?? self::SORT_DIRECTION_MAP['+']);
        }

        $parameters = array_filter($parameters, fn(string $key): bool => $key != 'sort', ARRAY_FILTER_USE_KEY);
        if (empty($parameters)) {
            return $query;
        }

        foreach ($parameters as $column => $value) {
            $query = $query->where($column, '=', $value);
        }

        return $query;
    }

    /**
     * Filters out parameters invalid for query modification
     *
     * @param array $parameters
     * @param array $select
     * @param Model $model
     * @return array
     */
    public function filterInvalidParameters(array $parameters, array $select, Model $model): array
    {
        if (empty($parameters)) {
            return $parameters;
        }

        $permittedFields = array_unique([...$model->attributesToArray(), ...array_map(fn(string $field): string => trim(end(explode('as', $field))), array_filter($select, fn(string $field): bool => !str_contains($field, '*')))]);
        return array_filter(array_keys($parameters), fn(string $parameter): bool => in_array($parameter, $permittedFields));
    }
}
