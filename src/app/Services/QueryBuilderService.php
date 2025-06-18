<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class QueryBuilderService
{
    const SORT_DIRECTION_MAP = [
        '+' => 'asc',
        '-' => 'desc'
    ];
    const DEFAULT_DIRECTION = '+';
    const SORT_PARAM_NAME = 'sort';

    /**
     * Dynamically generate query builder from given parameters
     *
     * @param array<string, mixed> $parameters
     * @param Builder $query
     * @param array<string,string> $aliases
     * @return Builder
     */
    public function buildQueryFromParameters(array $parameters, Builder $query, array $aliases = []): Builder
    {
        if (isset($parameters[self::SORT_PARAM_NAME])) {
            $query = isset(self::SORT_DIRECTION_MAP[$this->getSortDirectionIndicator($parameters[self::SORT_PARAM_NAME])])
                ? $query->orderBy($this->getSortedColumn($parameters[self::SORT_PARAM_NAME]), self::SORT_DIRECTION_MAP[$this->getSortDirectionIndicator($parameters[self::SORT_PARAM_NAME])])
                : $query->orderBy($parameters[self::SORT_PARAM_NAME], self::SORT_DIRECTION_MAP[self::DEFAULT_DIRECTION]);
        }

        $parameters = array_filter($parameters, fn(string $key): bool => $key != self::SORT_PARAM_NAME, ARRAY_FILTER_USE_KEY);
        if (empty($parameters)) {
            return $query;
        }

        foreach ($parameters as $column => $value) {
            $query = $query->where($aliases[$column] ?? $column, '=', $value);
        }

        return $query;
    }

    /**
     * Filters out parameters invalid for query modification
     *
     * @param array<string, mixed> $parameters
     * @param string[] $select
     * @param string[] $model
     * @return array<string, mixed>
     */
    public function filterInvalidParameters(array $parameters, array $select, array $modelColumns): array
    {
        if (empty($parameters)) {
            return $parameters;
        }

        $permittedFields = array_unique([
            ...$modelColumns,
            ...array_map(
                function (string $field): string {
                    $field_explode = explode('as', $field);
                    return trim(array_pop($field_explode));
                },
                array_filter($select, fn(string $field): bool => !str_contains($field, '*') || str_contains($field, '(*)'))
            )
        ]);

        return array_filter(
            $parameters,
            fn($value, string $parameter): bool => in_array($parameter, $permittedFields) || ($parameter == self::SORT_PARAM_NAME && array_intersect([$value, $this->getSortedColumn($value)], $permittedFields)),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Extracts sort indication from sort expression string
     *
     * @param string $sortExpression
     * @return string
     */
    public function getSortDirectionIndicator(string $sortExpression): string
    {
        return substr($sortExpression, 0, 1);
    }

    /**
     * Extracts sort column from sort expression string
     *
     * @param string $sortExpression
     * @return string
     */
    public function getSortedColumn(string $sortExpression): string
    {
        return substr($sortExpression, 1);
    }
}
